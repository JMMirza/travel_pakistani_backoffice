<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class QuotationOrder extends Model
{
    protected $appends = ["planned_cities"];
    public function city()
    {
        return $this->belongsTo(City::class, "cityId");
    }
    public function hotel()
    {
        return $this->belongsTo(ClientHotel::class, "hotelId");
    }
    public function transfer()
    {
        return $this->belongsTo(ClientTransfer::class, "hotelId");
    }
    public function user()
    {
        return $this->belongsTo(User::class, "userId");
    }
    public function getPlannedCitiesAttribute()
    {
        $cities = $this->attributes["citiesToVisit"];
        $citiesToVisit = array();
        if ($cities) {
            $cities = json_decode($cities);
            $citiesToVisit = City::whereIn("city_id", $cities)->get();
        }
        return $citiesToVisit;
    }
}
