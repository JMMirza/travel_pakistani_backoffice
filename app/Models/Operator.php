<?php

namespace App\Models;

use App\Models\User;
use App\Models\Admin\Staff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operator extends Model
{
    use SoftDeletes;

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }
    public function bank()
    {
        return $this->morphOne(BanksDetail::class, 'accountable');
    }
    public function staff()
    {
        return $this->morphOne(Staff::class, 'staffable');
    }
}
