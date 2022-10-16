<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationNote extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, "quotationId");
    }
}
