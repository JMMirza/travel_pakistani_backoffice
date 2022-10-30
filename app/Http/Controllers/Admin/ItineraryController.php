<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\ItineraryTemplate;
use App\Models\ItineraryTemplateDetail;
use App\Models\QuotationImage;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use Cloudder;
use Illuminate\Database\QueryException;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = \Auth::user();
        $categories = Category::all();

        if ($request->ajax()) {

            // $data = ItineraryTemplate::where('userId', $user->id)->with("category")->latest()->get();
            $data = ItineraryTemplate::with("category")->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('preferredDate', function ($row) {
                //     return $row->tourFrom . '-' . $row->tourEnd;
                // })
                ->addColumn('status', function ($row) {
                    if ($row->status == '0') {
                        return '<span class="badge bg-danger">In Active</span>';
                    } else {
                        return '<span class="badge bg-primary">Active</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return view('itinerary_templates.actions', ['row' => $row]);
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('itinerary_templates.itinerary_templates', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = Category::all();
        $cities = City::all();
        return view('itinerary_templates.add_itinerary_template', [
            'cities' => $cities,
            'categories' => $categories,
            'tab' => $request->has('tab') ? $request->tab : 1
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "templateType" => "required", "string",
            "days" => "required|integer",
            "status" => "required|integer",
            "categoryId" => "required|integer"
        ]);

        $userId = Auth::user()->id;
        $input['userId'] = $userId;
        $input['templateType'] = $request->templateType;
        $input['templateTitle'] = $request->templateTitle;
        $input['totalDays'] = $request->days;
        $input['categoryId'] = $request->categoryId;
        $input['status'] = $request->status;

        $ItineraryTemplateObj = ItineraryTemplate::create($input);
        return redirect(route('itinerary-templates.edit', $ItineraryTemplateObj->id) . '?tab=2')->with('success', 'Itinerary Template created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $itinerary_template = ItineraryTemplate::findOrFail($id);
        $categories = Category::all();
        $cities = City::all();

        // dd($itinerary_template->with(['templateDetails.city'])->first()->toArray());

        return view(
            'itinerary_templates.edit_itinerary_template',
            [
                'itinerary_template' => $itinerary_template->with(['templateDetails.city', 'category'])->first(),
                'categories' => $categories,
                'cities' => $cities,
                'tab' => $request->has('tab') ? $request->tab : 1
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            "templateType" => "required", "string",
            "templateTitle" => "required|string|min:6",
            "totalDays" => "required|integer",
            "categoryId" => "required|integer"
        ]);
        $itinerary_template = ItineraryTemplate::find($id);
        $userId = Auth::user()->id;
        $input['userId'] = $userId;
        $input['templateType'] = $request->templateType;
        $input['templateTitle'] = $request->templateTitle;
        $input['totalDays'] = $request->totalDays;
        $input['categoryId'] = $request->categoryId;
        $input['status'] = $request->status;

        $itinerary_obj = $itinerary_template->update($input);


        $int_detail['templateId'] = $id;
        $int_detail['cityId'] = $request->itineraryCities;
        $int_detail['dayNo'] = $request->numberDays;
        $int_detail['pickupTime'] = $request->pickupTime;
        $int_detail['description'] = $request->discription;

        $itinerary_templateObj = ItineraryTemplateDetail::create($int_detail);


        //Photo upload
        if ($request->hasFile("photo")) {

            $imgOptions = ['folder' => 'TP-DestinationContent', 'format' => 'webp'];
            $cloudder = Cloudder::upload($request->file("photo")->getRealPath(), null, $imgOptions);

            $imgName = '';

            if ($cloudder) {
                $result = $cloudder->getResult();
                $imgName = $result['public_id'];
            }

            $itinerary_update = ItineraryTemplateDetail::where('id', $itinerary_templateObj->id)->update(['photo' => $imgName]);
        }


        return redirect()->route('itinerary-templates.index')
            ->with('success', 'Template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $template = ItineraryTemplate::findOrFail($id);
        try {
            return $template->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
