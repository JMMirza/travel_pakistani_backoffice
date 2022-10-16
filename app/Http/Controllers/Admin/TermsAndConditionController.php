<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomTemplate;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\QueryException;

class TermsAndConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = CustomTemplate::where('userId', \Auth::user()->id)->with('user')->latest()->get();
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
                    return view('custom_templates.actions', ['row' => $row]);
                })
                ->rawColumns(['action', 'description'])
                ->make(true);
        }
        return view('custom_templates.custom_templates');
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
            "title" => "required|string|min:6",
            "description" => "required|string|min:6",
            "type" => ["required", "string"],
        ]);
        $userId = Auth::user()->id;
        $input['userId'] = $userId;
        $input['title'] = $request->title;
        $input['description'] = $request->desc;
        $input['templateType'] = $request->type;

        CustomTemplate::create($input);
        return redirect()->route('templates.index')
            ->with('success', 'Template created successfully.');
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
        $template = CustomTemplate::find($id);
        return view('custom_templates.custom_templates', ['template' => $template]);
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
            "title" => "required|string|min:6",
            "description" => "required|string|min:6",
            "type" => ["required", "string"],
        ]);
        $template = CustomTemplate::find($id);
        $userId = Auth::user()->id;
        $input['userId'] = $userId;
        $input['title'] = $request->title;
        $input['description'] = $request->desc;
        $input['templateType'] = $request->type;

        $template->update($input);
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
        $template = CustomTemplate::findOrFail($id);
        try {
            return $template->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
