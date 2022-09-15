<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandmarkCategory extends Model
{
    use SoftDeletes;
    public function subCategories()
    {
        return $this->hasMany(Self::class, "parentId");
    }
    public function landmarks()
    {
        return $this->hasMany(Landmark::class, "categoryId");
    }
}
