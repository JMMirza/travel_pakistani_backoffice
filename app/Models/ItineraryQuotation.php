<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Landmark;

class ItineraryQuotation extends Model
{
    use SoftDeletes;
    protected $appends = ["itineraryLandmarks"];

    public function quotationable()
    {
        return $this->morphTo();
    }

    public function getItineraryLandmarksAttribute()
    {
        $landmarksAll = array();
        if (isset($this->attributes['landmarks'])) {
            $landmarks = $this->attributes['landmarks'];
            $landmarksAll = array();
            if ($landmarks != NULL && $landmarks != "") {
                $landmarks = json_decode($landmarks);
                //return $landmarks;
                $landmarks = Landmark::whereIn('id', $landmarks)->get();
                $totalLandmarks = count($landmarks);
                for ($i = 0; $i < $totalLandmarks; $i++) {
                    $landmarksAll[$i]["id"] = $landmarks[$i]->id;
                    $landmarksAll[$i]["title"] = $landmarks[$i]->title;
                    $landmarksAll[$i]["masterId"] = $landmarks[$i]->masterId;
                }
            }
        }
        return $landmarksAll;
    }
}
