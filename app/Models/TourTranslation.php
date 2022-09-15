<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourTranslation extends Model
{
    public function language()
    {
        return $this->belongsTo(SystemLanguage::class, 'languageId');
    }
    public function tour()
    {
        return $this->belongsTo(Tour::class, 'tourId');
    }
}
