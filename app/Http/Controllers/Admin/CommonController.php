<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
