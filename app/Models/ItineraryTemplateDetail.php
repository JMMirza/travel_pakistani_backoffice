<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class ItineraryTemplateDetail extends Model
{
    use SoftDeletes;

    public function template()
    {
        return $this->belongsTo(ItineraryTemplate::class, "templateId");
    }
    public function city()
    {
        return $this->belongsTo(City::class, "cityId");
    }
}
