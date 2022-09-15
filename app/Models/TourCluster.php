<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourCluster extends Model
{
    use SoftDeletes;
    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tourId');
    }
}
