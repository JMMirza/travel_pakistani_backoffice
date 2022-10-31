<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationStatus;
use App\Models\City;
use App\Models\HotelQuotation;
use App\Models\ServiceQuotation;
use App\Models\QuotationNote;
use App\Models\QuotationStatusLog;
use App\Models\QuotationImage;
use App\Models\QuotationInvoice;
use App\Models\QuotationResponse;
use App\Models\QuotationChat;
use App\Models\ItineraryQuotation;
use App\Models\Branch;
use App\Models\User;
use App\Models\SiteUser;
use App\Models\Operator;
use App\Models\Inquiry;
use App\Models\LandmarkItem;
use App\Models\LandmarkCategory;
use App\Models\QuotationOrder;
use App\Traits\CommonTrait;
use App\Models\ItineraryTemplate;
use App\Models\ItineraryTemplateDetail;
use App\Models\CustomTemplate;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
//use PDF;
use DOMPDF;
use Illuminate\Support\Facades\View;
use Storage;
// use App\Mail\QuotationGenerated;
// use App\Mail\InvoiceGenerated;
use Illuminate\Support\Facades\Mail;
use Cloudder;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Exception;

class QuotationController extends Controller
{
    // use CommonTrait;

    public function index(Request $request)
    {
        $user = Auth::user();
        $status = QuotationStatus::all();

        if ($request->ajax()) {

            if ($user->userable_type == "Admin") {
                $quotations = Quotation::whereNull("quotationParent")
                    ->orderBy("id", "desc")
                    ->with('user', 'city', 'inquiry', 'statusDetail', 'processedByUser');
            } else {
                $quotations = Quotation::whereNull("quotationParent")
                    ->where("userId", $user->id)
                    ->orWhere("processedBy", $user->id)
                    ->orderBy("id", "desc")
                    ->with('user', 'city', 'inquiry', 'statusDetail', 'processedByUser');
            }

            if ($request->status && $request->status > 0) {
                $quotations = $quotations->where('status', $request->status);
            }

            if ($request->has('search_text') && $request->search_text !== '') {

                $search_text = $request->search_text;
                $quotations = $quotations->where(function ($query) use ($search_text) {
                    $query->where('clientName', 'like', '%' . $search_text . '%')->orWhere('clientEmail', 'like', '%' . $search_text . '%');
                });
            }

            return Datatables::of($quotations)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d');
                })
                ->addColumn('processedByName', function ($row) {

                    if ($row->processedByUser) {
                        return $row->processedByUser->name;
                    }

                    return 'N/A';
                })
                ->addColumn('status', function ($row) use ($status) {

                    $statuses = '';

                    $statuses .= '<select class="form-select form-select-sm quotation-status" data-quotation-id="' . $row->id . '">';

                    $selected = '';
                    foreach ($status as $key => $s) {
                        $selected = $row->status == $s->id || $row->status == $s->label ? 'selected' : '';
                        $statuses .= '<option ' . $selected . ' value="' . $s->id . '">' . $s->label . '</option>';
                    }

                    return $statuses .= '</select>';

                    // if ($row->statusDetail) {
                    //     return '<span class="badge ' . $row->statusDetail->cssClass . ' text-uppercase">' . $row->statusDetail->label . '</span>';
                    // } else {
                    // }
                })
                ->addColumn('action', function ($row) {

                    return '
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu" style="">
                                <a class="dropdown-item" href="' . route('quotation-edit', $row->id) . '?tab=1">Edit</a>
                                <a class="dropdown-item" href="#">Invoice</a>
                                <a class="dropdown-item" href="#">Chat</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#">Delete</a>
                            </div>
                        </div>

                    ';
                })

                ->rawColumns(['action', 'status'])
                ->make(true);
        }


        return view('quotations.quotations_list', ['status' => $status]);
    }

    public function create(Request $request)
    {
        $cities = City::all();
        $users = User::all();
        return view('quotations.quotation_form', [
            'cities' => $cities,
            'users' => $users,
            'quotation_id' => 0,
            'tab' => $request->has('tab') ? $request->tab : 1,
            'inquire_id' => $request->has('inquire_id') ? $request->inquire_id : 0
        ]);
    }

    public function saveBasicInformation(Request $request)
    {
        $user = Auth::user();

        $quotationData = $request->all();

        $inquiryId = $request->input('inquiryId', 0);
        $quotationId = (int) $request->input('quotationId', 0);
        $isNew = $request->input('isNew', 1);
        $staffRemarks = $request->input('staffRemarks', '');

        if ($inquiryId > 0) {
            $quotationData['inquiryId'] = $inquiryId;
        }

        $quotationData['quotationParent'] = null;
        $quotationData['staffRemarks'] = is_null($staffRemarks) ? '' : $staffRemarks;
        $quotationData['requiredServices'] = json_encode([]);
        $quotationData['extraMarkup'] = 0;
        $quotationData['markupTypeQuotation'] = '';

        $quotationData['userId'] = $user->id;
        $quotationData['citiesToVisit'] = json_encode($request->citiesToVisit);

        $tourDates = explode("to", $request->tourDates);

        if (count($tourDates) == 2) {
            $quotationData['tourFrom'] = trim($tourDates[0]);
            $quotationData['tourEnd'] = trim($tourDates[1]);
        } else {
            $quotationData['tourFrom'] = trim($tourDates[0]);
            // $quotationData['tourEnd'] = null;
        }

        if ($isNew == 1 && $quotationId > 0) {

            $quotationPrevious = Quotation::find($quotationId);
            $totalVersions = Quotation::where("quotationParent", $quotationId)->count();
            $quotationData['version'] = $totalVersions + 1;
            $quotationData['quotationParent'] = $quotationPrevious->quotationParent > 0 ? $quotationPrevious->quotationParent : $quotationId;
            $quotation = Quotation::create($quotationData);

            if ($quotation) {
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
            }
        } else if ($isNew == 0 && $quotationId > 0) {
            $quotation = Quotation::find($quotationId);
            $quotation->update($quotationData);
        } else {
            $quotation = Quotation::create($quotationData);
        }

        if ($quotation && empty($quotation->liveQuotation)) {
            $hashId = $quotation->id * 7585795975989869898667454765786;
            $quotation->liveQuotation = $hashId;
            $quotation->save();
        }

        return redirect()->route('quotation-edit', $quotation->id)->with('success', 'Quotation updated successflly');
    }

    public function edit(Request $request, $id)
    {
        $cities = City::all();
        $users = User::all();

        $quotation = Quotation::where(['id' => $id])->with([
            "user",
            "hotelQuotations",
            "activityQuotations",
            "mealQuotations",
            "transportQuotations",
            "otherServicesQuotations",
            "optionalServicesQuotations",
            "quotationNotes",
            "itineraryBasic",
            "quotationImages",
            "cancellationPolicy",
            "bookingNotes",
            "paymentTerms",
            "freeText",
        ])->first();

        $totalCost = 0;
        $finalAmount = 0;
        $discountedAmount = 0;

        if ($quotation->markupType == 'Total') {
            $hotelCostSum = $quotation->hotelQuotations->sum('hotelCost');
            $serviceCostSum = $quotation->serviceQuotations->sum('serviceCost');
            $discountedAmount = $totalCost = $finalAmount = $hotelCostSum + $serviceCostSum;

            if ($quotation->markupTypeQuotation == 'Percentage') {
                $extraMarkupValue = (($request->extraMarkup / 100) * $finalAmount);
                $discountedAmount = $finalAmount = $finalAmount - $extraMarkupValue;
            } else {
                $discountedAmount = $finalAmount = $finalAmount - $quotation->extraMarkup;
            }
        }

        if ($quotation->markupType == 'Individual') {
            $hotelCostSum = $quotation->hotelQuotations->sum('hotelSales');
            $serviceCostSum = $quotation->serviceQuotations->sum('serviceSales');
            $discountedAmount = $totalCost = $finalAmount = $hotelCostSum + $serviceCostSum;
        }


        if ($quotation->discountType == 'Percentage') {
            $discountValue = (($request->discountValue / 100) * $finalAmount);
            $discountedAmount = $finalAmount - $discountValue;
        } else {
            $discountedAmount = $finalAmount - $quotation->discountValue;
        }

        return view('quotations.quotation_form', [
            'cities' => $cities,
            'users' => $users,
            'quotation' => $quotation,
            'quotation_id' => $quotation->id,
            'services' => json_decode($quotation->requiredServices),
            'userNotes' => json_decode($quotation->userNotes),
            'tab' => isset($request->tab) ? $request->tab : 1,
            'totalCost' => $totalCost,
            'finalAmount' => $finalAmount,
            'discountedAmount' => $discountedAmount,
            'totalPersons' => ($quotation->adults + $quotation->children),

        ]);
    }

    public function itineraryListModal(Request $request)
    {
        $itinerary_templates = ItineraryTemplate::with(["category", "templateDetails"])->get();

        return view('quotations.itinerary_list_modal', [
            'itinerary_templates' => $itinerary_templates
        ]);
    }

    public function addQuotationItineraryModal(Request $request)
    {
        $itineraryQuotation = null;

        $quotationId = $request->input('quotationId', 0);

        if ($request->id) {
            $itineraryQuotation = ItineraryQuotation::find($request->id);
        }

        $landmarkCategories = LandmarkCategory::whereNull('parentId')->where('isActive', 1)->get();
        $cities = City::all();

        $days = [

            ['id' => '1', 'label' => 'Day 01'],
            ['id' => '2', 'label' => 'Day 02'],
            ['id' => '3', 'label' => 'Day 03'],
            ['id' => '4', 'label' => 'Day 04'],
            ['id' => '5', 'label' => 'Day 05'],
            ['id' => '6', 'label' => 'Day 06'],
            ['id' => '7', 'label' => 'Day 07'],
            ['id' => '7', 'label' => 'Day 07'],
            ['id' => '9', 'label' => 'Day 09'],
            ['id' => '10', 'label' => 'Day 10'],
        ];

        return view('quotations.add_quotation_itinerary_modal', [
            'itineraryQuotation' => $itineraryQuotation,
            'days' => $days,
            'cities' => $cities,
            'landmarkCategories' => $landmarkCategories,
            'quotationId' => $quotationId
        ]);
    }

    public function addQuotationItinerary(Request $request)
    {
        $quotationId = $request->input('quotation_id', 0);

        $itinerary_templates = ItineraryTemplateDetail::where('id', $request->itinerary_detail_id)->with('template')->first();
        $itinerary = new ItineraryQuotation();
        $itinerary->day = 1;
        $itinerary->title = $itinerary_templates->template->templateTitle;
        $itinerary->details = $itinerary_templates->description;
        $itinerary->photo = $itinerary_templates->photo;

        $quotation = Quotation::find($request->quotation_id);
        $quotation->itineraryBasic()->save($itinerary);

        $rowsHtml = '';
        $quotation = Quotation::find($quotationId);

        foreach ($quotation->itineraryBasic as $key => $itinerary) {
            $rowsHtml .= view('quotations.itinerary_card', ['itinerary' => $itinerary])->render();
        }

        return $rowsHtml;
    }

    public function removeQuotationItinerary($id)
    {
        return ItineraryQuotation::where('id', $id)->delete();
    }

    public function addQuotationHotelModal(Request $request)
    {
        $itineraryQuotation = null;
        $hotel = null;

        $quotationId = $request->input('quotationId', 0);

        if ($quotationId > 0) {
            $itineraryQuotation = Quotation::find($quotationId);
        }

        $hotelId = $request->input('hotelId', 0);

        if ($hotelId > 0) {
            $hotel = HotelQuotation::find($hotelId);
        }

        return view('quotations.add_quotation_hotel_modal', [
            'itineraryQuotation' => $itineraryQuotation,
            'hotel' => $hotel,
        ]);
    }

    public function addQuotationMealModal(Request $request)
    {
        $itineraryQuotation = null;
        $service = null;

        $quotationId = $request->input('quotationId', 0);
        $serviceId = $request->input('serviceId', 0);

        if ($quotationId > 0) {
            $itineraryQuotation = Quotation::find($quotationId);
        }

        if ($serviceId > 0) {
            $service = ServiceQuotation::find($serviceId);
        }

        // dd($service->toArray());

        return view('quotations.add_quotation_meal_modal', [
            'itineraryQuotation' => $itineraryQuotation,
            'service' => $service,
        ]);
    }

    public function addQuotationTransportModal(Request $request)
    {
        $itineraryQuotation = null;
        $service = null;

        $quotationId = $request->input('quotationId', 0);
        $serviceId = $request->input('serviceId', 0);

        if ($quotationId > 0) {
            $itineraryQuotation = Quotation::find($quotationId);
        }

        if ($serviceId > 0) {
            $service = ServiceQuotation::find($serviceId);
        }

        return view('quotations.add_quotation_transport_modal', [
            'itineraryQuotation' => $itineraryQuotation,
            'service' => $service,
        ]);
    }

    public function addQuotationActivityModal(Request $request)
    {
        $itineraryQuotation = null;
        $service = null;

        $quotationId = $request->input('quotationId', 0);
        $serviceId = $request->input('serviceId', 0);

        if ($quotationId > 0) {
            $itineraryQuotation = Quotation::find($quotationId);
        }

        if ($serviceId > 0) {
            $service = ServiceQuotation::find($serviceId);
        }

        return view('quotations.add_quotation_activity_modal', [
            'itineraryQuotation' => $itineraryQuotation,
            'service' => $service,
        ]);
    }

    public function addQuotationPoliciesModal(Request $request)
    {
        $itineraryQuotation = null;
        $note = null;

        $quotationId = $request->input('quotationId', 0);
        $noteType = $request->input('noteType', '');

        $noteTypes = CustomTemplate::where(['templateType' => $noteType])->get();

        if ($quotationId > 0) {
            $itineraryQuotation = Quotation::find($quotationId);
        }

        $noteId = $request->input('noteId', 0);

        if ($noteId > 0) {
            $note = QuotationNote::find($noteId);
        }

        return view('quotations.add_quotation_policies_modal', [
            'itineraryQuotation' => $itineraryQuotation,
            'note' => $note,
            'noteType' => $noteType,
            'noteTypes' => $noteTypes,
        ]);
    }

    public function saveQuotationItinerary(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'quotationId' => 'required',
            'itineraryQuotationId' => 'required',
            'itineraryDay' => 'required',
            'title' => 'required',
            'itineraryDescription' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $quotationId = $request->input('quotationId', 0);
        $itineraryQuotationId = $request->input('itineraryQuotationId', 0);

        if ($itineraryQuotationId > 0) {
            $quotationItinerary = ItineraryQuotation::find($itineraryQuotationId);

            $quotationItinerary->day = $request->itineraryDay;
            $quotationItinerary->title = $request->title;
            $quotationItinerary->details = $request->itineraryDescription;
            $quotationItinerary->save();
        } else {

            $quotation = Quotation::find($quotationId);

            $data = [
                'day' => $request->itineraryDay,
                'title' => $request->title,
                'details' => $request->itineraryDescription,
            ];

            $quotation->itineraryBasic()->create($data);
        }

        $rowsHtml = '';

        $quotation = Quotation::find($quotationId);

        foreach ($quotation->itineraryBasic as $key => $itinerary) {
            $rowsHtml .= view('quotations.itinerary_card', ['itinerary' => $itinerary])->render();
        }

        return $rowsHtml;
    }

    public function saveQuotationHotel(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'quotationId' => 'required',
            'hotelName' => 'required',
            'hotelDuration' => 'required',
            'nights' => 'required',
            'unitCost' => 'required',
            'totalUnits' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $quotationId = $request->input('quotationId', 0);
        $hotelId = $request->input('hotelId', 0);

        if ($hotelId > 0) {
            $hotel = HotelQuotation::find($hotelId);
        } else {
            $hotel = new HotelQuotation();
        }

        $hotel->quotationId = $quotationId;
        $hotel->hotelName = $request->hotelName;

        $hotelDuration = explode("to", $request->hotelDuration);
        $hotel->checkIn = trim($hotelDuration[0]);
        $hotel->checkout = trim($hotelDuration[1]);

        $hotel->instructions = $request->instructions;
        $hotel->nights = $request->nights;
        $hotel->unitCost = $request->unitCost;
        $hotel->totalUnits = $request->totalUnits;

        $totalCost = $request->unitCost * $request->totalUnits * $request->nights;
        $hotel->hotelCost = $totalCost;

        $totalSales = 0;
        $markupAmount = 0;

        if ($request->has("markupType") && $request->has("markupValue")) {

            $hotel->markupValue = $request->markupValue;
            $hotel->markupType = $request->markupType;

            if ($request->markupType == "Percentage" && $request->markupValue > 0) {
                $markupAmount = (($request->markupValue / 100) * $totalCost);
            } else if ($request->markupType == "Flat" && $request->markupValue > 0) {
                $markupAmount = $request->markupValue;
            }
        }

        $hotel->hotelMarkupAmount = $markupAmount;
        $totalSales = $markupAmount + $totalCost;
        $hotel->hotelSales = $totalSales;
        $hotel->versionNo = $request->versionNo;

        $hotel->save();

        $hotels = HotelQuotation::where(['quotationId' => $quotationId])->get();
        return $this->servicesRowsRender($hotels, $request->serviceType);
        // return response()->json(['hotels' => $hotels]);
    }

    public function saveQuotationMeal(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'quotationId' => 'required',
            'serviceId' => 'required',
            'serviceDateType' => 'required',
            'description' => 'required',
            'unitCost' => 'required',
            'totalUnits' => 'required',
            // 'instructions' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $quotationId = $request->input('quotationId', 0);
        $serviceId = $request->input('serviceId', 0);
        $calcDaysMeal = $request->input('calcDaysMeal', 0);
        $calcDaysMeal = $calcDaysMeal == 'on' ? 1 : $calcDaysMeal;

        $totalCost = 0;
        $totalSales = 0;

        $isNew = $request->isNew;

        if ($serviceId > 0) {
            $service = ServiceQuotation::find($serviceId);
        } else {
            $service = new ServiceQuotation();
        }

        $service->quotationId = $quotationId;
        $service->description = $request->description;
        $service->serviceType = $request->serviceType;
        $service->serviceDateType = $request->serviceDateType;

        $totalDays = 1;
        $serviceStartDate = NULL;
        $serviceEndDate = NULL;

        if ($request->serviceDateType == "nodate") {
            $totalDays = 0;
            $service->serviceEndDate = NULL;
        } elseif ($request->serviceDateType == "tour" || $request->serviceDateType == "range") {

            $service->calculateByDays = $calcDaysMeal;

            $serviceDates = explode("to", $request->serviceDuration);
            $serviceStartDate = trim($serviceDates[0]);
            $serviceEndDate = trim($serviceDates[1]);
            $totalDays = Carbon::parse($serviceStartDate)->diffInDays($serviceEndDate);
        } else {
            $serviceStartDate = $request->serviceDate;
            $serviceEndDate = $request->serviceDate;
        }

        $service->totalDays = $totalDays;
        $service->serviceDate = $serviceStartDate;
        $service->serviceEndDate = $serviceEndDate;
        $service->instructions = $request->instructions;
        $service->unitCost = $request->unitCost;

        $service->totalUnits = $request->totalUnits;
        $totalCost = $request->unitCost * $request->totalUnits;

        if ($calcDaysMeal && $totalDays && $request->serviceType != "nodate") {
            $totalCost = $totalCost * $totalDays;
        }

        $service->serviceCost = $totalCost;

        $markupAmount = 0;

        if ($request->has("markupType") && $request->has("markupValue")) {

            $service->markupValue = $request->markupValue;
            $service->markupType = $request->markupType;

            if ($request->markupType == "Percentage" && $request->markupValue > 0) {
                $markupAmount = (($request->markupValue / 100) * $totalCost);
            } else if ($request->markupType == "Flat" && $request->markupValue > 0) {
                $markupAmount = $request->markupValue;
            }
        }

        $service->serviceMarkupAmount = $markupAmount;
        $totalSales = $markupAmount + $totalCost;
        $service->serviceSales = $totalSales;
        $service->versionNo = $request->versionNo;
        $service->save();

        $services = ServiceQuotation::where(['quotationId' => $quotationId, 'serviceType' => $request->serviceType,])->get();
        return $this->servicesRowsRender($services, $request->serviceType);
        // return response()->json(['services' => $services]);
    }

    public function saveQuotationTransport(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'quotationId' => 'required',
            'serviceId' => 'required',
            'serviceDateType' => 'required',
            'description' => 'required',
            'unitCost' => 'required',
            'totalUnits' => 'required',
            'instructions' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $quotationId = $request->input('quotationId', 0);
        $serviceId = $request->input('serviceId', 0);
        $calcDaysMeal = $request->input('calcDaysMeal', 0);
        $calcDaysMeal = $calcDaysMeal == 'on' ? 1 : $calcDaysMeal;

        $totalCost = 0;
        $totalSales = 0;

        $isNew = $request->isNew;

        if ($serviceId > 0) {
            $service = ServiceQuotation::find($serviceId);
        } else {
            $service = new ServiceQuotation();
        }

        $service->quotationId = $quotationId;
        $service->description = $request->description;
        $service->serviceType = $request->serviceType;
        $service->serviceDateType = $request->serviceDateType;

        $totalDays = 1;
        $serviceStartDate = NULL;
        $serviceEndDate = NULL;

        if ($request->serviceDateType == "nodate") {
            $totalDays = 0;
            $service->serviceEndDate = NULL;
        } elseif ($request->serviceDateType == "tour" || $request->serviceDateType == "range") {

            $service->calculateByDays = $calcDaysMeal;

            $serviceDates = explode("to", $request->serviceDuration);
            $serviceStartDate = trim($serviceDates[0]);
            $serviceEndDate = trim($serviceDates[1]);
            $totalDays = Carbon::parse($serviceStartDate)->diffInDays($serviceEndDate);
        } else {
            $serviceStartDate = $request->serviceDate;
            $serviceEndDate = $request->serviceDate;
        }

        $service->totalDays = $totalDays;
        $service->serviceDate = $serviceStartDate;
        $service->serviceEndDate = $serviceEndDate;
        $service->instructions = $request->instructions;
        $service->unitCost = $request->unitCost;

        $service->totalUnits = $request->totalUnits;
        $totalCost = $request->unitCost * $request->totalUnits;

        if ($calcDaysMeal && $totalDays && $request->serviceType != "nodate") {
            $totalCost = $totalCost * $totalDays;
        }

        $service->serviceCost = $totalCost;

        $markupAmount = 0;

        if ($request->has("markupType") && $request->has("markupValue")) {

            $service->markupValue = $request->markupValue;
            $service->markupType = $request->markupType;

            if ($request->markupType == "Percentage" && $request->markupValue > 0) {
                $markupAmount = (($request->markupValue / 100) * $totalCost);
            } else if ($request->markupType == "Flat" && $request->markupValue > 0) {
                $markupAmount = $request->markupValue;
            }
        }

        $service->serviceMarkupAmount = $markupAmount;
        $totalSales = $markupAmount + $totalCost;
        $service->serviceSales = $totalSales;
        $service->versionNo = $request->versionNo;
        $service->save();

        $services = ServiceQuotation::where(['quotationId' => $quotationId, 'serviceType' => $request->serviceType,])->get();
        return $this->servicesRowsRender($services, $request->serviceType);
        // return response()->json(['services' => $services]);
    }

    public function saveQuotationActivity(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'quotationId' => 'required',
            'serviceId' => 'required',
            'serviceDateType' => 'required',
            'description' => 'required',
            'unitCost' => 'required',
            'totalUnits' => 'required',
            // 'instructions' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $quotationId = $request->input('quotationId', 0);
        $serviceId = $request->input('serviceId', 0);
        $calcDaysMeal = $request->input('calcDaysMeal', 0);
        $calcDaysMeal = $calcDaysMeal == 'on' ? 1 : $calcDaysMeal;

        $totalCost = 0;
        $totalSales = 0;

        $isNew = $request->isNew;

        if ($serviceId > 0) {
            $service = ServiceQuotation::find($serviceId);
        } else {
            $service = new ServiceQuotation();
        }

        $service->quotationId = $quotationId;
        $service->description = $request->description;
        $service->serviceType = $request->serviceType;
        $service->serviceDateType = $request->serviceDateType;

        $totalDays = 1;
        $serviceStartDate = NULL;
        $serviceEndDate = NULL;

        if ($request->serviceDateType == "nodate") {
            $totalDays = 0;
            $service->serviceEndDate = NULL;
        } elseif ($request->serviceDateType == "tour" || $request->serviceDateType == "range") {

            $service->calculateByDays = $calcDaysMeal;

            $serviceDates = explode("to", $request->serviceDuration);
            $serviceStartDate = trim($serviceDates[0]);
            $serviceEndDate = trim($serviceDates[1]);
            $totalDays = Carbon::parse($serviceStartDate)->diffInDays($serviceEndDate);
        } else {
            $serviceStartDate = $request->serviceDate;
            $serviceEndDate = $request->serviceDate;
        }

        $service->totalDays = $totalDays;
        $service->serviceDate = $serviceStartDate;
        $service->serviceEndDate = $serviceEndDate;
        $service->instructions = $request->instructions;
        $service->unitCost = $request->unitCost;

        $service->totalUnits = $request->totalUnits;
        $totalCost = $request->unitCost * $request->totalUnits;

        if (
            $calcDaysMeal && $totalDays && $request->serviceType != "nodate"
        ) {
            $totalCost = $totalCost * $totalDays;
        }

        $service->serviceCost = $totalCost;

        $markupAmount = 0;

        if ($request->has("markupType") && $request->has("markupValue")) {

            $service->markupValue = $request->markupValue;
            $service->markupType = $request->markupType;

            if ($request->markupType == "Percentage" && $request->markupValue > 0) {
                $markupAmount = (($request->markupValue / 100) * $totalCost);
            } else if ($request->markupType == "Flat" && $request->markupValue > 0) {
                $markupAmount = $request->markupValue;
            }
        }

        $service->serviceMarkupAmount = $markupAmount;
        $totalSales = $markupAmount + $totalCost;
        $service->serviceSales = $totalSales;
        $service->versionNo = $request->versionNo;
        $service->save();

        $services = ServiceQuotation::where(['quotationId' => $quotationId, 'serviceType' => $request->serviceType,])->get();
        return $this->servicesRowsRender($services, $request->serviceType);
        // return response()->json(['services' => $services]);
    }

    public function saveQuotationPolicy(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'quotationId' => 'required',
            'title' => 'required',
            'description' => 'required',
            'noteType' => 'required',
            'versionNo' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $quotationId = $request->input('quotationId', 0);
        $noteId = $request->input('noteId', 0);

        if ($noteId > 0) {
            $quotationNote = QuotationNote::find($noteId);
        } else {
            $quotationNote = new QuotationNote();
        }

        $quotationNote->quotationId = $quotationId;
        $quotationNote->title = $request->title;
        $quotationNote->description = $request->description;
        $quotationNote->type = $request->noteType;
        $quotationNote->versionNo = $request->versionNo;
        $quotationNote->save();

        $quotationNotes = QuotationNote::where(['quotationId' => $quotationId, 'type' => $request->noteType])->get();
        return $this->noteRowsRender($quotationNotes, $request->serviceType);
    }

    public function saveQuotation(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'quotationId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $quotationId = $request->input('quotationId', 0);
        $emailToCustomer = $request->input('email', 0);
        $isApproved = $request->has('isApproved') ? 1 : 0;
        $isExpired = $request->has('isExpired') ? 1 : 0;

        $quotation = Quotation::find($quotationId);

        // $quotation->title = $request->personsCount;
        // if not individual

        if ($quotation->markupType == 'Total') {
            $quotation->markupTypeQuotation = $request->markupTypeQuotation;
            $quotation->extraMarkup = $request->extraMarkup;
        }

        $quotation->discountType = $request->discountType;
        $quotation->discountValue = $request->discountValue;

        if ($emailToCustomer == 1) {
            $quotation->showPrice = $request->has('showPrice') ? 1 : 0;
            $quotation->showCost = $request->has('showCost') ? 1 : 0;
            $quotation->email = $emailToCustomer;
        }

        $quotation->isTemplate = $request->has('isTemplate') ? 1 : 0;

        if ($isExpired == 1) {
            $quotation->quotationStatus = 3;
            $quotation->expiryReason = $request->expiryReason;
        }

        if ($isApproved > 0 && $quotation->status != 3) {
            $quotationParentId = $quotation->id;

            if ($quotation->quotationParent != NULL) {
                $quotationParentId = $quotation->quotationParent;
            }

            \DB::table("quotations")->where("quotationParent", $quotationParentId)->update(["approvedVersionId" => $quotation->id]);
            \DB::table("quotations")->where("id", $quotationParentId)->update(["approvedVersionId" => $quotation->id, "status" => 8]);
            $quotation->status = 8;
        } else {
            $quotationParentId = $quotation->id;
            if ($quotation->quotationParent != NULL) {
                $quotationParentId = $quotation->quotationParent;
            }
            \DB::table("quotations")->where("quotationParent", $quotationParentId)->update(["approvedVersionId" => NULL]);
            \DB::table("quotations")->where("id", $quotationParentId)->update(["approvedVersionId" => NULL]);
        }

        $quotation->save();

        return redirect()->back()->with('success', 'Quotation updated successflly');
    }

    public function deleteQuotationHotel($id)
    {
        HotelQuotation::find($id)->delete();
        return response()->json(["message" => "Hotel deleted successfully!"], 200);
    }

    public function deleteQuotationService($id)
    {
        ServiceQuotation::find($id)->delete();
        return response()->json(["message" => "Service deleted successfully!"], 200);
    }

    public function deleteQuotationNote($id)
    {
        QuotationNote::find($id)->delete();
        return response()->json(["message" => "Note deleted successfully!"], 200);
    }

    public function saveQuotationServiceTypes(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'quotationId' => 'required',
            // 'hotelService' => 'required',
            // 'mealService' => 'required',
            // 'transportService' => 'required',
            // 'activitiesService' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $quotationId = $request->input('quotationId', 0);

        $serviceTypes = [

            'Hotel' => $request->has('hotelService') ? true : false,
            'Meal' => $request->has('mealService') ? true : false,
            'Transport' => $request->has('transportService') ? true : false,
            'Activities' => $request->has('activitiesService') ? true : false,
        ];

        // print_r($request->serviceTypes['Hotel']);exit;
        // print_r($serviceTypes);exit;

        $quotation = Quotation::find($quotationId);
        $quotation->requiredServices = json_encode($serviceTypes);
        $quotation->save();

        return response()->json(['quotation ' => $quotation]);
    }

    public function saveQuotationNotesTypes(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'quotationId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $quotationId = $request->input('quotationId', 0);

        $serviceTypes = [
            'cancellationPolicy' => $request->has('cancellationPolicy') ? true : false,
            'bookingNotes' => $request->has('bookingNotes') ? true : false,
            'paymentTerms' => $request->has('paymentTerms') ? true : false,
            'freeText' => $request->has('freeText') ? true : false,
        ];

        $quotation = Quotation::find($quotationId);
        $quotation->userNotes = json_encode($serviceTypes);
        $quotation->save();

        return response()->json(['quotation ' => $quotation]);
    }

    public function saveQuotationImage(Request $request)
    {
        $image = $request->file('file');
        $quotationId = $request->input('quotationId', 0);

        if ($request->hasFile("file")) {

            $imgOptions = ['folder' => 'TP-DestinationContent', 'format' => 'webp'];
            $cloudder = Cloudder::upload($request->file("file")->getRealPath(), null, $imgOptions);

            $imgName = '';

            if ($cloudder) {
                $result = $cloudder->getResult();
                $imgName = $result['public_id'];
            }

            $image = new QuotationImage();
            $image->quotationId = $quotationId;
            $image->title = $request->file("file")->getClientOriginalName();
            $image->image = $imgName;
            $image->version = 0;
            $image->save();

            return response()->json($image);
        }

        return response()->json([]);
    }

    public function listQuotationImage(Request $request)
    {
        if ($request->ajax()) {

            $quotationId = $request->input('quotationId', 0);

            $quotationImages = QuotationImage::where('quotationId', $quotationId)->get();

            return Datatables::of($quotationImages)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d');
                })
                ->addColumn('image', function ($row) {

                    $imageUrl = Cloudder::show($row->image);
                    return '<a class="link-success" href="' . $imageUrl . '" target="_blank">View Image</a>';
                })
                ->addColumn('action', function ($row) {

                    return '
                        <a data-table="quotations-images-table" href="' . route('remove-quotation-image', $row->id) . '" class="btn btn-danger delete-record btn-icon btn-sm waves-effect waves-light">
                            <i class="ri-delete-bin-5-line"></i>
                        </a>
                    ';
                })
                ->rawColumns(['action', 'image'])
                ->make(true);
        }
    }

    private function servicesRowsRender($services, $type)
    {
        // echo ($type);
        // dd($services->toArray());
        $rowsHtml = '';

        foreach ($services as $key => $service) {

            if ($type == 'Hotel') {
                $rowsHtml .= view('quotations.hotel_quotation_table_row', ['hotel' => $service])->render();
            } else if ($type == 'Meal') {
                $rowsHtml .= view('quotations.meal_quotation_table_row', ['meal' => $service])->render();
            } else if ($type == 'Transport') {
                $rowsHtml .= view('quotations.transport_quotation_table_row', ['transport' => $service])->render();
            } else {
                $rowsHtml .= view('quotations.activity_quotation_table_row', ['activity' => $service])->render();
            }
        }

        return $rowsHtml;
    }

    private function noteRowsRender($notes, $type = '')
    {
        $rowsHtml = '';

        foreach ($notes as $key => $noteRow) {
            $rowsHtml .= view('quotations.notes_quotation_table_row', ['noteRow' => $noteRow])->render();
        }

        return $rowsHtml;
    }

    public function deleteQuotationImage($id)
    {
        QuotationImage::find($id)->delete();
        return response()->json(["message" => "Photo Deleted Successfully!"], 200);
    }

    public function changeQuotationStatus(Request $request)
    {
        $user = Auth::user();

        $validator = \Validator::make($request->all(), [
            'quotationId' => 'required',
            'statusId' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $quotation = Quotation::findOrFail($request->quotationId);
        $quotationStatus = QuotationStatus::findOrFail($request->statusId);

        QuotationStatusLog::create([
            'quotationId' => $request->quotationId,
            'statusId' => $request->statusId,
            'userId' => $user->id,
        ]);

        $quotation->status = $quotationStatus->label;
        $quotation->save();

        return response()->json(['data' => null], 200);
    }



    //Legcy Code below

    public function quotationTemplates(Quotation $quotation)
    {
        $this->authorize('viewAny', $quotation);
        $user = Auth::user();
        if ($user->userable_type == "Admin")
            $quotations = Quotation::where("isTemplate", "1")->orderBy("id", "desc")->with('user', 'city', 'inquiry')->get();
        else
            $quotations = Quotation::where("isTemplate", "1")->where("userId", $user->id)->orderBy("id", "desc")->with('user', 'city', 'inquiry')->get();
        return response()->json(['data' => $quotations], 200);
    }

    public function getAllResponses()
    {
        \DB::enableQueryLog();
        $quotationsResponses = QuotationResponse::orderBy("id", "desc")->with('quotation')->get();
        $queryLog = \DB::getQueryLog();
        return response()->json([
            'data' => $quotationsResponses,
            "log" => $queryLog
        ], 200);
    }

    // public function getQuotationChat($id)
    // {

    //     // $id = base64_decode($id);
    //     $id = $id;

    //     \DB::enableQueryLog();
    //     $quotationsChat = QuotationChat::where('quotationId', $id)->orderBy("id", "desc")->get();
    //     return response()->json([
    //         'data' => $quotationsChat,
    //     ], 200);
    // }

    // public function submitQuotationChat(Request $request)
    // {

    //     $result = "";

    //     $quotationId = (int)$request->quotationId;
    //     // $quotationId = base64_decode($quotationId);
    //     // $quotationId = $quotationId / 7585795975989869898667454765786;

    //     $quotation = Quotation::find($quotationId);
    //     try {

    //         $response = new QuotationChat();

    //         $response->quotationId = $quotationId;
    //         $response->message = $request->message;
    //         $response->type = $request->type;

    //         $response->save();

    //         $username = "923139367626"; ///Your Username
    //         $password = "Wonderweal-99"; ///Your Password
    //         $sender = "26144";

    //         if ($response->type == 'owner') {

    //             $mobile = $quotation->clientContact;

    //             $message = "You recieved a new quotation reply. Please visit - https://travelpakistani.com/quotation/chat/" . $response->quotationId . " to check.";

    //             $post = "sender=" . urlencode($sender) . "&mobile=" . urlencode($mobile) . "&message=" . urlencode($message) . "";

    //             $url = "https://sendpk.com/api/sms.php?username=" . $username . "&password=" . $password . "";
    //             $ch = curl_init();

    //             $timeout = 30; // set to zero for no timeout
    //             curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
    //             curl_setopt($ch, CURLOPT_URL, $url);
    //             curl_setopt($ch, CURLOPT_POST, 1);
    //             curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //             curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    //             $result = curl_exec($ch);
    //         }

    //         return response()->json(["message" => "Submittied Successfully", "status" => 200, "sms_response" => $result], 200);
    //     } catch (Exception $e) {

    //         return response()->json($e - getMessage(), 500);
    //     }
    // }
    public function updateQuotationResponse(Request $request)
    {

        $response = QuotationResponse::find($request->responseId);
        $response->status = $request->status ? $request->status : "viewed";
        $response->save();
        return response()->json(["message" => "Response updated successfully"], 200);
    }

    public function updateEmailStatus($status, $quoteId)
    {
        $updateEmailStatus = Quotation::where('id', $quoteId)->first();
        $updateEmailStatus->email = $status;
        if ($updateEmailStatus->save()) {
            return response()->json('success');
        }
    }

    // public function getQuotationResponseDetails($id)
    // {
    //     \DB::enableQueryLog();
    //     $quotationsResponses = QuotationResponse::where('id', $id)->orderBy("id", "desc")->with('quotation')->first();

    //     $response = QuotationResponse::find($id);
    //     $newStatus = "";
    //     if ($response->status == "new") {
    //         $response->status = "viewed";
    //         $response->save();
    //         $newStatus = "viewed";
    //     }
    //     return response()->json([
    //         'data' => $quotationsResponses,
    //         'newStatus' => $newStatus
    //     ], 200);
    // }

    public function updateItineraryImage(Request $request)
    {
        $itineraryId = $request->route("id");
        if ($request->hasFile("itineraryImage")) {
            $itinerary = ItineraryQuotation::find($itineraryId);
            $imgOptions = ['folder' => 'itineraryPictures', 'format' => 'webp'];
            $cloudder = Cloudder::upload($request->file("itineraryImage")->getRealPath(), null, $imgOptions);
            $result = $cloudder->getResult();
            if (isset($result['public_id'])) {
                $itineraryImage = $result['public_id'];
            }
            $itinerary->photo = $itineraryImage;
            $itinerary->save();
            $uploadedImage = $result["url"];
            return response()->json(["message" => "Image uploaded successfully!", "uploadedImagePath" => $uploadedImage], 200);
        }
        return response()->json(["message" => "Upload error"], 422);
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $id = $request->route("id");
        $version = $request->route('version');
        //return response()->json(["id"=>$id,"version"=>$version],422);
        if ($version > 1) {
            $quotation = Quotation::where("quotationParent", $id)->where('versionNo', $version)->first();
            if (!$quotation) {
                return response()->json(["message" => "Invalid version or quotation id"], 404);
            }
            $id = $quotation->id;
        }
        /*$quotation = Quotation::selectRaw('max(id) as currentQuotation')->where("quotationParent",$id)->first();
        if($quotation->currentQuotation>0)
        $id = $quotation->currentQuotation;*/

        $quotation = Quotation::with(["user", "hotelQuotations", "serviceQuotations", "quotationNotes", "itineraryBasic", "quotationImages"])->find($id);
        $this->authorize('view', $quotation);
        $userPicture = "";
        if ($quotation->user->profilePic) {
            $userPicture = Cloudder::show($quotation->user->profilePic);
        }
        $quotation->user->userPicture = $userPicture;

        $hotels = $quotation->hotelQuotations;
        $services = $quotation->serviceQuotations;
        $totalServices = count($services);
        $meals = $activities = $transfers = $optionalItems = $otherItems = array();
        $itineraryList = $quotation->itineraryBasic;
        $totalListItinerary = count($itineraryList);
        for ($i = 0; $i < $totalListItinerary; $i++) {
            $itineraryImageFullPath = "";
            if ($itineraryList[$i]->photo != NULL && $itineraryList[$i]->photo != "" && $itineraryList[$i]->photo != "null") {
                $itineraryImageFullPath = Cloudder::show($itineraryList[$i]->photo, [
                    'format' => 'webp'
                ]);
            }
            $itineraryList[$i]->photoFullPath = $itineraryImageFullPath;
        }
        for ($i = 0; $i < $totalServices; $i++) {
            if ($services[$i]->serviceType == "Activity") {
                $activities[] = $services[$i];
            } else if ($services[$i]->serviceType == "Meal") {
                $meals[] = $services[$i];
            } else if ($services[$i]->serviceType == "Optional") {
                $optionalItems[] = $services[$i];
            } else if ($services[$i]->serviceType == "Other") {
                $otherItems[] = $services[$i];
            } else {
                $transfers[] = $services[$i];
            }
        }
        unset($quotation->serviceQuotations);
        unset($quotation->hotelQuotations);
        return response()->json(["data" => $quotation, "optionalItems" => $optionalItems, "hotels" => $hotels, "activities" => $activities, "meals" => $meals, "transfers" => $transfers, "otherItems" => $otherItems,], 200);
    }

    public function invoice($id, $version)
    {
        if ($version == 1) {
            $quotation = Quotation::where("id", $id)->where("versionNo", $version)->first();
        } else {
            $quotation = Quotation::where("quotationParent", $id)->where("versionNo", $version)->first();
        }

        $approvedInvoices = QuotationInvoice::where("quotationId", $id)->where("status", "1")->get();

        $totalVersions = Quotation::where("quotationParent", $id)->orderBy("id", "desc")->pluck('versionNo')->first();
        $operator = Operator::where("id", $quotation->userId)->first();
        $totalVersions = (int) $totalVersions;
        return response()->json(["data" => $quotation, "versionNo" => $totalVersions, "operator" => $operator, "approvedInvoices" => $approvedInvoices], 200);
    }

    public function getInvoices($id)
    {

        $allInvoices = QuotationInvoice::where("quotationId", $id)->get();
        return response()->json(["data" => $allInvoices], 200);
    }

    public function approveInvoices($id)
    {

        $Invoice = QuotationInvoice::find($id);
        $Invoice->status = 1;
        $Invoice->save();

        return response()->json(["message" => "Invoice Status Approved"], 200);
    }

    public function revokeInvoices($id)
    {

        $Invoice = QuotationInvoice::find($id);
        $Invoice->status = 0;
        $Invoice->save();

        return response()->json(["message" => "Invoice Status Approved"], 200);
    }

    public function sendInvoice($id, $version, $amount)
    {

        if ($version == 1) {
            $quotation = Quotation::where("id", $id)->where("versionNo", $version)->first();
        } else {
            $quotation = Quotation::where("quotationParent", $id)->where("versionNo", $version)->first();
        }
        //return response()->json([$quotation],422);
        $companyDetails = User::where("id", $quotation->userId)->with(["userable", "banks"])->first();
        $userDetails = $companyDetails->userable;
        $banks = $companyDetails->banks;

        $branchInfo = Branch::find(1);

        $getTime = time();
        $randValue = rand();
        $tourFrom = Carbon::createFromDate($quotation->tourFrom)->format("d/m/Y");
        $tourTo = Carbon::createFromDate($quotation->tourTo)->format("d/m/Y");
        $today = Carbon::now()->format("d/m/Y");
        $totalSalesQuotation = number_format($quotation->totalSales);

        //return response()->json([$id, $quotation, $amount, $companyDetails,$branchInfo,$tourFrom,$today,$totalSalesQuotation,$userDetails,$banks],422);

        /*$view = View::make('invoicePDF',compact("id", "quotation", "amount", "companyDetails","branchInfo","tourFrom","tourTo","today","totalSalesQuotation","userDetails","banks"));
        $html_content = $view->render();

        PDF::SetTitle('Quotation Details');
        PDF::AddPage();
        PDF::writeHTML($html_content, true, false, true, false, '');
        PDF::Output(public_path('invoicePDF/invoice_' .$id.'_'.$getTime.$randValue. '.pdf'), 'F');
        PDF::reset();*/
        $pdf = DOMPDF::loadView('invoicePDF', compact("id", "quotation", "amount", "companyDetails", "branchInfo", "tourFrom", "tourTo", "today", "totalSalesQuotation", "userDetails", "banks"))->save('invoicePDF/invoice_' . $id . '_' . $getTime . $randValue . '.pdf');

        Mail::to($quotation->clientEmail)->send(new InvoiceGenerated(["id" => $id, "quotation" => $quotation, "amount" => $amount, "companyDetails" => $companyDetails, "branchInfo" => $branchInfo, "tourFrom" => $tourFrom, "tourTo" => $tourTo, "today" => $today, "totalSalesQuotation" => $totalSalesQuotation, "userDetails" => $userDetails, "banks" => $banks, "getTime" => $getTime, "randValue" => $randValue]));

        $quotationInvoice = new QuotationInvoice;

        $quotationInvoice->quotationId = $id;
        $quotationInvoice->quotationVersion = $version;
        $quotationInvoice->invoiceDate = date('d-m-Y');
        $quotationInvoice->pdf = 'invoice_' . $id . '_' . $getTime . $randValue . '.pdf';
        $quotationInvoice->dueAmount = $amount;
        $quotationInvoice->totalAmount = $quotation->totalSales;
        $quotationInvoice->remainingAmount = $quotation->totalSales - $amount;

        $quotationInvoice->save();

        return response()->json(["message" => "Email sent Successfully"], 200);
    }
    public function emailInvoice(Request $request)
    {
        $id = $request->route("id");
        $invoiceAmount = $request->invoiceAmount;
        $description = $request->description;
        $dueDate = $request->dueDate;
        $quotation = Quotation::find($id);

        $approvedVersionId = $quotation->approvedVersionId;
        if ($quotation->quotationParent != NULL) {
            $quotationParent = Quotation::find($quotation->quotationParent);
            $approvedVersionId = $quotation->approvedVersionId;
        }
        $quotation = Quotation::with(["user", "user.banks", "user.userable"])->find($approvedVersionId);

        // $companyDetails = User::where("id",$quotation->userId)->with(["userable","banks"])->first();
        $user = $quotation->user;
        if ($quotation->processedBy) {
            $user = $quotation->processedByUser;
        }
        //return response()->json(["data"=>$user],422);
        $userDetails = $user->userable;
        //$banks = $user->banks;
        $totalPayment = $quotation->totalSales;
        $quotationInvoices = QuotationInvoice::where("quotationId", $approvedVersionId)->get();
        $totalPaid = 0;
        if ($totalInvoices = count($quotationInvoices)) {
            for ($i = 0; $i < $totalInvoices; $i++) {
                if ($quotationInvoices[$i]->status) {
                    $totalPaid = $totalPaid + $quotationInvoices[$i]->dueAmount;
                }
            }
        } else {
            $quotation->status = 9;
            $quotation->save();
        }
        $totalRemaining = $totalPayment - $totalPaid;
        if ($invoiceAmount > $totalRemaining) {
            return response()->json(["message" => "Total remaining amount is PKR " . number_format($totalRemaining) . ". Please enter amount less than the remaining amount"], 422);
        }

        //$totalRemaining = $totalRemaining-$invoiceAmount;

        $branchInfo = Branch::find(1);

        $getTime = time();
        $randValue = rand();
        $tourFrom = Carbon::createFromDate($quotation->tourFrom)->format("d/m/Y");
        $tourTo = Carbon::createFromDate($quotation->tourTo)->format("d/m/Y");
        $today = Carbon::now()->format("d/m/Y");
        $dueDate = Carbon::createFromFormat("d/m/Y", $dueDate)->format("d/m/Y");
        $totalSalesQuotation = number_format($quotation->totalSales);

        if ($user->hasRole("Operator") || ($user->hasRole("Staff") && $userDetails->staffable_type != "Admin")) {
            if ($user->hasRole("Staff")) {
                $userDetails = Operator::with("user.banks")->find($userDetails->staffable_id);
                $banks = $userDetails->user->banks;
            } else {
                $banks = $user->banks;
            }

            if ($userDetails->companyTitle) {
                $organizerName = $userDetails->companyTitle;
            } else {
                $organizerName = $userDetails->contactPerson;
            }
            if ($userDetails->businessEmail) {
                $organizerEmail = $userDetails->businessEmail;
            }
            if ($userDetails->operatorLogo) {
                $invoiceLogo = env("CLOUDINARY_BASE_URL") . "c_fit,h_100,w_500/" . $userDetails->operatorLogo;
            }
        } else {
            $adminInfo = User::with("banks")->where("userable_type", "Admin")->first();
            $banks = $adminInfo->banks;

            $organizerName = "Travel Pakistani";
            $organizerEmail = "sales@travelpakistani.com";
            $invoiceLogo = "https://res.cloudinary.com/www-travelpakistani-com/image/upload/v1641984500/download.png";
        }

        //return response()->json(["data"=>$banks],200);

        // return response()->json(["message"=>$user->getRoleNames()],422);

        $pdf = DOMPDF::loadView('invoicePDF', compact("id", "dueDate", "organizerName", "invoiceLogo", "organizerEmail", "description", "quotation", "invoiceAmount", "totalPaid", "totalRemaining", "user", "branchInfo", "tourFrom", "tourTo", "today", "totalSalesQuotation", "userDetails", "banks"))->save('invoicePDF/invoice_' . $id . '_' . $getTime . $randValue . '.pdf');

        Mail::to($quotation->clientEmail)->send(new InvoiceGenerated(["id" => $id, "dueDate" => $dueDate, "invoiceLogo" => $invoiceLogo, "organizerName" => $organizerName, "organizerEmail" => $organizerEmail, "description" => $description, "quotation" => $quotation, "totalPaid" => $totalPaid, "totalRemaining" => $totalRemaining, "invoiceAmount" => $invoiceAmount, "userDetails" => $userDetails, "branchInfo" => $branchInfo, "tourFrom" => $tourFrom, "tourTo" => $tourTo, "today" => $today, "totalSalesQuotation" => $totalSalesQuotation, "user" => $user, "banks" => $banks, "getTime" => $getTime, "randValue" => $randValue]));

        //return response()->json(["message"=>"hello3"],422);
        $quotationInvoice = new QuotationInvoice;

        $quotationInvoice->quotationId = $id;
        $quotationInvoice->quotationVersion = $approvedVersionId;
        $quotationInvoice->invoiceDate = Carbon::now()->format("Y-m-d");
        $quotationInvoice->pdf = 'invoice_' . $id . '_' . $getTime . $randValue . '.pdf';
        $quotationInvoice->dueAmount = $invoiceAmount;
        $quotationInvoice->totalAmount = $quotation->totalSales;
        $quotationInvoice->remainingAmount = $totalRemaining;
        $quotationInvoice->description = $description;
        $quotationInvoice->dueDate = Carbon::createFromFormat("d/m/Y", $dueDate)->format("Y-m-d");

        $quotationInvoice->save();


        return response()->json(["message" => "Invoice has been created and email sent successfully!"], 201);
    }
    public function invoicesAll(Request $request)
    {
        $id = $request->route("id");
        $quotation = Quotation::find($id);

        $approvedVersionId = $quotation->approvedVersionId;
        if ($quotation->quotationParent != NULL) {
            $quotationParent = Quotation::find($quotation->quotationParent);
            $approvedVersionId = $quotation->approvedVersionId;
        }

        $invoices = QuotationInvoice::where("quotationId", $approvedVersionId)->get();

        $totalPaid = 0;
        if ($totalInvoices = count($invoices)) {
            for ($i = 0; $i < $totalInvoices; $i++) {
                if ($invoices[$i]->status) {
                    $totalPaid = $totalPaid + $invoices[$i]->dueAmount;
                }
                $invoices[$i]->totalPaid = $totalPaid;
            }
        }
        // $totalRemaining = $totalPayment-$totalPaid;
        return response()->json(["data" => $invoices], 200);
    }
    public function updateInvoiceStatus(Request $request)
    {
        $id = $request->route("id");
        $status = $request->status;

        $invoice = QuotationInvoice::find($id);
        $invoiceStatus = $invoice->status;
        if ($invoiceStatus && !$status) {
            return response()->json(["message" => "You cannot change the status of a paid invoice to un-paid"], 422);
        }

        $invoice->status = $status;
        $invoice->remainingAmount = $invoice->remainingAmount - $invoice->dueAmount;
        $invoice->save();
        return response()->json(["message" => "Invoice status has been updated successfully!", "data" => $invoice], 200);
    }
    public function approvedVersion(Request $request)
    {
        $id = $request->route("id");
        $quotation = Quotation::find($id);

        $approvedVersionId = $quotation->approvedVersionId;
        if ($quotation->quotationParent != NULL) {
            $quotationParent = Quotation::find($quotation->quotationParent);
            $approvedVersionId = $quotation->approvedVersionId;
        }
        if (!$approvedVersionId) {
            return response()->json(["message" => "Only approved quotations are allowed to create invoices"], 422);
        }
        $quotation = Quotation::find($approvedVersionId);
        $totalPayment = $quotation->totalSales;

        $quotationInvoices = QuotationInvoice::where("quotationId", $approvedVersionId)->get();
        $totalPaid = 0;
        if ($totalInvoices = count($quotationInvoices)) {
            for ($i = 0; $i < $totalInvoices; $i++) {
                if ($quotationInvoices[$i]->status) {
                    $totalPaid = $totalPaid + $quotationInvoices[$i]->dueAmount;
                }
            }
        }
        $totalRemaining = $totalPayment - $totalPaid;

        return response()->json(["data" => $quotation, "totalPaid" => $totalPaid, "totalRemaining" => $totalRemaining], 200);
    }
    public function images(Request $request)
    {
        $id = $request->route("id");
        $version = $request->route("version");
        $user = Auth::user();
        if ($version > 1) {
            $quotation = Quotation::where("quotationParent", $id)->where("versionNo", $version)->first();
            $id = $quotation->id;
            $version = $quotation->versionNo;
        }
        $images = QuotationImage::where("quotationId", $id)->get();
        return response()->json(["data" => $images], 200);
    }

    function quotationImages(Request $request)
    {
        $quotationId = $request->route('id');
        $version = $request->route('version');
        if ($version > 1) {
            $quotation = Quotation::where("quotationParent", $quotationId)->where("versionNo", $version)->first();
            $version = $quotation->versionNo;
        } else if ($version == 1) {
            $quotation = Quotation::find($quotationId);
            $version = $quotation->versionNo;
        } else {
            return response()->json(["message", "Invalid version or quotation id"], 422);
        }
        $quotationId = $quotation->id;
        $title = isset($request->title) ? $request->title : "";
        //$version = $versionNo;//1;//$request->route('version');
        $imgOptions = ['folder' => env('TOUR_IMAGES_PATH'), 'format' => 'webp'];
        $cloudder = Cloudder::upload($request->file('file')->getRealPath(), null, $imgOptions);
        $result = $cloudder->getResult();
        if (isset($result['public_id'])) {
            $publicId = $result['public_id'];
            //$quotation = Quotation::find($quotationId);
            $image = new QuotationImage();
            $image->quotationId = $quotationId;
            $image->title = $title;
            $image->image = $publicId . '.webp';
            $image->version = $version;
            $image->save();
            $quotationImage = Cloudder::show($publicId, [
                'format' => 'webp'
            ]);
            return response()->json(["result" => $result, "imageInfo" => $image], 200);
        } else {
            return response()->json(['message' => "There is some issue in uploading photo"], 422);
        }
    }
    public function deleteImage(Request $request)
    {
        QuotationImage::find($request->route("id"))->delete();
        return response()->json(["message" => "Photo Deleted successfully!"], 200);
    }

    public function addExtraMarkup(Request $request)
    {

        $quotationId = (int) $request->quotationId;

        $Quotation = Quotation::find($quotationId);
        $Quotation->extraMarkup = (int) $request->markupValue;
        $Quotation->status = "5";
        $Quotation->save();

        return response()->json(["message" => "Markup added successfully!"], 200);
    }

    public function getExtraMarkup($quotationId)
    {

        $quotationId = (int) $quotationId;

        $extraMarkup = Quotation::where('id', $quotationId)->get();
        return response()->json(["data" => $extraMarkup], 200);
    }

    /*
    private function saveBasicInformation($request, $quotation, $version = 1, $inquiryId = 0)
    {
        $user = Auth::user();

        $quotation->userId = $user->id;

        if ($inquiryId > 0) {
            $quotation->inquiryId = $inquiryId;
        }

        $requiredServicesUser = $request->requiredServices;
        $notesUser = $request->termTypes ? $request->termTypes : array();
        $ru = count((is_countable($requiredServicesUser) ? $requiredServicesUser : []));
        $servicesRequired = array();

        for ($i = 0; $i < $ru; $i++) {
            $servicesRequired[$requiredServicesUser[$i]] = true;
        }
        //$quotation->quotationParent = 0;
        $quotation->staffRemarks = $request->staffRemarks;
        $quotation->markupType = $request->quotationMarkupType;
        $quotation->versionNo = $version;
        $quotation->quotationsTitle = $request->quotationTitle;
        $quotation->cityId = $request->cityId;
        $quotation->clientName = $request->clientName;
        $expiryReason = "";

        if (isset($request->isExpired) && $request->isExpired == 1) {
            $expiryReason = $request->expiryReason;
        }

        $quotation->expiryReason = $expiryReason;
        if (!isset($quotation->totalEmails) || (isset($quotation->totalEmails) && $quotation->totalEmails == 0)) {
            $quotation->clientEmail = $request->email;
        }

        $quotation->clientContact = $request->phone;
        $quotation->citiesToVisit = json_encode($request->citiesToVisit);

        if (isset($request->processedBy) && $request->processedBy != "") {
            $quotation->processedBy = $request->processedBy;
        }

        if ($request->has('otherAreas') && $request->otherAreas != "") {
            $otherAreas = json_decode($request->otherAreas);
            $totalAreas = count($otherAreas);
            $otherAreasArr = array();
            for ($i = 0; $i < $totalAreas; $i++) {
                $otherAreasArr[] = $otherAreas[$i]->value;
            }
            $quotation->otherAreas = json_encode($otherAreasArr);
        }

        $tourDates = explode("-", $request->tourDates);
        $quotation->tourFrom = Carbon::createFromFormat("d/m/Y", trim($tourDates[0]))->format("Y-m-d"); //Carbon::createFromDate($tourDates[0])->format("Y-m-d");
        $quotation->tourEnd = Carbon::createFromFormat("d/m/Y", trim($tourDates[1]))->format("Y-m-d"); //Carbon::createFromDate($tourDates[1])->format("Y-m-d");
        $quotation->adults = $request->adults;
        $quotation->children = $request->children;
        if ($request->quotationValidity)
            $quotation->validity = Carbon::createFromFormat("m/d/Y", $request->quotationValidity)->format("Y-m-d");
        $quotation->status = isset($request->quotationStatus) ? $request->quotationStatus : 6; //Draft
        $availableServices = config("enums.availableSerivices");
        $availableNotes = config("enums.typesTerms");
        $totalServices = count((is_countable($availableServices) ? $availableServices : []));
        $totalNotes = count((is_countable($availableNotes) ? $availableNotes : []));
        $servicesArr = $notesArr = array();
        for ($i = 0; $i < $totalServices; $i++) {
            $servicesArr[$availableServices[$i]] = isset($servicesRequired[$availableServices[$i]]) ? true : false;
        }
        foreach ($availableNotes as $k => $v) {
            if (in_array($k, $notesUser))
                $notesArr[$k] = true;
            else
                $notesArr[$k] = false;
        }

        $quotation->requiredServices = json_encode($servicesArr);
        $quotation->userNotes = json_encode($notesArr);
        $sendEmail = isset($request->sendEmail) ? $request->sendEmail : 0;
        $quotation->showCost = isset($request->showCost) ? $request->showCost : 0;
        $quotation->showPrice = isset($request->showPrice) ? $request->showPrice : 0;
        $quotation->showPerPersonCost = isset($request->showPerPersonCost) ? $request->showPerPersonCost : 0;
        $quotation->email = $sendEmail;
        //$quotation->approvedVersionId = NULL;
        if (isset($request->isApproved) && $request->isApproved && $quotation->status != 3) {
            $quotationParentId = $quotation->id;
            if ($quotation->quotationParent != NULL) {
                $quotationParentId = $quotation->quotationParent;
            }
            \DB::table("quotations")->where("quotationParent", $quotationParentId)->update(["approvedVersionId" => $quotation->id]);
            \DB::table("quotations")->where("id", $quotationParentId)->update(["approvedVersionId" => $quotation->id, "status" => 8]);
            $quotation->status = 8;
        } else {
            $quotationParentId = $quotation->id;
            if ($quotation->quotationParent != NULL) {
                $quotationParentId = $quotation->quotationParent;
            }
            \DB::table("quotations")->where("quotationParent", $quotationParentId)->update(["approvedVersionId" => NULL]);
            \DB::table("quotations")->where("id", $quotationParentId)->update(["approvedVersionId" => NULL]);
        }

        $quotation->isTemplate = 0;
        if (isset($request->saveAsTemplate) && $request->saveAsTemplate == 1) {
            $quotation->isTemplate = 1;
        }

        $quotation->save();
        return $quotation;
    }
    */

    public function store(Request $request, Quotation $quotation)
    {

        $this->authorize('create', $quotation);
        /*$checkIn = $request->checkIn[0];
        $checkInCarbon = Carbon::parse($request->checkIn[0])->format('Y-m-d H:i');
        return response()->json(["checkin"=>$checkIn,"carbon formated"=>$checkInCarbon],422);*/
        $emailPdf = $request->emailType;
        $quotation = new Quotation();
        $inquiryId = $request->inquiryId > 0 ? $request->inquiryId : 0;

        $request->request->add(["requiredServices" => array(), 'quotationStatus' => 6]);
        //$request->quotationStatus
        $quotation = $this->saveBasicInformation($request, $quotation, 1, $inquiryId);
        if (isset($quotation->id)) {
            $hashId = $quotation->id * 7585795975989869898667454765786;
            $quotation->liveQuotation = $hashId;
            $quotation->save();
        }
        return response()->json(['data' => $quotation, "message" => "Quotation created successfully!"], 201);
        /*$quote = $this->saveQuote($request,$quotation,1);
        $notes = $this->saveNotes($request,$quotation->id,1);
        $basicItinerary=$this->saveBasicItinerary($request,$quotation);
        if($quotation)
        {
            if($request->inquiryId)
            {
                $inquiry = Inquiry::find($request->inquiryId);
                $inquiry->status="Quoted";
                $inquiry->save();
            }
            if($emailPdf==1)
            {
                $request->request->add(['quotationId' => $quotation->id]);
                $this->emailPDF($request);
                return response()->json(['data'=>$quotation,"message"=>"Email sent with quotation details"],201);
            }
            return response()->json(['data'=>$quotation,"message"=>"Quotation created successfully!"],201);
        }
        else
        return response()->json(['message'=>"Problem in creating quotation"],500);       */
    }

    public function getQuotationId($request)
    {
        $quotationId = $request->route("id");
        $version = $request->route('version');
        if ($version > 1) {
            $currentQuotation = Quotation::where("quotationParent", $quotationId)->where("versionNo", $version)->first();
        } else {
            $currentQuotation = Quotation::find($quotationId);
        }
        return $currentQuotation->id;
    }
    public function update(Request $request)
    {
        $quotationId = $request->route("id");
        $version = $request->route('version');
        $tourDates = explode("-", $request->tourDates);
        $inquiryId = 0;
        if ($version > 1) {
            $currentQuotation = Quotation::where("quotationParent", $quotationId)->where("versionNo", $version)->first();
            $quotationParent = $currentQuotation->quotationParent;
        } else {
            $currentQuotation = Quotation::find($quotationId);
            $quotationParent = $currentQuotation->id;
        }

        if ($currentQuotation->inquiryId)
            $inquiryId = $currentQuotation->inquiryId;
        $totalVersions = Quotation::where("quotationParent", $quotationParent)->count();
        $totalVersions = $totalVersions + 1;
        // return response()->json(["versions"=>$totalVersions],422);
        /*$currentQuotation = Quotation::find($quotationId);
        $quotationParent = $currentQuotation->id;
        if($currentQuotation->quotationParent>0)
        {
            $quotationParent = $currentQuotation->quotationParent;
        }
        $child = Quotation::selectRaw("max(id) as currentQuotation")->where("quotationParent",$quotationParent)->first();
        $currentVersion = ($child && $child->currentQuotation>0)?Quotation::find($child->currentQuotation)->versionNo:1;        */

        $isNew = $request->isNew;
        $emailPdf = $request->emailType;
        $updateType = $request->updateType;
        // $tourDates = explode("-",$request->tourDates);
        /*$child = Quotation::selectRaw("max(id) as currentQuotation")->where("quotationParent",$quotationParent)->first();
        if($child->currentQuotation>0)
        {
            $currentQuotation=Quotation::find($child->currentQuotation);
            $quotationParent = $currentQuotation->quotationParent;
        }*/
        //return response()->json(["data"=>$currentVersion],422);
        if ($isNew && $updateType == "basic") {
            $quotation = new Quotation();
            $version = $totalVersions + 1;
            $quotation->quotationParent = $quotationParent;
        } else {
            $quotation = $currentQuotation;
            $version = $quotation->versionNo;
            if (isset($request->isExpired) && $request->isExpired == 1) {
                $request->request->add(['quotationStatus' => 3]);
            }
            // return response()->json(["data"=>$request->quotationStatus],422);
            //$this->removeExtraData($request,$quotation);
        }
        //return response()->json(["version"=>$version],422);
        $this->authorize('update', $quotation);
        $quotation = $this->saveBasicInformation($request, $quotation, $version, $inquiryId);

        if ($quotation->id && ($quotation->liveQuotation == NULL || $quotation->liveQuotation == "")) {
            $hashId = $quotation->id * 7585795975989869898667454765786;
            $quotation->liveQuotation = base64_encode($hashId);
            $quotation->save();
        }

        if ($updateType == "quote") {
            if (!$isNew)
                $this->removeExtraData($request, $quotation, "quote");
            $quote = $this->saveQuote($request, $quotation, $version);
            // return response()->json(["data"=>$quote],200);
        } else if ($updateType == "notes") {
            if (!$isNew)
                $this->removeExtraData($request, $quotation, "notes");
            $notes = $this->saveNotes($request, $quotation->id, $version);
        } else if ($updateType == "itinerary" && $isNew) {

            $itineraryList = Quotation::find($request->route("id"))->itineraryBasic;
            //return response()->json(["data"=>$itineraryList,"quotation"=>$currentQuotation],200);
            $this->saveExistingItinerary($itineraryList, $quotation->id);
        }
        /*
        else if($updateType=="itinerary")
        {
            if(!$isNew)
                $this->removeExtraData($request,$quotation,"itinerary");
            $basicItinerary=$this->saveBasicItinerary($request,$quotation);
        }*/
        //return $updateType;
        if ($quotation) {
            /*if($emailPdf==1)
            {
                $request->request->add(['quotationId' => $quotation->id]);
                $this->emailPDF($request);
                return response()->json(['data'=>$quotation,"message"=>"Email sent with updated quotation"],200);
            }*/
            return response()->json(['data' => $quotation, "message" => "Quotation updated successfully!"], 200);
        } else
            return response()->json(['message' => "Problem in updating quotation"], 500);
    }
    public function removeQuotation($id)
    {
        $delQuotation = Quotation::where('id', $id)->delete();
        return response()->json(["message" => "Quotation deleted successfully"], 200);
    }
    private function removeExtraData($request, $quotation, $remDataType)
    {
        if ($remDataType == "quote") {
            $hotelIds = $request->hotelId;
            if (isset($request->hotelId) && $totalIds = count($hotelIds)) {
                $requiredIds = array();
                for ($i = 0; $i < $totalIds; $i++) {
                    $requiredIds[] = $hotelIds[$i];
                }
                HotelQuotation::where("quotationId", $quotation->id)->whereNotIn("id", $requiredIds)->delete();
            } else
                HotelQuotation::where("quotationId", $quotation->id)->delete();

            $transferIds = $request->transferId;
            $requiredIdsService = array();
            if (isset($request->transferId) && $totalIds = count($transferIds)) {
                for ($i = 0; $i < $totalIds; $i++) {
                    $requiredIdsService[] = $transferIds[$i];
                }
            }

            $mealIds = $request->mealId;
            if (isset($request->mealId) && $totalIds = count($mealIds)) {
                $requiredIds = array();
                for ($i = 0; $i < $totalIds; $i++) {
                    $requiredIdsService[] = $mealIds[$i];
                }
            }

            $activityIds = $request->activityId;
            if (isset($request->activityId) && $totalIds = count($activityIds)) {
                $requiredIds = array();
                for ($i = 0; $i < $totalIds; $i++) {
                    $requiredIdsService[] = $activityIds[$i];
                }
            }
            //optServiceId

            $optServiceIds = $request->optServiceId;
            if (isset($request->optServiceId) && $totalIds = count($optServiceIds)) {
                $requiredIds = array();
                for ($i = 0; $i < $totalIds; $i++) {
                    $requiredIdsService[] = $optServiceIds[$i];
                }
            }

            $otherServiceIds = $request->otherServiceId;
            if (isset($request->otherServiceId) && $totalIds = count($otherServiceIds)) {
                $requiredIds = array();
                for ($i = 0; $i < $totalIds; $i++) {
                    $requiredIdsService[] = $otherServiceIds[$i];
                }
            }

            if (count($requiredIdsService)) {
                ServiceQuotation::where("quotationId", $quotation->id)->whereNotIn("id", $requiredIdsService)->delete();
            } else {
                ServiceQuotation::where("quotationId", $quotation->id)->delete();
            }
        } else if ($remDataType == "notes") {
            $notesIds = $request->notesId;
            if (isset($request->notesId) && $totalIds = count($notesIds)) {
                $requiredNotes = array();
                for ($i = 0; $i < $totalIds; $i++) {
                    $requiredNotes[] = $notesIds[$i];
                }
                QuotationNote::where("quotationId", $quotation->id)->whereNotIn("id", $requiredNotes)->delete();
            } else {
                QuotationNote::where("quotationId", $quotation->id)->delete();
            }
        } else if ($remDataType == "itinerary") {
            $itineraryBasicIds = $request->itineraryBasicId;
            if (isset($request->itineraryBasicId) && $totalIds = count($itineraryBasicIds)) {
                $requiredItinerary = array();
                for ($i = 0; $i < $totalIds; $i++) {
                    $requiredItinerary[] = $itineraryBasicIds[$i];
                }
                ItineraryQuotation::where("quotationable_id", $quotation->id)->where("quotationable_type", "Quotation")->whereNotIn("id", $requiredItinerary)->delete();
            } else {
                ItineraryQuotation::where("quotationable_id", $quotation->id)->where("quotationable_type", "Quotation")->delete();
            }
        }
    }
    private function saveQuote($request, $quotation, $version = 1)
    {
        $quotationId = $quotation->id;
        // return $quotationId;
        //$version = 1;

        /*if($quotation->quotationParent > 0)
        {
            $totalVersions = Quotation::select("count(id) as totalVersions")->where("quotationParent",$quotation->quotationParent)->first();
            $version = $totalVersions+1;
        }  */

        $hotelQuote = $this->saveHotel($request, $quotationId, $version);
        $transferQuote = $this->saveTransfer($request, $quotationId, $version);
        //return $transferQuote;
        $mealQuote = $this->saveMeal($request, $quotationId, $version);

        $activitiesQuote = $this->saveActivities($request, $quotationId, $version);
        $optServicesQuotate = $this->saveOptServices($request, $quotationId, $version);
        $otherServicesQuote = $this->saveOtherServices($request, $quotationId, $version);
        $quotationMarkupValue = $quotationDiscountValue = 0;
        $quotationMarkupType = $quotationDiscountType = "";
        $qouteCost = $hotelQuote["totalCost"] + $transferQuote["totalCost"] + $mealQuote["totalCost"] + $activitiesQuote["totalCost"] + $otherServicesQuote["totalCost"];
        $qouteSales = $hotelQuote["totalSales"] + $transferQuote["totalSales"] + $mealQuote["totalSales"] + $activitiesQuote["totalSales"] + $otherServicesQuote["totalSales"];
        //return array($mealQuote,$hotelQuote);
        if ($request->quotationMarkupType == "Total" && ($request->totalMarkupType == "Flat" || $request->totalMarkupType == "Percentage") && $request->extraMarkup > 0) {
            $markupTypeQuotation = $quotationMarkupType = $request->totalMarkupType;
            $markupValue = $request->extraMarkup;
            $quotationMarkupValue = $markupValue;
            if ($markupTypeQuotation == "Percentage") {
                $markupValue = ($markupValue / 100) * $qouteSales;
            }
            $qouteSales = $qouteSales + $markupValue;
        }
        if ($request->totalDiscount > 0) {
            $discountValue = $request->totalDiscount;
            $quotationDiscountValue = $discountValue;
            $quotationDiscountType = $request->discountType;
            if ($request->discountType == "Percentage") {
                $discountValue = ($discountValue / 100) * $qouteSales;
            }
            $qouteSales = $qouteSales - $discountValue;
            // return $qouteSales;
        }
        // return [$qouteCost,$qouteSales];
        $updateQuotation = Quotation::find($quotationId);
        $updateQuotation->totalCost = $qouteCost;
        $updateQuotation->totalSales = $qouteSales;
        $updateQuotation->extraMarkup = $quotationMarkupValue;
        $updateQuotation->discountType = $quotationDiscountType;
        $updateQuotation->discountValue = $quotationDiscountValue;
        $updateQuotation->markupTypeQuotation = $quotationMarkupType;
        $updateQuotation->save();
    }
    private function saveHotel($request, $quotationId, $versionNo)
    {
        $quotationCost = 0;
        $quotationSales = 0;
        $isNew = $request->isNew;

        if (isset($request->description)) {
            $totalHotels = count($request->hotelName);
            for ($i = 0; $i < $totalHotels; $i++) {
                if (isset($request->hotelId[$i]) && $request->hotelId[$i] > 0 && !$isNew) {
                    $hotel = HotelQuotation::find($request->hotelId[$i]);
                } else {
                    $hotel = new HotelQuotation();
                }
                $hotel->quotationId = $quotationId;
                $hotel->hotelName = $request->hotelName[$i];
                $hotel->checkIn = Carbon::parse($request->checkIn[$i])->format("Y-m-d H:i");
                $hotel->checkout = Carbon::parse($request->checkOut[$i])->format("Y-m-d H:i");
                $hotel->instructions = $request->instructions[$i];
                $hotel->nights = $request->nights[$i];
                $hotel->unitCost = $request->unitPriceHotel[$i];
                $hotel->totalUnits = $request->unitsHotel[$i];
                $totalCost = $request->unitPriceHotel[$i] * $request->unitsHotel[$i] * $request->nights[$i];
                $hotel->hotelCost = $totalCost;
                $markupAmount = 0;
                if ($request->has("markupTypeHotel") && $request->has("markupHotel")) {
                    $hotel->markupValue = $request->markupHotel[$i];
                    $hotel->markupType = $request->markupTypeHotel[$i];
                    if ($request->markupTypeHotel[$i] == "Percentage" && $request->markupHotel[$i] > 0) {
                        $markupAmount = (($request->markupHotel[$i] / 100) * $totalCost);
                    } else if ($request->markupTypeHotel[$i] == "Flat" && $request->markupHotel[$i] > 0) {
                        $markupAmount = $request->markupHotel[$i];
                    }
                }
                $hotel->hotelMarkupAmount = $markupAmount;
                $totalSales = $markupAmount + $totalCost;
                $hotel->hotelSales = $totalSales;
                $hotel->versionNo = $versionNo;
                $hotel->save();

                $quotationCost = $quotationCost + $totalCost;
                $quotationSales = $quotationSales + $totalSales;
            }
        }
        $result = array('totalCost' => $quotationCost, 'totalSales' => $quotationSales);
        return $result;
    }

    private function saveTransfer($request, $quotationId, $version = 1)
    {
        $quotationCost = 0;
        $quotationSales = 0;
        $isNew = $request->isNew;
        if (isset($request->transferDesc)) {
            $totalTransfers = count($request->transferDesc);
            //return $totalTransfers;
            for ($i = 0; $i < $totalTransfers; $i++) {
                if (isset($request->transferId[$i]) && $request->transferId[$i] > 0 && !$isNew) {
                    $service = ServiceQuotation::find($request->transferId[$i]);
                } else {
                    $service = new ServiceQuotation();
                }
                $service->quotationId = $quotationId;
                $service->description = $request->transferDesc[$i];
                $service->serviceType = $request->transferType[$i];
                $tDateType = $request->transferDateType[$i];
                $service->serviceDateType = $request->transferDateType[$i];
                if ($tDateType != "nodate") {
                    $service->calculateByDays = $request->calcDaysTransfer[$i];
                    $service->serviceDate = Carbon::parse($request->transferDate[$i])->format('Y-m-d');
                    $service->serviceEndDate = Carbon::parse($request->transferEndDate[$i])->format('Y-m-d');
                } else {
                    $service->calculateByDays = 0;
                    $service->serviceDate = "";
                    $service->serviceEndDate = NULL;
                }
                $service->totalDays = $request->totalDaysTransfer[$i];


                //$service->serviceDate = Carbon::parse($request->transferDate[$i])->format('Y-m-d H:i');
                $service->instructions = $request->remarksTransfer[$i];
                $service->unitCost = $request->unitCostTransfer[$i];
                $service->totalUnits = $request->unitsTransfer[$i];
                $totalCost = $request->unitCostTransfer[$i] * $request->unitsTransfer[$i];
                if ($request->calcDaysTransfer[$i] && $request->totalDaysTransfer[$i] && $tDateType != "nodate") {
                    $totalCost = $totalCost * $request->totalDaysTransfer[$i];
                }
                $service->serviceCost = $totalCost;
                $markupAmount = 0;
                if ($request->has("markupTypeTransfer") && $request->has("markupTransfer")) {
                    $service->markupValue = $request->markupTransfer[$i];
                    $service->markupType = $request->markupTypeTransfer[$i];
                    if ($request->markupTypeTransfer[$i] == "Percentage" && $request->markupTransfer[$i] > 0) {
                        $markupAmount = (($request->markupTransfer[$i] / 100) * $totalCost);
                    } else if ($request->markupTypeTransfer[$i] == "Flat" && $request->markupTransfer[$i] > 0) {
                        $markupAmount = $request->markupTransfer[$i];
                    }
                }

                $service->serviceMarkupAmount = $markupAmount;
                $totalSales = $markupAmount + $totalCost;
                $service->serviceSales = $totalSales;
                $service->versionNo = $version;
                $service->save();
                $quotationCost = $quotationCost + $totalCost;
                $quotationSales = $quotationSales + $totalSales;
            }
        }

        $result = array('totalCost' => $quotationCost, 'totalSales' => $quotationSales);
        return $result;
    }
    private function dummy()
    {


        $quotationId = $this->getQuotationId($request);
        $quotation = Quotation::find($quotationId);
        $fileInputImage = $request->file("imgItinerary");
        $uploadedImage = "";
        if (isset($request->itineraryId) && $request->itineraryId > 0) {
            $itinerary = ItineraryQuotation::find($request->itineraryId);
            $statusCode = 200;
        } else {
            $itinerary = new ItineraryQuotation();
            $statusCode = 201;
        }

        $itinerary->day = $request->itineraryDay;
        $itinerary->title = $request->itineraryTitle;
        $itinerary->details = $request->itineraryDesc;
        if (isset($fileInputImage)) {
            $imgOptions = ['folder' => 'itineraryPictures', 'format' => 'webp'];
            $cloudder = Cloudder::upload($fileInputImage->getRealPath(), null, $imgOptions);
            $result = $cloudder->getResult();
            if (isset($result['public_id'])) {
                $itineraryImage = $result['public_id'];
            }
            $itinerary->photo = $itineraryImage;
            $uploadedImage = $result["url"];
        } else if (isset($request->templatePhoto) && $request->templatePhoto != "" && $request->templatePhoto != "null") {
            $itinerary->photo = $request->templatePhoto;
            $uploadedImage = Cloudder::show($request->templatePhoto);
            $uploadedImage = $uploadedImage;
        } elseif ($itinerary->photo) {
            $uploadedImage = Cloudder::show($itinerary->photo);
        }
        $quotation->itineraryBasic()->save($itinerary);
        $itinerary->itineraryUploadedImage = $uploadedImage;
        return response()->json(["data" => $itinerary], $statusCode);
    }
    private function saveActivities($request, $quotationId, $version = 1)
    {
        $quotationCost = 0;
        $quotationSales = 0;
        $isNew = $request->isNew;
        if (isset($request->activityDate)) {
            $totalActivities = count($request->activityDate);
            for ($i = 0; $i < $totalActivities; $i++) {
                if (isset($request->activityId[$i]) && $request->activityId[$i] > 0 && !$isNew) {
                    $service = ServiceQuotation::find($request->activityId[$i]);
                } else {
                    $service = new ServiceQuotation();
                }
                $service->quotationId = $quotationId;
                $service->description = $request->activityDesc[$i];
                $service->serviceType = "Activity";
                $service->serviceDateType = $request->activityDateType[$i];
                $service->totalDays = $request->totalDaysActivity[$i];

                if ($request->activityDateType[$i] != "nodate") {
                    $service->calculateByDays = $request->calcDaysActivity[$i];
                    $service->serviceDate = Carbon::parse($request->activityDate[$i])->format('Y-m-d');
                    $service->serviceEndDate = Carbon::parse($request->activityEndDate[$i])->format('Y-m-d');
                } else {
                    $service->calculateByDays = 0;
                    $service->serviceDate = "";
                    $service->serviceEndDate = NULL;
                }

                $service->instructions = $request->remarksActivity[$i];
                $service->unitCost = $request->unitCostActivity[$i];
                $service->totalUnits = $request->activityUnits[$i];
                $totalCost = $request->unitCostActivity[$i] * $request->activityUnits[$i];
                if ($request->calcDaysActivity[$i] && $request->totalDaysActivity[$i]) {
                    $totalCost = $totalCost * $request->totalDaysActivity[$i];
                }
                $service->serviceCost = $totalCost;
                $markupAmount = 0;
                if ($request->has("markupActivity") && $request->has("markupTypeActivity")) {
                    if ($request->markupTypeActivity[$i] == "Percentage" && $request->markupActivity[$i] > 0) {
                        $markupAmount = (($request->markupActivity[$i] / 100) * $totalCost);
                    } else if ($request->markupTypeActivity[$i] == "Flat" && $request->markupActivity[$i] > 0) {
                        $markupAmount = $request->markupActivity[$i];
                    }
                    $service->markupValue = $request->markupActivity[$i];
                    $service->markupType = $request->markupTypeActivity[$i];
                }
                $service->serviceMarkupAmount = $markupAmount;
                $totalSales = $markupAmount + $totalCost;
                $service->serviceSales = $totalSales;
                $service->versionNo = $version;
                $service->save();
                $quotationCost = $quotationCost + $totalCost;
                $quotationSales = $quotationSales + $totalSales;
            }
        }

        $result = array('totalCost' => $quotationCost, 'totalSales' => $quotationSales);
        return $result;
    }
    private function saveOptServices($request, $quotationId, $version = 1)
    {
        $quotationCost = 0;
        $quotationSales = 0;
        $isNew = $request->isNew;
        if (isset($request->optServiceDate)) {
            $totalOptServices = count($request->optServiceDate);
            for ($i = 0; $i < $totalOptServices; $i++) {
                if (isset($request->optServiceId[$i]) && $request->optServiceId[$i] > 0 && !$isNew) {
                    $service = ServiceQuotation::find($request->optServiceId[$i]);
                } else {
                    $service = new ServiceQuotation();
                }
                $service->quotationId = $quotationId;
                $service->description = $request->optServiceDesc[$i];
                $service->serviceType = "Optional"; //$request->transferType[$i];
                //$service->serviceDate = Carbon::parse($request->optServiceDate[$i])->format('Y-m-d H:i');
                $service->serviceDateType = $request->optServiceDateType[$i];
                $service->totalDays = $request->totalDaysOptService[$i];
                $service->instructions = $request->remarksOptService[$i];

                if ($request->optServiceDateType[$i] != "nodate") {
                    $service->calculateByDays = $request->calcDaysOptService[$i];
                    $service->serviceDate = Carbon::parse($request->optServiceDate[$i])->format('Y-m-d');
                    $service->serviceEndDate = Carbon::parse($request->optServiceEndDate[$i])->format('Y-m-d');
                } else {
                    $service->calculateByDays = 0;
                    $service->serviceDate = "";
                    $service->serviceEndDate = NULL;
                }

                $service->unitCost = $request->unitCostOptService[$i];
                $service->totalUnits = $request->optServiceUnits[$i];
                $totalCost = $request->unitCostOptService[$i] * $request->optServiceUnits[$i];
                if ($request->calcDaysOptService[$i] && $request->totalDaysOptService[$i]) {
                    $totalCost = $totalCost * $request->totalDaysOptService[$i];
                }
                $service->serviceCost = $totalCost;
                $markupAmount = 0;
                if ($request->has("markupTypeOptService") && $request->has("markupOptService")) {
                    if ($request->markupTypeOptService[$i] == "Percentage" && $request->markupOptService[$i] > 0) {
                        $markupAmount = (($request->markupOptService[$i] / 100) * $totalCost);
                    } else if ($request->markupTypeOptService[$i] == "Flat" && $request->markupOptService[$i] > 0) {
                        $markupAmount = $request->markupOptService[$i];
                    }
                    $service->markupValue = $request->markupOptService[$i];
                    $service->markupType = $request->markupTypeOptService[$i];
                }
                $service->serviceMarkupAmount = $markupAmount;
                $totalSales = $markupAmount + $totalCost;
                $service->serviceSales = $totalSales;
                $service->versionNo = $version;
                $service->save();
                $quotationCost = $quotationCost + $totalCost;
                $quotationSales = $quotationSales + $totalSales;
            }
        }

        $result = array('totalCost' => $quotationCost, 'totalSales' => $quotationSales);
        return $result;
    }
    private function saveOtherServices($request, $quotationId, $version = 1)
    {
        $quotationCost = 0;
        $quotationSales = 0;
        $isNew = $request->isNew;
        if (isset($request->otherServiceDate)) {
            $totalOtherServices = count($request->otherServiceDate);
            for ($i = 0; $i < $totalOtherServices; $i++) {
                if (isset($request->otherServiceId[$i]) && $request->otherServiceId[$i] > 0 && !$isNew) {
                    $service = ServiceQuotation::find($request->otherServiceId[$i]);
                } else {
                    $service = new ServiceQuotation();
                }
                $service->quotationId = $quotationId;
                $service->description = $request->otherServiceDesc[$i];
                $service->serviceType = "Other"; //$request->transferType[$i];
                //$service->serviceDate = Carbon::parse($request->otherServiceDate[$i])->format('Y-m-d H:i');
                $service->serviceDateType = $request->otherServiceDateType[$i];
                $service->totalDays = $request->totalDaysOtherService[$i];

                if ($request->otherServiceDateType[$i] != "nodate") {
                    $service->calculateByDays = $request->calcDaysOtherService[$i];
                    $service->serviceDate = Carbon::parse($request->otherServiceDate[$i])->format('Y-m-d');
                    $service->serviceEndDate = Carbon::parse($request->otherServiceEndDate[$i])->format('Y-m-d');
                } else {
                    $service->calculateByDays = 0;
                    $service->serviceDate = "";
                    $service->serviceEndDate = NULL;
                }

                $service->instructions = $request->remarksOtherService[$i];
                $service->unitCost = $request->unitCostOtherService[$i];
                $service->totalUnits = $request->otherServiceUnits[$i];
                // $service->calculateByDays = $request->calcDaysOtherService[$i];
                $totalCost = $request->unitCostOtherService[$i] * $request->otherServiceUnits[$i];
                if ($request->calcDaysOtherService[$i] && $request->totalDaysOtherService[$i]) {
                    $totalCost = $totalCost * $request->totalDaysOtherService[$i];
                }
                $service->serviceCost = $totalCost;
                $markupAmount = 0;
                if ($request->has("markupTypeOtherService") && $request->has("markupOtherService")) {
                    if ($request->markupTypeOtherService[$i] == "Percentage" && $request->markupOtherService[$i] > 0) {
                        $markupAmount = (($request->markupOtherService[$i] / 100) * $totalCost);
                    } else if ($request->markupTypeOtherService[$i] == "Flat" && $request->markupOtherService[$i] > 0) {
                        $markupAmount = $request->markupOtherService[$i];
                    }
                    $service->markupValue = $request->markupOtherService[$i];
                    $service->markupType = $request->markupTypeOtherService[$i];
                }
                $service->serviceMarkupAmount = $markupAmount;
                $totalSales = $markupAmount + $totalCost;
                $service->serviceSales = $totalSales;
                $service->versionNo = $version;
                $service->save();
                $quotationCost = $quotationCost + $totalCost;
                $quotationSales = $quotationSales + $totalSales;
            }
        }

        $result = array('totalCost' => $quotationCost, 'totalSales' => $quotationSales);
        return $result;
    }
    private function saveMeal($request, $quotationId, $version = 1)
    {
        $quotationCost = 0;
        $quotationSales = 0;
        $isNew = $request->isNew;
        if (isset($request->mealCost)) {
            $totalMeals = count($request->mealCost);
            //return $totalMeals;
            for ($i = 0; $i < $totalMeals; $i++) {

                $unitCostMeal = $request->mealCost[$i];
                $mealUnits = $request->mealUnits[$i];
                if (isset($request->mealId[$i]) && $request->mealId[$i] > 0 && !$isNew) {
                    $service = ServiceQuotation::find($request->mealId[$i]);
                } else {
                    $service = new ServiceQuotation();
                }
                $mealDType = $request->mealDateType[$i];
                $service->quotationId = $quotationId;
                $service->description = $request->mealType[$i];
                $service->serviceType = "Meal"; //$request->transferType[$i];
                $service->serviceDateType = $request->mealDateType[$i];
                $service->totalDays = $request->totalDaysMeal[$i];
                if ($mealDType != "nodate") {
                    $service->calculateByDays = $request->calcDaysMeal[$i];
                    $service->serviceDate = Carbon::parse($request->mealDate[$i])->format('Y-m-d');
                    $service->serviceEndDate = Carbon::parse($request->mealEndDate[$i])->format('Y-m-d');
                } else {
                    $service->calculateByDays = 0;
                    $service->serviceDate = "";
                    $service->serviceEndDate = NULL;
                }

                $service->instructions = $request->remarksMeal[$i];
                $service->unitCost = $unitCostMeal;
                $service->totalUnits = $mealUnits;
                $totalCost = $unitCostMeal * $mealUnits;
                if ($request->calcDaysMeal[$i] && $request->totalDaysMeal[$i] && $mealDType != "nodate") {
                    $totalCost = $totalCost * $request->totalDaysMeal[$i];
                }
                $service->serviceCost = $totalCost;
                $markupAmount = 0;
                if ($request->has("markupTypeMeal") && $request->has("markupMeal")) {
                    $service->markupType = $request->markupTypeMeal[$i];
                    $service->markupValue = $request->markupMeal[$i];
                    if ($request->markupTypeMeal[$i] == "Percentage" && $request->markupMeal[$i] > 0) {
                        $markupAmount = (($request->markupMeal[$i] / 100) * $totalCost);
                    } else if ($request->markupTypeMeal[$i] == "Flat" && $request->markupMeal[$i] > 0) {
                        $markupAmount = $request->markupMeal[$i];
                    }
                }

                $service->serviceMarkupAmount = $markupAmount;
                $totalSales = $markupAmount + $totalCost;
                $service->serviceSales = $totalSales;
                $service->versionNo = $version;
                $service->save();
                $quotationCost = $quotationCost + $totalCost;
                $quotationSales = $quotationSales + $totalSales;
            }
        }
        $result = array('totalCost' => $quotationCost, 'totalSales' => $quotationSales);
        return $result;
    }
    public function saveNotes($request, $quotationId, $version = 1)
    {
        //return;
        $isNew = $request->isNew;
        if (isset($request->notestitle)) {
            $notesTitles = $request->notestitle;
            $totalNotes = count($notesTitles);
            for ($i = 0; $i < $totalNotes; $i++) {
                if (isset($request->notesId[$i]) && $request->notesId[$i] > 0 && !$isNew) {
                    $note = QuotationNote::find($request->notesId[$i]);
                } else {
                    $note = new QuotationNote();
                }
                $note->quotationId = $quotationId;
                $note->title = $notesTitles[$i];
                $note->description = $request->notesDesc[$i];
                $note->type = $request->notesType[$i];
                $note->versionNo = $version;
                $note->save();
            }
        }
    }
    //Function to use in copyQuotation
    public function saveExistingItinerary($itineraryList, $quotationId)
    {
        $totalList = count($itineraryList);
        $quotation = Quotation::find($quotationId);
        $tourStartDate = $quotation->tourFrom;
        $tourEndDate = $quotation->tourEnd;
        $totalDays = Carbon::parse($tourStartDate)->diffInDays($tourEndDate);
        if ($totalList > $totalDays) {
            $totalList = $totalDays;
        }
        for ($i = 0; $i < $totalList; $i++) {
            $itinerary = new ItineraryQuotation();
            $itinerary->day = $itineraryList[$i]->day;
            $itinerary->title = $itineraryList[$i]->title;
            $itinerary->details = $itineraryList[$i]->details;
            $itinerary->photo = $itineraryList[$i]->photo;
            $quotation->itineraryBasic()->save($itinerary);
        }
    }
    public function saveExistingImages($images, $quotationId)
    {
        $totalImages = count($images);
        for ($i = 0; $i < $totalImages; $i++) {
            $image = new QuotationImage();
            $image->quotationId = $quotationId;
            $image->title = $images[$i]->title;
            $image->image = $images[$i]->image;
            $image->version = 1;
            $image->save();
        }
    }
    public function saveBasicItinerary($request, $quotation)
    {
        //ItineraryQuotation
        $isNew = $request->isNew;

        if (isset($request->itineraryBasicDay) && $request->itineraryBasicDay != "") {

            $totalDays = count($request->itineraryBasicDay);
            $fileInputImage = $request->file("imageGridItinerary");

            for ($i = 0; $i < $totalDays; $i++) {

                if (isset($request->itineraryBasicId[$i]) && $request->itineraryBasicId[$i] > 0 && !$isNew) {
                    $itinerary = ItineraryQuotation::find($request->itineraryBasicId[$i]);
                } else {
                    $itinerary = new ItineraryQuotation();
                }

                $itinerary->day = $request->itineraryBasicDay[$i];
                $itinerary->title = $request->itineraryBasicTitle[$i];
                $itinerary->details = $request->itineraryBasicDesc[$i];
                //$itineraryImage=NULL;
                if (isset($fileInputImage[$i])) {
                    $imgOptions = ['folder' => 'itineraryPictures', 'format' => 'webp'];
                    $cloudder = Cloudder::upload($fileInputImage[$i]->getRealPath(), null, $imgOptions);
                    $result = $cloudder->getResult();
                    if (isset($result['public_id'])) {
                        $itineraryImage = $result['public_id'];
                    }
                    $itinerary->photo = $itineraryImage;
                } else if (isset($request->templatePhotoURL[$i]) && $request->templatePhotoURL[$i] != "") {
                    $itinerary->photo = $request->templatePhotoURL[$i];
                }
                $quotation->itineraryBasic()->save($itinerary);
            }
        }
    }
    public function saveItineraryRow(Request $request)
    {
        $quotationId = $this->getQuotationId($request);
        $quotation = Quotation::find($quotationId);
        $fileInputImage = $request->file("imgItinerary");
        $landmarks = $request->landmarks;
        $uploadedImage = "";
        if (isset($request->itineraryId) && $request->itineraryId > 0) {
            $itinerary = ItineraryQuotation::find($request->itineraryId);
            $statusCode = 200;
        } else {
            $itinerary = new ItineraryQuotation();
            $statusCode = 201;
        }

        $itinerary->day = $request->itineraryDay;
        $itinerary->title = $request->itineraryTitle;
        $itinerary->details = $request->itineraryDesc;
        if ($landmarks && count($landmarks)) {
            $itinerary->landmarks = json_encode($request->landmarks);
        } else {
            $itinerary->landmarks = json_encode(array());
        }

        if (isset($fileInputImage)) {
            $imgOptions = ['folder' => 'itineraryPictures', 'format' => 'webp'];
            $cloudder = Cloudder::upload($fileInputImage->getRealPath(), null, $imgOptions);
            $result = $cloudder->getResult();
            if (isset($result['public_id'])) {
                $itineraryImage = $result['public_id'];
            }
            $itinerary->photo = $itineraryImage;
            $uploadedImage = $result["url"];
        } else if (isset($request->templatePhoto) && $request->templatePhoto != "" && $request->templatePhoto != "null") {
            $itinerary->photo = $request->templatePhoto;
            $uploadedImage = Cloudder::show($request->templatePhoto);
            $uploadedImage = $uploadedImage;
        } elseif ($itinerary->photo) {
            $uploadedImage = Cloudder::show($itinerary->photo);
        }
        //return response()->json(["data"=>$itinerary],422);
        $quotation->itineraryBasic()->save($itinerary);
        $itinerary->itineraryUploadedImage = $uploadedImage;
        return response()->json(["data" => $itinerary], $statusCode);
    }
    public function convertQuotation(Request $request)
    {
        $orderId = $request->id;
        $quotation = Quotation::find($orderId);
        if ($quotation->approvedVersionId) {
            $approvedQuotation = Quotation::find($quotation->approvedVersionId);
            $userExists = User::where("email", $approvedQuotation->clientEmail)->first();
            if (!$userExists) {
                $randString = Str::random(10);
                $randomStr = Str::random(5);
                $siteUser = new SiteUser();
                $siteUser->save();
                $user = new User();
                $user->username = $randomStr;
                $user->name = $approvedQuotation->clientName;
                $user->email = $approvedQuotation->clientEmail;
                $user->phone = $approvedQuotation->clientContact;
                $user->branchId = 1;
                $user->cityId = $approvedQuotation->cityId;
                $user->passwordText = $randString;
                $user->password = Hash::make($randString);
                $siteUser->user()->save($user);
                $user = $siteUser->user();
                $user->assignRole("User");
            } else {
                $user = $userExists;
            }

            $order = new QuotationOrder();
            $order->quotationId = $approvedQuotation->id;
            $order->userId = $user->id;
            $order->clientName = $approvedQuotation->clientName;
            $order->clientEmail = $approvedQuotation->clientEmail;
            $order->clientContact = $approvedQuotation->clientContact;
            $order->cityId = $approvedQuotation->cityId;
            $order->citiesToVisit = $approvedQuotation->citiesToVisit;
            $order->requiredServices = $approvedQuotation->requiredServices;
            $order->tourFrom = $approvedQuotation->tourFrom;
            $order->tourEnd = $approvedQuotation->tourEnd;
            $order->adults = $approvedQuotation->adults;
            $order->children = $approvedQuotation->children;
            $order->totalCost = $approvedQuotation->totalCost;
            $order->totalSales = $approvedQuotation->totalSales;
            $order->save();
            return response()->json(["data" => $order], 201);
        } else {
            return response()->json(["message" => "Please approve quotation first to convert it into order"], 422);
        }
    }
    public function copyQuotation(Request $request)
    {
        $inquiryId = $request->inquiryId;
        $quotationId = $request->route("id");
        $quotation = Quotation::with(["hotelQuotations", "serviceQuotations", "quotationNotes", "itineraryBasic", "quotationImages"])->find($quotationId);
        //return response()->json(["quotation"=>$quotation->hotelQuotations],422);
        $loggedInUser = Auth::user();
        if ($inquiryId) {
            $inquiry = Inquiry::find($inquiryId);
            $clientName = $inquiry->name;

            $newQuotation = new Quotation();
            $newQuotation->quotationsTitle = $quotation->quotationsTitle;
            $newQuotation->inquiryId = $inquiry->id;
            $newQuotation->userId = $loggedInUser->id;
            $newQuotation->clientName = $clientName;
            $newQuotation->clientEmail = $inquiry->email;
            $newQuotation->clientContact = $inquiry->contactNo;
            $newQuotation->cityId = $inquiry->cityId;
            $tourStartDate = $inquiry->tourFrom;
            $newQuotation->tourFrom = $tourStartDate;
            $tourEndDate = $inquiry->tourEnd;
            $newQuotation->tourEnd = $tourEndDate;
            $newQuotation->adults = $inquiry->adults;
            $newQuotation->children = $inquiry->children;
            $requiredServices = $inquiry->requiredServices;
            $newQuotation->requiredServices = $requiredServices;
            $newQuotation->citiesToVisit = $inquiry->citiesToVisit;
            $newQuotation->otherAreas = $inquiry->otherAreas;
            $newQuotation->validity = $tourStartDate;
            $newQuotation->staffRemarks = $inquiry->staffRemarks;
            $newQuotation->status = 6;
            $newQuotation->versionNo = 1;
            $newQuotation->userNotes = $quotation->userNotes;
            $newQuotation->processedBy = $loggedInUser->id;
            $newQuotation->markupType = $quotation->markupType;
            $newQuotation->save();

            $newQuotationId = $newQuotation->id;

            $hashId = $newQuotationId * 7585795975989869898667454765786;
            $newQuotation->liveQuotation = $hashId;
            $newQuotation->save();

            $requiredServices = json_decode($requiredServices, true);
            $totalRequiredServices = count($requiredServices);
            $request->request->add(['isNew' => true, "quotationMarkupType" => $quotation->markupType]);
            $arrRequiredServices = array_keys($requiredServices);
            $quotationNotes = $quotation->quotationNotes;
            $itineraryBasic = $quotation->itineraryBasic;
            $totalItineraryBasic = count($itineraryBasic);
            $totalQuotationNotes = count($quotationNotes);
            $quotationImages = $quotation->quotationImages;
            $totalImages = count($quotationImages);

            if ($totalRequiredServices) {
                $serviceQuotations = $quotation->serviceQuotations;
                $totalServices = count($serviceQuotations);
                $mealCounter = $activityCounter = $otherServiceCounter = $optServiceCounter = $transferCounter = 0;
                if (in_array("Hotel", $arrRequiredServices)) {
                    $hotels = $quotation->hotelQuotations;
                    $totalHotels = count($hotels);
                    $arrHotels = array();
                    for ($h = 0; $h < $totalHotels; $h++) {
                        $hotelNames[$h] = $hotels[$h]->hotelName;
                        $checkIn[$h] = Carbon::parse($tourStartDate)->format("Y-m-d H:i");
                        $checkout[$h] = Carbon::parse($tourStartDate)->addDay()->format("Y-m-d H:i");
                        $instructions[$h] = $hotels[$h]->instructions;
                        $nights[$h] = $hotels[$h]->nights;
                        $unitPriceHotel[$h] = $hotels[$h]->unitCost;
                        $unitsHotel[$h] = $hotels[$h]->totalUnits;
                        $markupHotel[$h] = $hotels[$h]->markupValue;
                        $markupTypeHotel[$h] = $hotels[$h]->markupType;
                    }
                }

                for ($s = 0; $s < $totalServices; $s++) {
                    $serviceType = $serviceQuotations[$s]->serviceType;
                    $serviceDateType = $serviceQuotations[$s]->serviceDateType;
                    if ($serviceType == "Meal") {
                        $mealCost[$mealCounter] = $serviceQuotations[$s]->unitCost;
                        $mealUnits[$mealCounter] = $newQuotation->adults;
                        $mealDateType[$mealCounter] = $serviceDateType;
                        $mealType[$mealCounter] = $serviceQuotations[$s]->description;
                        $totalDays = 1;
                        $serviceStartDate = NULL;
                        $serviceEndDate = NULL;
                        if ($serviceDateType == "nodate") {
                            $totalDays = 0;
                        } elseif ($serviceDateType == "tour" || $serviceDateType == "range") {
                            $serviceStartDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                            $serviceEndDate = Carbon::parse($tourEndDate)->format('Y-m-d');
                            $totalDays = Carbon::parse($tourStartDate)->diffInDays($tourEndDate);
                        } else {
                            $serviceStartDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                            $serviceEndDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                        }
                        $totalDaysMeal[$mealCounter] = $totalDays;
                        $calcDaysMeal[$mealCounter] = $serviceQuotations[$s]->calculateByDays;
                        $mealDate[$mealCounter] = $serviceStartDate;
                        $mealEndDate[$mealCounter] = $serviceEndDate;
                        $remarksMeal[$mealCounter] = $serviceQuotations[$s]->instructions;
                        if ($serviceQuotations[$s]->markupType) {
                            $markupTypeMeal[$mealCounter] = $serviceQuotations[$s]->markupType;
                            $markupMeal[$mealCounter] = $serviceQuotations[$s]->markupValue;
                        }
                        $mealCounter = $mealCounter + 1;
                    } elseif ($serviceType == "Activity") {
                        $unitCostActivity[$activityCounter] = $serviceQuotations[$s]->unitCost;
                        $activityUnits[$activityCounter] = $newQuotation->adults;
                        $activityDateType[$activityCounter] = $serviceDateType;
                        $activityDesc[$activityCounter] = $serviceQuotations[$s]->description;
                        $totalDays = 1;
                        $serviceStartDate = NULL;
                        $serviceEndDate = NULL;
                        if ($serviceDateType == "nodate") {
                            $totalDays = 0;
                        } elseif ($serviceDateType == "tour" || $serviceDateType == "range") {
                            $serviceStartDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                            $serviceEndDate = Carbon::parse($tourEndDate)->format('Y-m-d');
                            $totalDays = Carbon::parse($tourStartDate)->diffInDays($tourEndDate);
                        } else {
                            $serviceStartDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                            $serviceEndDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                        }
                        $totalDaysActivity[$activityCounter] = $totalDays;
                        $calcDaysActivity[$activityCounter] = $serviceQuotations[$s]->calculateByDays;
                        $activityDate[$activityCounter] = $serviceStartDate;
                        $activityEndDate[$activityCounter] = $serviceEndDate;
                        $remarksActivity[$activityCounter] = $serviceQuotations[$s]->instructions;
                        if ($serviceQuotations[$s]->markupType) {
                            $markupTypeMeal[$activityCounter] = $serviceQuotations[$s]->markupType;
                            $markupMeal[$activityCounter] = $serviceQuotations[$s]->markupValue;
                        }
                        $activityCounter = $activityCounter + 1;
                    } elseif ($serviceType == "Other") {
                        $unitCostOtherService[$otherServiceCounter] = $serviceQuotations[$s]->unitCost;
                        $otherServiceUnits[$otherServiceCounter] = $newQuotation->adults;
                        $otherServiceDate[$otherServiceCounter] = $serviceStartDate;
                        $otherServiceDesc[$otherServiceCounter] = $serviceQuotations[$s]->description;
                        $totalDays = 1;
                        $serviceStartDate = NULL;
                        $serviceEndDate = NULL;
                        if ($serviceDateType == "nodate") {
                            $totalDays = 0;
                        } elseif ($serviceDateType == "tour" || $serviceDateType == "range") {
                            $serviceStartDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                            $serviceEndDate = Carbon::parse($tourEndDate)->format('Y-m-d');
                            $totalDays = Carbon::parse($tourStartDate)->diffInDays($tourEndDate);
                        } else {
                            $serviceStartDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                            $serviceEndDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                        }
                        $totalDaysOtherService[$otherServiceCounter] = $totalDays;
                        $calcDaysOtherService[$otherServiceCounter] = $serviceQuotations[$s]->calculateByDays;
                        $otherServiceEndDate[$otherServiceCounter] = $serviceEndDate;
                        $otherServiceDateType[$otherServiceCounter] = $serviceDateType;
                        $remarksOtherService[$otherServiceCounter] = $serviceQuotations[$s]->instructions;
                        if ($serviceQuotations[$s]->markupType) {
                            $markupTypeMeal[$otherServiceCounter] = $serviceQuotations[$s]->markupType;
                            $markupMeal[$otherServiceCounter] = $serviceQuotations[$s]->markupValue;
                        }
                        $otherServiceCounter = $otherServiceCounter + 1;
                    } elseif ($serviceType == "Optional") {


                        $unitCostOptService[$optServiceCounter] = $serviceQuotations[$s]->unitCost;
                        $optServiceUnits[$optServiceCounter] = $newQuotation->adults;
                        $optServiceDateType[$optServiceCounter] = $serviceDateType;
                        $optServiceDesc[$optServiceCounter] = $serviceQuotations[$s]->description;
                        $totalDays = 1;
                        $serviceStartDate = NULL;
                        $serviceEndDate = NULL;
                        if ($serviceDateType == "nodate") {
                            $totalDays = 0;
                        } elseif ($serviceDateType == "tour" || $serviceDateType == "range") {
                            $serviceStartDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                            $serviceEndDate = Carbon::parse($tourEndDate)->format('Y-m-d');
                            $totalDays = Carbon::parse($tourStartDate)->diffInDays($tourEndDate);
                        } else {
                            $serviceStartDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                            $serviceEndDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                        }
                        $totalDaysOptService[$optServiceCounter] = $totalDays;
                        $calcDaysOptService[$optServiceCounter] = $serviceQuotations[$s]->calculateByDays;
                        $optServiceDate[$optServiceCounter] = $serviceStartDate;
                        $optServiceEndDate[$optServiceCounter] = $serviceEndDate;
                        $remarksOptService[$optServiceCounter] = $serviceQuotations[$s]->instructions;
                        if ($serviceQuotations[$s]->markupType) {
                            $markupTypeOptService[$optServiceCounter] = $serviceQuotations[$s]->markupType;
                            $markupOptService[$optServiceCounter] = $serviceQuotations[$s]->markupValue;
                        }
                        $optServiceCounter = $optServiceCounter + 1;
                    } else {
                        $unitCostTransfer[$transferCounter] = $serviceQuotations[$s]->unitCost;
                        $unitsTransfer[$transferCounter] = $newQuotation->adults;
                        $transferDateType[$transferCounter] = $serviceDateType;
                        $transferDesc[$transferCounter] = $serviceQuotations[$s]->description;
                        $transferType[$transferCounter] = $serviceQuotations[$s]->serviceType;
                        $totalDays = 1;
                        $serviceStartDate = NULL;
                        $serviceEndDate = NULL;
                        if ($serviceDateType == "nodate") {
                            $totalDays = 0;
                        } elseif ($serviceDateType == "tour" || $serviceDateType == "range") {
                            $serviceStartDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                            $serviceEndDate = Carbon::parse($tourEndDate)->format('Y-m-d');
                            $totalDays = Carbon::parse($tourStartDate)->diffInDays($tourEndDate);
                        } else {
                            $serviceStartDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                            $serviceEndDate = Carbon::parse($tourStartDate)->format('Y-m-d');
                        }
                        $totalDaysTransfer[$transferCounter] = $totalDays;
                        $calcDaysTransfer[$transferCounter] = $serviceQuotations[$s]->calculateByDays;
                        $transferDate[$transferCounter] = $serviceStartDate;
                        $transferEndDate[$transferCounter] = $serviceEndDate;
                        $remarksTransfer[$transferCounter] = $serviceQuotations[$s]->instructions;
                        if ($serviceQuotations[$s]->markupType) {
                            $markupTypeTransfer[$transferCounter] = $serviceQuotations[$s]->markupType;
                            $markupTransfer[$transferCounter] = $serviceQuotations[$s]->markupValue;
                        }
                        $transferCounter = $transferCounter + 1;
                    }
                }

                if (count($hotelNames) && in_array("Hotel", $arrRequiredServices)) {
                    $request->request->add(['hotelName' => $hotelNames]);
                    $request->request->add(['checkIn' => $checkIn]);
                    $request->request->add(['checkOut' => $checkout]);
                    $request->request->add(['nights' => $nights]);
                    $request->request->add(['instructions' => $instructions]);
                    $request->request->add(['unitPriceHotel' => $unitPriceHotel]);
                    $request->request->add(['unitsHotel' => $unitsHotel]);
                    $request->request->add(['markupHotel' => $markupHotel]);
                    $request->request->add(['markupTypeHotel' => $markupTypeHotel]);
                    //$this->saveHotel($request,$newQuotationId,1);
                }
                if (isset($mealCost) && count($mealCost) && in_array("Meal", $arrRequiredServices)) {
                    $request->request->add(['mealCost' => $mealCost]);
                    $request->request->add(['mealUnits' => $mealUnits]);
                    $request->request->add(['mealDateType' => $mealDateType]);
                    $request->request->add(['mealType' => $mealType]);
                    $request->request->add(['remarksMeal' => $remarksMeal]);
                    $request->request->add(['totalDaysMeal' => $totalDaysMeal]);
                    $request->request->add(['calcDaysMeal' => $calcDaysMeal]);
                    $request->request->add(['mealDate' => $mealDate]);
                    $request->request->add(['mealEndDate' => $mealEndDate]);
                    if (isset($markupMeal) && count($markupMeal)) {
                        $request->request->add(['markupTypeMeal' => $markupTypeMeal]);
                        $request->request->add(['markupMeal' => $markupMeal]);
                    }
                }
                if (isset($unitCostActivity) && count($unitCostActivity) && in_array("Activities", $arrRequiredServices)) {
                    $request->request->add(['unitCostActivity' => $unitCostActivity]);
                    $request->request->add(['activityUnits' => $activityUnits]);
                    $request->request->add(['activityDateType' => $activityDateType]);
                    $request->request->add(['activityDesc' => $activityDesc]);
                    $request->request->add(['remarksActivity' => $remarksActivity]);
                    $request->request->add(['totalDaysActivity' => $totalDaysActivity]);
                    $request->request->add(['calcDaysActivity' => $calcDaysActivity]);
                    $request->request->add(['activityDate' => $activityDate]);
                    $request->request->add(['activityEndDate' => $activityEndDate]);
                    if (isset($markupActivity) && count($markupActivity)) {
                        $request->request->add(['markupTypeActivity' => $markupTypeActivity]);
                        $request->request->add(['markupActivity' => $markupActivity]);
                    }
                }
                if (isset($unitCostOptService) && count($unitCostOptService)) {
                    $request->request->add(['optServiceDate' => $optServiceDate]);
                    $request->request->add(['unitCostOptService' => $unitCostOptService]);
                    $request->request->add(['optServiceUnits' => $optServiceUnits]);
                    $request->request->add(['optServiceDateType' => $optServiceDateType]);
                    $request->request->add(['calcDaysOptService' => $calcDaysOptService]);
                    $request->request->add(['remarksOptService' => $remarksOptService]);
                    $request->request->add(['totalDaysOptService' => $totalDaysOptService]);
                    $request->request->add(['optServiceDate' => $optServiceDate]);
                    $request->request->add(['optServiceEndDate' => $optServiceEndDate]);
                    $request->request->add(['optServiceDesc' => $optServiceDesc]);
                    if (isset($markupTypeOptService) && count($markupTypeOptService)) {
                        $request->request->add(['markupTypeOptService' => $markupTypeOptService]);
                        $request->request->add(['markupOptService' => $markupOptService]);
                    }
                }
                if (isset($unitCostOtherService) && count($unitCostOtherService)) {
                    $request->request->add(['otherServiceDesc' => $otherServiceDesc]);
                    $request->request->add(['unitCostOtherService' => $unitCostOtherService]);
                    $request->request->add(['otherServiceUnits' => $otherServiceUnits]);
                    $request->request->add(['otherServiceDateType' => $otherServiceDateType]);
                    $request->request->add(['calcDaysOtherService' => $calcDaysOtherService]);
                    $request->request->add(['remarksOtherService' => $remarksOtherService]);
                    $request->request->add(['totalDaysOtherService' => $totalDaysOtherService]);
                    $request->request->add(['otherServiceDate' => $otherServiceDate]);
                    $request->request->add(['otherServiceEndDate' => $otherServiceEndDate]);
                    if (isset($markupTypeOtherService) && count($markupTypeOtherService)) {
                        $request->request->add(['markupTypeOtherService' => $markupTypeOtherService]);
                        $request->request->add(['markupOtherService' => $markupOtherService]);
                    }
                }
                if (isset($unitsTransfer) && count($unitsTransfer) && in_array("Transport", $arrRequiredServices)) {
                    $request->request->add(['transferDesc' => $transferDesc]);
                    $request->request->add(['transferType' => $transferType]);
                    $request->request->add(['unitCostTransfer' => $unitCostTransfer]);
                    $request->request->add(['unitsTransfer' => $unitsTransfer]);
                    $request->request->add(['transferDateType' => $transferDateType]);
                    $request->request->add(['calcDaysTransfer' => $calcDaysTransfer]);
                    $request->request->add(['remarksTransfer' => $remarksTransfer]);
                    $request->request->add(['totalDaysTransfer' => $totalDaysTransfer]);
                    $request->request->add(['transferDate' => $transferDate]);
                    $request->request->add(['transferEndDate' => $transferEndDate]);
                    if (isset($markupTypeTransfer) && count($markupTypeTransfer)) {
                        $request->request->add(['markupTypeTransfer' => $markupTypeTransfer]);
                        $request->request->add(['markupTransfer' => $markupTransfer]);
                    }
                }
                $this->saveQuote($request, $newQuotation, 1);
            }
            if ($totalQuotationNotes) {

                for ($n = 0; $n < $totalQuotationNotes; $n++) {
                    $notesTitle[$n] = $quotationNotes[$n]->title;
                    $notesDesc[$n] = $quotationNotes[$n]->description;
                    $notesType[$n] = $quotationNotes[$n]->type;
                }

                $request->request->add(['notestitle' => $notesTitle]);
                $request->request->add(['notesDesc' => $notesDesc]);
                $request->request->add(['notesType' => $notesType]);
                $this->saveNotes($request, $newQuotationId, 1);
            }
            if ($totalItineraryBasic) {
                $this->saveExistingItinerary($itineraryBasic, $newQuotationId);
            }
            if ($totalImages) {
                $this->saveExistingImages($quotationImages, $newQuotationId);
            }
            return response()->json(["data" => $newQuotation], 201);
        }
    }
    public function deleteItinerary(Request $request)
    {
        $id = $request->route("id");
        $itinerary = ItineraryQuotation::where("id", $id)->delete();
        return response()->json(["message" => "Itinerary deleted successfully!"], 200);
    }
    public function submitQuotationResponse(Request $request)
    {

        $quotationId = $request->quotationId;
        $quotationId = Quotation::where("liveQuotation", $quotationId)->first();
        if (!$quotationId) {
            return response()->json(["message" => "Invalid Quotation"], 422);
        }
        $quotationId = $quotationId->id;
        /*$quotationId = base64_decode($quotationId);
        $quotationId = $quotationId / 7585795975989869898667454765786;*/

        if (QuotationResponse::where('quotationId', $quotationId)->exists()) {

            return response()->json(["message" => "Record already Exist", "status" => 409], 409);
        }

        try {

            $response = new QuotationResponse;

            $response->quotationId = $quotationId;
            $response->feedback = $request->feedback;
            $response->rating = $request->rating;

            $response->save();

            return response()->json(["message" => "Submittied Successfully", "status" => 200], 200);
        } catch (Exception $e) {

            return response()->json($e - getMessage(), 500);
        }
    }
    public function getQuotationData(Request $request)
    {

        $id = $request->route("id");

        /* $id = $id;
        $id = base64_decode($id);
        $id = $id / 7585795975989869898667454765786;
        $id = round($id);*/

        $costColumn = isset($request->costType) ? $request->costType : 1;
        //return response()->json(["cost col"=>$costColumn],422);
        //"hotelQuotations","serviceQuotations","quotationNotes","itineraryBasic","quotationImages"
        //$quotation = Quotation::with("city")->find($id);
        //return response()->json(["data"=>["id"=>$id,"route"=>$request->route("id")]],200);
        $quotation = Quotation::with(["city", "hotelQuotations", "quotationNotes", "serviceQuotations", "itineraryBasic" => function ($query) {
            $query->orderBy("day", "asc");
        }, "quotationImages"])->where("liveQuotation", $id)->first();

        $quotationImages = isset($quotation->quotationImages) ? $quotation->quotationImages : array();
        $totalImages = count($quotationImages);
        for ($i = 0; $i < $totalImages; $i++) {
            $imageFullPath = "";
            if ($quotationImages[$i]->image != "" && $quotationImages[$i]->image != NULL) {
                $imageFullPath = Cloudder::show($quotationImages[$i]->image);
            }
            $quotationImages[$i]->imageFullPath = $imageFullPath;
        }
        $itineraryBasic = isset($quotation->itineraryBasic) ? $quotation->itineraryBasic : array();
        $totalBasicItinerary = count($itineraryBasic);
        for ($i = 0; $i < $totalBasicItinerary; $i++) {
            $photoUrl = "";
            $itineraryBasic[$i]->details = nl2br($itineraryBasic[$i]->details);
            if ($itineraryBasic[$i]->photo != "" && $itineraryBasic[$i]->photo != NULL) {
                $photoUrl = Cloudder::show($itineraryBasic[$i]->photo, [
                    'format' => 'webp'
                ]);
            }
            $itineraryBasic[$i]->photoFullPath = $photoUrl;
        }
        $allServices = isset($quotation->serviceQuotations) ? $quotation->serviceQuotations : array();
        $totalServices = count($allServices);
        $activities = $meals = $transfers = $optionalItems = $otherItems = array();
        for ($i = 0; $i < $totalServices; $i++) {
            if ($allServices[$i]->serviceType == "Activity") {
                $activities[] = $allServices[$i];
            } else if ($allServices[$i]->serviceType == "Meal") {
                $meals[] = $allServices[$i];
            } else if ($allServices[$i]->serviceType == "Optional") {
                $optionalItems[] = $allServices[$i];
            } else if ($allServices[$i]->serviceType == "Other") {
                $otherItems[] = $allServices[$i];
            } else {
                $transfers[] = $allServices[$i];
            }
        }

        $user = User::find($quotation->userId);

        $processByInfo = User::find($quotation->processedBy);

        $branchInfo = Branch::find(1);

        $notes = isset($quotation->quotationNotes) ? $quotation->quotationNotes : array();
        $header = $poweredBy = array();
        if ($user->hasRole("Admin") || ($user->hasRole("Staff") && $user->userable->staffable_type == "Admin")) {
            $branchInfo = $user;

            $header["logo"] = "https://res.cloudinary.com/www-travelpakistani-com/image/upload/v1641984500/download.png";
            $header["contactPerson"] = "Travel Pakistani";
            $header["address"] = $branchInfo->branchAddress;
            $header["email"] = $branchInfo->branchEmail;
            $header["phone"] = $branchInfo->branchPhone;
            $header["preparedBy"] = $branchInfo->name;
            $header["refNo"] = $id;
            $header["operatorAbout"] = "Travel Pakistani is an online marketplace where tour operators and hosts can sell their tours directly to customers. You can select from hundreds of packages or request a customized tour based on your needs and budget. Visit our website today";
        } else if ($user->hasRole("Operator") || $user->hasRole("Staff")) {
            $userInfo = $user;
            if ($user->hasRole("Operator")) {
                $operatorId = $userInfo->userable_id;
            } else if ($user->hasRole("Staff")) {
                $staffable = $user->userable;
                $operatorId = $staffable->staffable_id;
            }

            $operatorInfo = Operator::find($operatorId);

            if ($operatorInfo->operatorLogo == "" || $operatorInfo->operatorLogo == NULL)
                $header["logo"] = "https://res.cloudinary.com/www-travelpakistani-com/image/upload/v1641984500/download.png";
            else
                $header["logo"] = env("CLOUDINARY_BASE_URL") . $operatorInfo->operatorLogo;
            $header["contactPerson"] = $operatorInfo->companyTitle;
            $header["address"] = $operatorInfo->companyAddress;
            $header["email"] = $operatorInfo->businessEmail;
            $header["phone"] = $operatorInfo->contactNumber;
            $header["preparedBy"] = $userInfo->name;
            $header["refNo"] = $id;
            $header["operatorAbout"] = $operatorInfo->operatorAbout;
        }

        $poweredBy["logo"] = $branchInfo->logo;
        $poweredBy["contactPerson"] = $branchInfo->contactPerson;
        $poweredBy["address"] = $branchInfo->branchAddress;
        $poweredBy["email"] = $branchInfo->branchEmail;
        $poweredBy["phone"] = $branchInfo->branchPhone;
        $poweredBy["branchName"] = $branchInfo->branchName;

        //return response()->json(["data"=>$user->userable_type],200);
        $title = $quotation->title;
        $prefDates = Carbon::parse($quotation->tourFrom)->format("d/m/Y") . " to " . Carbon::parse($quotation->tourEnd)->format("d/m/Y");
        $validity = Carbon::parse($quotation->validity)->format("d/m/Y");
        $requiredServices = json_decode($quotation->requiredServices);
        $otherAreas = json_decode($quotation->otherAreas);
        if ($otherAreas && count($otherAreas))
            $otherAreas = implode(",", $otherAreas);
        $plannedAreas = $quotation->planned_cities;
        $areaDetailsPDF = array();
        if ($plannedAreas) {
            $arrPlannedAreas = explode(",", $plannedAreas);
            $totalPlannedAreas = count($arrPlannedAreas);
            for ($i = 0; $i < $totalPlannedAreas; $i++) {
                $citiesToVisit[] = City::where("title", "like", "%" . $arrPlannedAreas[$i] . "%")->first();
                //$areaDetailsPDF[]=LandmarkItem::where("description","=",$arrPlannedAreas[$i])->first();
            }
        }

        if ($otherAreas != "" && $quotation->planned_cities != "")
            $plannedAreas .= ",";
        $plannedAreas .= $otherAreas;

        $services["Hotel"] = $requiredServices->Hotel ? "YES" : "NO";
        $services["Transport"] = $requiredServices->Transport ? "YES" : "NO";
        $services["Meal"] = $requiredServices->Meal ? "YES" : "NO";
        $services["Activities"] = $requiredServices->Activities ? "YES" : "NO";
        $requiredServices = $services;

        $hotels = $quotation->hotelQuotations;

        $notesAll = array();
        $totalNotes = count($notes);
        $totalNotes = $totalNotes > 5 ? 5 : $totalNotes;
        $typesTerms = config("enums.typesTerms");
        for ($i = 0; $i < $totalNotes; $i++) {
            $notesAll[$notes[$i]->type][] = $notes[$i];
        }
        $images = $quotation->quotationImages;
        //return;
        $itinerary = $quotation->itineraryBasic;

        $hashId = $quotation->liveQuotation; //$id * 7585795975989869898667454765786;

        $quotationMarkupType = $quotation->markupType;
        $quotationCostAmount = $quotation->totalCost;
        $salesAmountQuotation = $quotation->totalSales;
        $discountValue = $quotation->discountValue;
        $discountTypeQ = $quotation->discountType;
        $userDiscount = 0;
        if ($quotationMarkupType == "Total") {
            if ($quotation->markupTypeQuotation == "Percentage") {
                $markupValue = ($quotation->extraMarkup / 100) * $quotationCostAmount;
                $salesAmountQuotation = $quotationCostAmount + $markupValue;
            } else {
                $salesAmountQuotation = $quotationCostAmount + $quotation->extraMarkup;
            }

            if ($discountTypeQ == "Percentage") {
                $userDiscount = ($discountValue / 100) * $salesAmountQuotation;
            } else {
                $userDiscount = $discountValue;
            }
        } else {
            if ($discountTypeQ == "Percentage") {
                $ds = 1 - ($discountValue / 100);
                $salesAmountQuotation = $salesAmountQuotation / $ds;
                $userDiscount = $salesAmountQuotation * ($discountValue / 100);
            } else {
                $userDiscount = $discountValue;
                $salesAmountQuotation = $salesAmountQuotation + $discountValue;
            }
        }

        $finalPayment = $salesAmountQuotation - $userDiscount;

        $quotation->processedBy = isset($processByInfo->name) ? $processByInfo->name : $quotation->user->name;
        $processedByLogo = "";
        if (isset($processByInfo) && $processByInfo->profilePic) {
            $processedByLogo = "https://res.cloudinary.com/www-travelpakistani-com/image/upload/v1641984500/" . $processByInfo->profilePic;
        }

        $quotation->processedByLogo = $processedByLogo;

        return response()->json(compact("user", "header", "quotation", "prefDates", "validity", "requiredServices", "plannedAreas", "activities", "transfers", "meals", "optionalItems", "hotels", "notesAll", "typesTerms", "poweredBy", "costColumn", "areaDetailsPDF", "itinerary", "citiesToVisit", "otherItems", "salesAmountQuotation", "userDiscount", "finalPayment"), 200);
    }
    public function deleteTestQuotations()
    {
        $emails = array(
            "sdfghjfd@gmail.com",
            "aa@gmail.com",
            "mm@gmail.com",
            "naveedtest@gmail.com",
            "naveedtest12@gmail.com",
            "naveeddurrani@gmail.com",
            "test@test.com",
            "123@gmail.com",
            "abc@gmail.com",
            "xyz@gmail.com",
            "abdullahkhankangra@gmail.com",
            "abc@xyz.com",
            "abc@test.com",
            "aishazafarmit@yahoo.com"
        );
        //DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        if ($quotations = Quotation::whereIn("clientEmail", $emails)->delete()) {
            //DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return response()->json(["message" => "Quotations deleted successfully!"], 200);
        } else {
            return response()->json(["message" => "Test quotations does not exists!"], 200);
        }
    }
    public function emailPDF(Request $request)
    {

        if ($request->route("id")) {
            $id = $request->route("id");
            $showPrice = $request->route("showPrice");
            $showPriceItems = $request->route("showPriceItems");
        } else {
            $id = $request->quotationId;
        }
        $version = $request->route("version");
        $costColumn = isset($request->costType) ? $request->costType : 1;
        //return response()->json(["cost col"=>$costColumn],422);
        $quotation = Quotation::with(["city", "quotationNotes", "itineraryBasic"])->find($id);
        if ($quotation->status == 3) {
            return response()->json(["message" => "Quotation updated successfully!"], 200);
        }
        $quotation->status = 5;
        $totalEmailsSent = $quotation->totalEmails;
        $quotation->totalEmails = $totalEmailsSent + 1;
        $quotation->save();
        //  return response()->json(["data"=>$quotation->liveQuotation],422);
        $user = User::find($quotation->userId);
        $hashId = $quotation->liveQuotation;
        // $result = array('totalCost'=>$quotation);
        // return $result;

        $branchInfo = Branch::find(1);

        $notes = $quotation->quotationNotes;
        $header = $poweredBy = array();
        if ($user->userable_type == "Admin") {
            $branchInfo = $user;

            $header["logo"] = "https://res.cloudinary.com/www-travelpakistani-com/image/upload/v1641984500/download.png";
            $header["contactPerson"] = "Travel Pakistani";
            $header["address"] = $branchInfo->branchAddress;
            $header["email"] = $branchInfo->branchEmail;
            $header["phone"] = $branchInfo->branchPhone;
            $header["preparedBy"] = $branchInfo->name;
            $header["refNo"] = $id;
            $header["operatorAbout"] = "";

            $mailFrom = "sales@travelpakistani.com";
            $mailFromName = "Travel Pakistani";
        } else if ($user->userable_type == "Operator") {
            $userInfo = $user; //User::find($quotation->userId);
            $operatorInfo = Operator::find($userInfo->userable_id);

            $mailFrom = $operatorInfo->businessEmail;

            $mailFromName = html_entity_decode($operatorInfo->companyTitle);
            if (!$operatorInfo->companyTitle) {
                $MailFromName = $operatorInfo->contactPerson;
            }

            if ($operatorInfo->operatorLogo == "" || $operatorInfo->operatorLogo == NULL)
                $header["logo"] = "https://res.cloudinary.com/www-travelpakistani-com/image/upload/v1641984500/download.png";
            else
                $header["logo"] = env("CLOUDINARY_BASE_URL") . $operatorInfo->operatorLogo;
            $header["contactPerson"] = $operatorInfo->companyTitle;
            $header["address"] = $operatorInfo->companyAddress;
            $header["email"] = $operatorInfo->businessEmail;
            $header["phone"] = $operatorInfo->contactNumber;
            $header["preparedBy"] = $userInfo->name;
            $header["refNo"] = $id;
            $header["operatorAbout"] = $operatorInfo->operatorAbout;
        } else if ($user->userable_type == "Staff") {
            $userInfo = User::find($quotation->userId);
            $staffInfo = $userInfo->userable;
            // $mailFrom = env("mail_from_address");
            //$mailFromName = env("MAIL_FROM_NAME");
            if ($staffInfo->staffable_type == "Operator") {
                $operatorInfo = Operator::find($staffInfo->staffable_id);

                $mailFrom = $operatorInfo->businessEmail;
                $mailFromName = html_entity_decode($operatorInfo->companyTitle);
                if (!$operatorInfo->companyTitle) {
                    $MailFromName = $operatorInfo->contactPerson;
                }
            } else {
                $operatorInfo = User::find($staffInfo->staffable_id);
                $mailFrom = "sales@travelpakistani.com";
                $mailFromName = "Travel Pakistani";
            }
        }

        $poweredBy["logo"] = $branchInfo->logo;
        $poweredBy["contactPerson"] = $branchInfo->contactPerson;
        $poweredBy["address"] = $branchInfo->branchAddress;
        $poweredBy["email"] = $branchInfo->branchEmail;
        $poweredBy["phone"] = $branchInfo->branchPhone;
        $poweredBy["branchName"] = $branchInfo->branchName;

        //return response()->json(["data"=>$mailFrom],422);

        //return response()->json(["data"=>$user->userable_type],200);
        $title = $quotation->title;
        $prefDates = Carbon::parse($quotation->tourFrom)->format("d/m/Y") . " to " . Carbon::parse($quotation->tourEnd)->format("d/m/Y");
        $validity = Carbon::parse($quotation->validity)->format("d/m/Y");
        $requiredServices = json_decode($quotation->requiredServices);
        $otherAreas = json_decode($quotation->otherAreas);
        if ($otherAreas && count($otherAreas))
            $otherAreas = implode(",", $otherAreas);
        $plannedAreas = $quotation->planned_cities;
        $areaDetailsPDF = array();
        if ($plannedAreas) {
            $arrPlannedAreas = explode(",", $plannedAreas);
            $totalPlannedAreas = count($arrPlannedAreas);
            for ($i = 0; $i < $totalPlannedAreas; $i++) {
                $citiesToVisit[] = City::where("title", "like", "%" . $arrPlannedAreas[$i] . "%")->first();
                //$areaDetailsPDF[]=LandmarkItem::where("description","=",$arrPlannedAreas[$i])->first();
            }
        }

        if ($otherAreas != "" && $quotation->planned_cities != "")
            $plannedAreas .= ",";
        $plannedAreas .= $otherAreas;

        $services["Hotel"] = $requiredServices->Hotel ? "YES" : "NO";
        $services["Transport"] = $requiredServices->Transport ? "YES" : "NO";
        $services["Meal"] = $requiredServices->Meal ? "YES" : "NO";
        $services["Activities"] = $requiredServices->Activities ? "YES" : "NO";
        $requiredServices = $services;

        $hotels = $quotation->hotelQuotations;
        $otherServices = $quotation->serviceQuotations;
        $totalOtherServices = count($otherServices);
        $activities = $transfers = $meals = array();
        for ($i = 0; $i < $totalOtherServices; $i++) {
            if ($otherServices[$i]->serviceType == "Activity") {
                $activities[] = $otherServices[$i];
            } else if ($otherServices[$i]->serviceType == "Meal") {
                $meals[] = $otherServices[$i];
            } else {
                $transfers[] = $otherServices[$i];
            }
        }
        //return response()->json(["data"=>$notes],200);
        $notesAll = array();
        $totalNotes = count($notes);
        $totalNotes = $totalNotes > 5 ? 5 : $totalNotes;
        $typesTerms = config("enums.typesTerms");
        for ($i = 0; $i < $totalNotes; $i++) {
            $notesAll[$notes[$i]->type][] = $notes[$i];
        }
        $images = $quotation->quotationImages;
        //return;
        $itinerary = $quotation->itineraryBasic;

        // $result = array('totalCost'=>$poweredBy);
        // return $result;

        // $view = View::make('quotationPDF', compact("user","header","quotation","prefDates","validity","requiredServices","plannedAreas","activities","transfers","meals","hotels","notesAll","typesTerms","poweredBy","costColumn","areaDetailsPDF","itinerary", "citiesToVisit", "showPrice", "showPriceItems"));
        // $html_content = $view->render();

        // PDF::SetTitle('Quotation Details');
        // PDF::AddPage();
        // PDF::writeHTML($html_content, true, false, true, false, '');
        // PDF::Output(public_path('quotationPDF/quotation_' .$id. '.pdf'), 'F');
        // PDF::reset();

        Mail::to($quotation->clientEmail)->send(new QuotationGenerated($quotation, $header, array("mailFromName" => $mailFromName, "mailFrom" => $mailFrom, "pdf" => $id, "poweredBy" => $poweredBy, "user" => $user, "requiredServices" => $requiredServices, "plannedAreas" => $plannedAreas, "validity" => $validity, "prefDates" => $prefDates, "costColumn" => $costColumn, "areaDetailsPDF" => $areaDetailsPDF, "hashId" => $hashId)));
        return response()->json(["message" => "Quotation finished and email sent successfully!"], 200);
    }
    public function updateLiveQuotationLink()
    {
        $quotations = Quotation::all();
        $totalQuotations = count($quotations);
        for ($i = 0; $i < $totalQuotations; $i++) {
            if (!$quotations[$i]->liveQuotation) {
                $hashId = $quotations[$i]->id * 7585795975989869898667454765786;
                Quotation::where("id", $quotations[$i]->id)->update(["liveQuotation" => $hashId]);
            }
        }
    }
    public function changeStatus(Request $request)
    {

        $quotationId = (int)$request->quotationId;

        $Quotation = Quotation::find($quotationId);

        $Quotation->status = (int)$request->quotationStatus;

        $Quotation->save();

        return response()->json(["message" => "Quotation Status Updated"], 200);
    }

    // written by mirza

    public function showResponses(Request $request)
    {

        if ($request->ajax()) {

            $data = QuotationResponse::with('quotation')->latest('created_at')->get();
            // dd($data->toArray());
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('quotations.response_actions', ['row' => $row]);
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'viewed') {
                        return '<span class="badge bg-secondary">Viewed</span>';
                    }
                    if ($row->status == 'new') {
                        return '<span class="badge bg-success">New</span>';
                    }
                    if ($row->status == 'confirmed') {
                        return '<span class="badge bg-primary">Confirmed</span>';
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('quotations.quotation_responses');
    }

    public function getQuotationResponseDetails($id)
    {
        // \DB::enableQueryLog();
        $quotationsResponses = QuotationResponse::where('id', $id)->with('quotation')->first();
        if ($quotationsResponses->status == "new") {
            $quotationsResponses->status = "viewed";
            $quotationsResponses->save();
        }
        return view('quotations.quotation_response_modal', ['response' => $quotationsResponses]);
    }

    public function getQuotationChat($id)
    {
        $quotationsChat = QuotationChat::where('quotationId', $id)->orderBy("id", "asc")->get();
        $quotation = Quotation::findOrFail($id);
        // dd($quotationsChat->toArray());
        return view('quotations.quotation_response_chat', ['chat' => $quotationsChat, 'quotationId' => $id, 'quotation' => $quotation]);
    }

    public function submitQuotationChat(Request $request)
    {

        $result = "";

        $quotationId = (int)$request->quotationId;
        // $quotationId = base64_decode($quotationId);
        // $quotationId = $quotationId / 7585795975989869898667454765786;

        $quotation = Quotation::find($quotationId);
        try {

            $response = new QuotationChat();

            $response->quotationId = $quotationId;
            $response->message = $request->message;
            $response->type = $request->type;

            $response->save();

            $username = "923139367626"; ///Your Username
            $password = "Wonderweal-99"; ///Your Password
            $sender = "26144";

            if ($response->type == 'owner') {

                $mobile = $quotation->clientContact;

                $message = "You recieved a new quotation reply. Please visit - https://travelpakistani.com/quotation/chat/" . $response->quotationId . " to check.";

                $post = "sender=" . urlencode($sender) . "&mobile=" . urlencode($mobile) . "&message=" . urlencode($message) . "";

                $url = "https://sendpk.com/api/sms.php?username=" . $username . "&password=" . $password . "";
                $ch = curl_init();

                $timeout = 30; // set to zero for no timeout
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)');
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                $result = curl_exec($ch);
            }

            return redirect()->back()->with("success", "Submittied Successfully");
        } catch (Exception $e) {
            return redirect()->back()->with("error", "Error occurred");
        }
    }
}
