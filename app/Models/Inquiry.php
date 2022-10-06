<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\City;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use SoftDeletes;
    //protected $appends = ['planned_cities'];
    protected $guarded = [];
    protected $dates = [

        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'date:d M, Y H:i',
    ];

    public function city()
    {
        return $this->hasOne(City::class, 'city_id', 'cityId');
    }
    public function getPlannedCitiesAttribute()
    {
        $citiesToVisit = $this->attributes['citiesToVisit'];
        $citiesToVisit = json_decode($citiesToVisit);
        $cities = City::selectRaw('GROUP_CONCAT(title) as citiesToVisit')->whereIn('city_id', $citiesToVisit)->get();
        $cities = $cities[0]->citiesToVisit;
        return $cities;
    }
    public function quotations()
    {
        return $this->hasMany(Quotation::class, "inquiryId");
    }
    public function user()
    {
        return $this->belongsTo(user::class, "assignedTo");
    }
    public function createdByUser()
    {
        return $this->belongsTo(user::class, "createdBy");
    }
}
