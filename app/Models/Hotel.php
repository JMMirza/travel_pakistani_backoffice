<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hotel extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'cityId',
        'hotelName',
        'hotelAddress',
        'email',
        'website',
        'contactNo',
        'lat',
        'long'
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
        return $this->hasMany(QuotationOrder::class, "userId");
    }
}
