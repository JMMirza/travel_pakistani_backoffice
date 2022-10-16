<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class ItineraryTemplate extends Model
{
    use SoftDeletes;

    protected $dates = [

        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'date:d M, Y H:i',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "userId");
    }

    public function category()
    {
        return $this->belongsTo(Category::class, "categoryId");
    }

    public function city()
    {
        return $this->belongsTo(City::class, "cityId");
    }

    public function templateDetails()
    {
        return $this->hasMany(ItineraryTemplateDetail::class, 'templateId', 'id');
    }
}
