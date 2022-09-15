<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Quotation extends Model
{
    use SoftDeletes;
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
        return $this->belongsTo(City::class, 'cityId');
    }
    public function hotelQuotations()
    {
        return $this->hasMany(HotelQuotation::class, 'quotationId');
    }
    public function serviceQuotations()
    {
        return $this->hasMany(ServiceQuotation::class, 'quotationId');
    }
    public function quotationNotes()
    {
        return $this->hasMany(QuotationNote::class, 'quotationId');
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
}
