<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotationId',
        'userId',
        'statusId'
    ];
}
