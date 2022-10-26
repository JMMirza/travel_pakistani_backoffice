<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceQuotation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'quotationId',
        'description',
        'serviceDateType',
        'serviceDate',
        'serviceEndDate',
        'serviceType',
        'calculateByDays',
        'totalDays',
        'instructions',
        'unitCost',
        'totalUnits',
        'markupValue',
        'markupType',
        'serviceCost',
        'serviceMarkupAmount',
        'serviceSales',
        'versionNo',
    ];

    protected $dates = [
        'serviceDate',
        'serviceEndDate',
        'created_at',
        'updated_at',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class, "quotationId");
    }
}
