<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Landmark;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Database\QueryException;
use File;

class LandmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Landmark::with('category')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('parent_id', function ($row) {
                //     if ($row->parent_id != null)
                //         return $row->parent_id;
                //     else
                //         return 'N / A';
                // })
                ->addColumn('action', function ($row) {
                    return view('landmarks.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('landmarks.landmarks');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('landmarks.add_new_landmark');
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
            'title' => 'required|string|max:255',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'categoryId' => 'required|integer'
        ]);
        $input = $request->all();

        $file_name = time() . '.' . $request->image->extension();
        $path = 'uploads/landmarks';
        File::ensureDirectoryExists($path);

        $request->image->move(public_path($path), $file_name);

        $input['image'] = $file_name;
        Landmark::create($input);

        return redirect()->route('landmarks.index')
            ->with('success', 'Landmark created successfully.');
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
        $landmark = Landmark::find($id);
        return view('landmarks.edit_landmark', ['landmark' => $landmark]);
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
        $landmark = Landmark::find($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'categoryId' => 'required|integer'
        ]);

        $input = $request->all();
        // dd($input);

        if ($request->hasFile('product_picture')) {
            // dd($input);
            $file_name = time() . '.' . $request->product_picture->extension();
            $path = 'uploads/landmarks';
            File::ensureDirectoryExists($path);

            $request->product_picture->move(public_path($path), $file_name);

            $input['product_picture'] = $file_name;
        }
        $landmark->update($input);

        return redirect()->route('landmarks.index')
            ->with('success', 'Landmark updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $landmark = Landmark::find($id);
        try {
            return $landmark->delete();
        } catch (QueryException $e) {
            print_r($e->errorInfo);
        }
    }

    public function landmarkSuggestions(Request $request)
    {
        $city = $request->city;
        $type = $request->type;
        $landmarks = Landmark::where("cityId",$city)->where("categoryId",$type)->with("images")->get();
        return response()->json(['data'=>$landmarks]);
    }  
}
