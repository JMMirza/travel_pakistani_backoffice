<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandmarkContent extends Model
{
    protected $primaryKey = 'id';

    public function landmark()
    {
        return $this->belongsTo(Landmark::class, "landmarkId");
    }
}
