<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationImage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'image',
        'quotationId',
        'version'
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, "quotationId");
    }
}
