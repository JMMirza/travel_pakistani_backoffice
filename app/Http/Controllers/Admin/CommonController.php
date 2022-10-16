<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BanksDetail;
use App\Models\City;
use Illuminate\Http\Request;
use DB;

class CommonController extends Controller
{
    public function citiesSite()
    {
        $connection = DB::connection('mysql2');
        $data = $connection->select("select * from city");
        return $data;
    }

    public function allCities()
    {
        $cities = City::orderBy('title')->get();
        dd($cities);
        return $this->json(['data' => $cities], 200);
    }

    public function profile()
    {
        $cities = City::all();
        $bank_details = BanksDetail::where('userId', \Auth::user()->id)->get();
        return view('profile.index', ['cities' => $cities, 'bank_details' => $bank_details]);
    }
}
