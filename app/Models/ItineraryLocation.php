<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryLocation extends Model
{
    public function scopeOfItineraryId($query, $itnId)
    {
        return $query->where('itineraryId', $itnId);
    }
}
