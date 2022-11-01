<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\HotelQuotation;
use App\Models\ServiceQuotation;
use App\Models\QuotationNote;
use App\Models\QuotationStatusLog;
use App\Models\QuotationImage;
use Auth;

class Quotation extends Model
{
    use SoftDeletes;

    protected $fillable = [

        'versionNo',
        'quotationParent',
        'userId',
        'inquiryId',
        'processedBy',
        'totalEmails',
        'quotationsTitle',
        'clientName',
        'clientEmail',
        'clientContact',
        'cityId',
        'citiesToVisit',
        'otherAreas',
        'tourFrom',
        'tourEnd',
        'adults',
        'children',
        'requiredServices',
        'userNotes',
        'validity',
        'status',
        'reason',
        'totalCost',
        'totalSales',
        'discountValue',
        'discountType',
        'extraMarkup',
        'markupTypeQuotation',
        'markupType',
        'liveQuotation',
        'isTemplate',
        'approvedVersionId',
        'email',
        'showPrice',
        'showCost',
        'staffRemarks',
        'expiryReason',
        'masterId',
    ];

    protected $dates = [
        'tourFrom',
        'tourEnd',
        'validity',
    ];

    protected $appends = ["quotation_versions"];

    public function user()
    {
        return $this->belongsTo(User::class, "userId");
    }

    public function processedByUser()
    {
        return $this->belongsTo(User::class, "processedBy");
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'cityId', 'city_id');
    }

    public function hotelQuotations()
    {
        return $this->hasMany(HotelQuotation::class, 'quotationId');
    }

    public function serviceQuotations()
    {
        return $this->hasMany(ServiceQuotation::class, 'quotationId');
    }

    public function mealQuotations()
    {
        return $this->hasMany(ServiceQuotation::class, 'quotationId')->where('serviceType', 'Meal');
    }

    public function transportQuotations()
    {
        return $this->hasMany(ServiceQuotation::class, 'quotationId')->whereIn('serviceType', ['By Air', 'By Road', 'By Train', 'Transport']);
    }

    public function activityQuotations()
    {
        return $this->hasMany(ServiceQuotation::class, 'quotationId')->where('serviceType', 'Activity');
    }

    public function otherServicesQuotations()
    {
        return $this->hasMany(ServiceQuotation::class, 'quotationId')->where('serviceType', 'Other');
    }

    public function optionalServicesQuotations()
    {
        return $this->hasMany(ServiceQuotation::class, 'quotationId')->where('serviceType', 'Optional');
    }

    public function quotationNotes()
    {
        return $this->hasMany(QuotationNote::class, 'quotationId');
    }

    public function cancellationPolicy()
    {
        return $this->hasMany(QuotationNote::class, 'quotationId')->where('type', 'cancellationPolicy');
    }

    public function bookingNotes()
    {
        return $this->hasMany(QuotationNote::class, 'quotationId')->where('type', 'bookingNotes');
    }

    public function paymentTerms()
    {
        return $this->hasMany(QuotationNote::class, 'quotationId')->where('type', 'paymentTerms');
    }

    public function freeText()
    {
        return $this->hasMany(QuotationNote::class, 'quotationId')->where('type', 'freeText');
    }

    public function quotationResponse()
    {
        return $this->hasMany(quotationResponse::class, 'quotationId');
    }

    public function quotationChat()
    {
        return $this->hasMany(quotationChat::class, 'quotationId');
    }

    public function quotationImages()
    {
        return $this->hasMany(QuotationImage::class, 'quotationId');
    }

    public function getPlannedCitiesAttribute()
    {
        $citiesToVisit = $this->attributes['citiesToVisit'];
        $citiesToVisit = json_decode($citiesToVisit);
        $cities = "";
        if ($citiesToVisit) {
            $cities = City::selectRaw('GROUP_CONCAT(title) as citiesToVisit')->whereIn('city_id', $citiesToVisit)->get();
            $cities = $cities[0]->citiesToVisit;
        }
        return $cities;
    }

    public function getQuotationVersionsAttribute()
    {
        $quotationId = $this->attributes["id"];
        $totalVersions = $this->where("quotationParent", $quotationId)->count();
        $totalVersions = $totalVersions + 1;
        return $totalVersions;
    }

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, "inquiryId");
    }

    public function itineraryBasic()
    {
        return $this->morphMany(ItineraryQuotation::class, 'quotationable');
    }

    public function chatMessage()
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function statusDetail()
    {
        return $this->hasOne(QuotationStatus::class, "id", "status");
    }

    public function copyQuotationData($quotation)
    {
        $user = Auth::user();

        $quotationPrevious = Quotation::find($this->id);
        $quotationId = $quotationPrevious->id;
        // $quotation = $this;

        if ($quotation) {

            QuotationStatusLog::create([
                'quotationId' => $quotation->id,
                'statusId' => 4,
                'userId' => $user->id,
            ]);

            if ($quotationPrevious->itineraryBasic) {
                $quotationPrevious->itineraryBasic->map(function ($row, $key) use ($quotation) {
                    $row->quotationId = $quotation->id;
                    $quotation->itineraryBasic()->create($row->toArray());
                });
            }

            $hotelQuotations = HotelQuotation::where('quotationId', $quotationId)->get();

            if ($hotelQuotations) {
                $hotelQuotations->map(function ($row, $key) use ($quotation) {
                    $row->quotationId = $quotation->id;
                    HotelQuotation::create($row->toArray());
                });
            }

            $serviceQuotations = ServiceQuotation::where('quotationId', $quotationId)->get();

            if ($serviceQuotations) {
                $serviceQuotations->map(function ($row, $key) use ($quotation) {
                    $row->quotationId = $quotation->id;
                    ServiceQuotation::create($row->toArray());
                });
            }

            $quotationNotes = QuotationNote::where('quotationId', $quotationId)->get();

            if ($quotationNotes) {
                $quotationNotes->map(function ($row, $key) use ($quotation) {
                    $row->quotationId = $quotation->id;
                    QuotationNote::create($row->toArray());
                });
            }

            $quotationImages = QuotationImage::where('quotationId', $quotationId)->get();

            if ($quotationImages) {
                $quotationImages->map(function ($row, $key) use ($quotation) {
                    $row->quotationId = $quotation->id;
                    QuotationImage::create($row->toArray());
                });
            }

            // exit;
        }
    }
}
