<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function scopeOfId($query, $id)
    {
        return $query->where('id', $id);
    }
}
