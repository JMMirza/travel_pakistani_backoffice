<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Operator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DataTables;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Operator::with("user")->get();
        // dd($data->toArray());
        if ($request->ajax()) {
            return Datatables::of($data)
                ->addIndexColumn()
                // ->addColumn('parent_id', function ($row) {
                //     if ($row->parent_id != null)
                //         return $row->parent_id;
                //     else
                //         return 'N / A';
                // })
                ->addColumn('action', function ($row) {
                    return view('operators.actions', ['row' => $row]);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('operators.operators');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::all();
        return view('operators.create_new_operator', ['cities' => $cities]);
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            "cityId" => "required",
            "username" => "required|min:6|max:254|unique:users,username|alpha_dash",
            //"branchId"=>"required|integer|exists:branches,id",
            "companyTitle" => "required|string|unique:operators,companyTitle|max:254",
            //"contactPerson"=>"required|string|min:8",
            //"businessEmail"=>"required|email|unique:operators,businessEmail",
            //"contactNumber"=>"required",
            //"companyAddress"=>"required|string|min:8",
            //"operatorLogo"=>"required|image",
            //"operatorAbout"=>"required",
            //"businessType"=>"required"
        ]);

        $operator = Operator::create([
            'companyTitle' => $request->companyTitle,
            'contactPerson' => "",
            'businessEmail' => "",
            'contactNumber' => "",
            'companyAddress' => "",
            'operatorLogo' => "",
            'operatorAbout' => "",
            'businessType' => "",
            'status' => 0,
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => strtolower($request->username),
            'cityId' => $request->cityId,
            'branchId' => 1,
            'password' => Hash::make($request->password),
            'credits' => env("OPERATOR_CREDITS_FREE")
        ]);
        $operator->user()->save($user);
        $user->attachRole('operator');
        return redirect()->route('login')
            ->with('success', 'Account created successfully please check your email to activate your account');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $operator = Operator::with(["user", "user.city"])->find($id);
        // dd($operator->toArray());
        $cities = City::all();
        return view('operators.edit_operator', ['operator' => $operator, 'cities' => $cities]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = "")
    {
        if ($id == "")
            $id = \Auth::user()->userable->id;
        $rules = [
            //"branchId"=>"sometimes|integer",
            //"password"=>"sometimes|string:min:8",
            "companyTitle" => "sometimes|string|min:8",
            "contactPerson" => "sometimes|string|min:8",
            // "businessEmail"=>"sometimes|email|unique:operators,businessEmail",
            "companyAddress" => "sometimes|string|min:8",
            //"operatorLogo"=>"sometimes|image",
        ];
        $this->validate($request, $rules);
        //return $this->json(["contactPerson"=>$request->companyTitle],200);
        //$dir = "Logos";
        $operator = Operator::find($id);

        if ($request->has('companyTitle'))
            $operator->companyTitle = $request->companyTitle;
        if ($request->has('contactPerson'))
            $operator->contactPerson = $request->contactPerson;
        if ($request->has('businessEmail'))
            $operator->businessEmail = $request->businessEmail;
        if ($request->has('contactNumber'))
            $operator->contactNumber = $request->contactNumber;
        if ($request->has('companyAddress'))
            $operator->companyAddress = $request->companyAddress;
        if ($request->has('operatorCode'))
            $operator->operatorCode = $request->operatorCode;
        if ($request->has('operatorAbout'))
            $operator->operatorAbout = $request->operatorAbout;
        if ($request->has('businessType'))
            $operator->businessType = $request->businessType;
        if ($request->has('typeDescription'))
            $operator->typeDescription = $request->typeDescription;
        if ($request->has('status'))
            $operator->status = $request->status;

        $operator->save();

        $loggedInUser = \Auth::user();
        if ($loggedInUser->hasRole("Admin")) {
            $operatorUser = $operator->user;
            if ($request->has("city")) {
                $operatorUser->cityId = $request->city;
            }
            if ($request->has("status")) {
                $operatorUser->status = $request->status;
                if (!$request->status) {
                    $staff = Staff::with("user")->where("staffable_id", $operator->id)->where("staffable_type", "Operator")->get();
                    $totalStaff = count($staff);
                    for ($s = 0; $s < $totalStaff; $s++) {
                        $staffUser = $staff[$s]->user;
                        $staffUser->status = $request->status;
                        $staffUser->save();
                    }
                }
            }
            $operatorUser->save();
        }
        /*  $user = array();
        if($request->has('name'))
            $user['name'] = $request->name;
        if($request->has('branchId'))
            $user['branchId'] = $request->branchId; */
        /* if($request->has('password'))
            $user['password'] = Hash::make($request->password);*/
        //return $this->json(['user'=>$user],200);

        //$operator->user()->update($user);
        return $this->json(["data" => $operator], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
