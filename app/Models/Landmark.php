<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Landmark extends Model
{
    protected $dates = [

        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'date:d M, Y H:i',
    ];
    protected $primaryKey = 'id';
    public function landmarkContent()
    {
        return $this->hasMany(LandmarkContent::class, "landmarkId");
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function category()
    {
        return $this->belongsTo(LandmarkCategory::class, 'categoryId');
    }
}
