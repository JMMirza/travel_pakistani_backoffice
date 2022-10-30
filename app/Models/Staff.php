<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use SoftDeletes;
    public function staffable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }
    public function reportsToUser()
    {
        return $this->belongsTo(User::class, "reportsTo", 'id');
    }
}
