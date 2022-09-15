<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;
    public function messageable()
    {
        return $this->morphTo();
    }
    public function recipient()
    {
        return $this->belongsTo(User::class, "recipientId");
    }
}
