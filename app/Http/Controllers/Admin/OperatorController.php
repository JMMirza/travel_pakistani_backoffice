<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
        //
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
