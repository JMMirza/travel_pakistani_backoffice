<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandmarkItemImage extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'landmark_item_images';
    protected $primaryKey = 'id';

    public function landmark()
    {
        return $this->belongsTo(LandmarkItem::class, "id");
    }
}
