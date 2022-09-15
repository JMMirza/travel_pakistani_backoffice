<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientTransfer extends Model
{
    use SoftDeletes;
    public function orders()
    {
        return $this->hasMany(QuotationOrder::class, "orderId");
    }
}
