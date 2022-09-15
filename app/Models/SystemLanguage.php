<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLanguage extends Model
{
    //use softDeletes();
    public function tourTranslations()
    {
        return $this->hasMany(tourTranslation::class);
    }
}
