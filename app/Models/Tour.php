<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, "userId");
    }
    public function category()
    {
        return $this->belongsTo(Category::class, "categoryId");
    }
    public function tourTranslations()
    {
        return $this->hasMany(TourTranslation::class, 'tourId');
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function clusters()
    {
        return $this->hasMany(TourCluster::class, 'tourId');
    }
}
