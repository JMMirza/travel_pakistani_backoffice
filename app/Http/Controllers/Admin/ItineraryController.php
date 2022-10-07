<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItineraryTemplate;
use Illuminate\Http\Request;
use DataTables;
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
        if ($request->ajax()) {

            $data = ItineraryTemplate::where('userId', $user->id)->with("category")->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('preferredDate', function ($row) {
                //     return $row->tourFrom . '-' . $row->tourEnd;
                // })
                // ->addColumn('status', function ($row) {
                //     if ($row->status == 'Expired') {
                //         return '<span class="badge bg-danger">Expired</span>';
                //     } elseif ($row->status == 'In Process') {
                //         return '<span class="badge bg-primary">Pending</span>';
                //     } elseif ($row->status == 'Confirm') {
                //         return '<span class="badge bg-default">Complete</span>';
                //     } else {
                //         return '<span class="badge bg-warning">New</span>';
                //     }
                // })
                ->addColumn('action', function ($row) {
                    return view('itinerary_templates.actions', ['row' => $row]);
                })
                ->rawColumns(['action', 'description'])
                ->make(true);
        }
        return view('itinerary_templates.itinerary_templates');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            "templateTitle" => "required|string|min:6",
            "totalDays" => "required|integer",
            "categoryId" => "required|integer"
        ]);
        $userId = Auth::user()->id;
        $input['userId'] = $userId;
        $input['templateType'] = $request->type;
        $input['templateTitle'] = $request->title;
        $input['totalDays'] = $request->days;
        $input['categoryId'] = $request->category;
        $input['status'] = $request->status;

        ItineraryTemplate::create($input);
        return redirect()->route('itinerary-templates.index')
            ->with('success', 'Itinerary Template created successfully.');
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
    public function edit($id)
    {
        $itinerary_template = ItineraryTemplate::find($id);
        return view('itinerary_templates.itinerary_templates', ['itinerary_template' => $itinerary_template]);
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

        $itinerary_template->update($input);
        return redirect()->route('templates.index')
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
