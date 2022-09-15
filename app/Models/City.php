<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'city';
    protected $primaryKey = 'city_id';
    public function orders()
    {
        return $this->hasMany(QuotationOrder::class, 'cityId');
    }
    public function users()
    {
        return $this->hasMany(City::class, 'cityId');
    }
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'city_id', 'cityId');
    }
    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'cityId');
    }
    public function itineraryTemplates()
    {
        return $this->hasMany(itineraryTemplate::class, 'cityId');
    }
}
