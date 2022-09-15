<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Branch extends Model
{
    //use SoftDeletes;
    public function site()
    {
        return $this->belongsTo(SiteCredential::class, "siteId");
    }
    public function users()
    {
        return $this->hasMany(User::class, "branchId");
    }
}
