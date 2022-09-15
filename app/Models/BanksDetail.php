<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class BanksDetail extends Model
{
    use SoftDeletes;
    protected $fillable = ["userId", "accountNo", "accountTitle", "IBAN", "swiftCode", "bankName", "bankAddress", "bankPhone"];

    public function user()
    {
        return $this->belongsTo(User::class, "userId");
    }
}
