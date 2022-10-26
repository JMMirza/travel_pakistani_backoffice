<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quotationId',
        'title',
        'description',
        'type',
        'versionNo',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, "quotationId");
    }
}
