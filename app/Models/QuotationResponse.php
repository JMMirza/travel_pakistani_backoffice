<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationResponse extends Model
{

    protected $dates = [

        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'date:d M, Y H:i',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotationId', 'id');
    }
}
