<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class CustomTemplate extends Model
{
    use SoftDeletes;
    protected $fillable = ["userId", "title", "description", "templateType"];

    public function user()
    {
        return $this->belongsTo(User::class, "userId");
    }
}
