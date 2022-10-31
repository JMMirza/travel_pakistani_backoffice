<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationChat extends Model
{

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, 'quotationId', 'id');
    }
}
