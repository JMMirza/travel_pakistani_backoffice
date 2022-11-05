<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotationInvoice extends Model
{
    protected $fillable = [

        'description',
        'quotationId',
        'quotationVersion',
        'invoiceDate',
        'pdf',
        'dueAmount',
        'totalAmount',
        'remainingAmount',
        'dueDate',
        'status',
    ];
}
