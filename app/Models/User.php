<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'branchId',
        'phone',
        'cityId',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->morphOne(Self::class, 'userable');
    }

    // public function staff()
    // {
    //     return $this->morphOne(Staff::class, 'staffable');
    // }

    public function reportingStaff()
    {
        return $this->hasMany(Staff::class, 'reportsTo');
    }

    public function tours()
    {
        return $this->hasMany(Tour::class, "userId");
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class, 'userId');
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'assignedTo');
    }

    public function createdInquiries()
    {
        return $this->hasMany(Inquiry::class, 'createdBy');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, "branchId");
    }

    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function banks()
    {
        return $this->hasMany(BanksDetail::class, "userId");
    }

    public function orders()
    {
        return $this->hasMany(QuotationOrder::class, "userId");
    }

    public function city()
    {
        return $this->belongsTo(City::class, "cityId", 'city_id');
    }
}
