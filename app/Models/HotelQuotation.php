<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelQuotation extends Model
{
    use SoftDeletes;
    public function quotation()
    {
        return $this->belongsTo(Quotation::class, "quotationId");
    }
}
