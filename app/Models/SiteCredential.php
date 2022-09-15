<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteCredential extends Model
{
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }
}
