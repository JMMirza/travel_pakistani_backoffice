<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'abbreviation'
    ];

    protected $dates = [

        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'date:d M, Y H:i',
    ];

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
