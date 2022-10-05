<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\QueryException;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Inquiry::with(['createdByUser', 'user'])->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('preferredDate', function ($row) {
                    return $row->tourFrom . '-' . $row->tourEnd;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'Expired') {
                        return '<span class="badge bg-danger">Expired</span>';
                    } elseif ($row->status == 'In Process') {
                        return '<span class="badge bg-primary">Pending</span>';
                    } elseif ($row->status == 'Confirm') {
                        return '<span class="badge bg-default">Complete</span>';
                    } else {
                        return '<span class="badge bg-warning">New</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return view('inquiries.actions', ['row' => $row]);
                })
                ->rawColumns(['action', 'preferredDate', 'status'])
                ->make(true);
        }
        return view('inquiries.inquiries');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::latest()->get();
        return view('inquiries.add_new_inquiry', ['users' => $users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'contactNo' => 'required|string|max:255',
            'cityId' => 'required|integer',
            'adults' => 'required|integer',
            'requiredServices' => 'required|text'
        ]);

        Inquiry::create($request->all());

        return redirect()->route('inquiries.index')
            ->with('success', 'Inquiry created successfully.');
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
        $inquiry = Inquiry::findOrFail($id);
        // dd($inquiry->toArray());
        $users = User::latest()->get();
        return view('inquiries.edit_inquiry', ['inquiry' => $inquiry, 'users' => $users]);
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
            'name' => 'required|string|max:255',
            'contactNo' => 'required|string|max:255',
            'cityId' => 'required|integer',
            'adults' => 'required|integer',
            'requiredServices' => 'required|text'
        ]);
        $inquiry = Inquiry::findOrFail($id);

        $inquiry->update($request->all());

        return redirect()->route('inquiries.index')
            ->with('success', 'Inquiry updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        try {
            return $inquiry->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }
}
