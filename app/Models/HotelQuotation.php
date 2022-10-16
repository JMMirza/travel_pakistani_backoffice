<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelQuotation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quotationId',
        'hotelName',
        'checkIn',
        'checkout',
        'nights',
        'instructions',
        'markupValue',
        'markupType',
        'unitCost',
        'totalUnits',
        'hotelCost',
        'hotelMarkupAmount',
        'hotelSales',
        'versionNo',
    ];

    protected $dates = [
        'checkIn',
        'checkout',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, "quotationId");
    }
}
