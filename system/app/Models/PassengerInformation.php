<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class PassengerInformation extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contact_information_id',
        'orderid',
        'travel_id',
        'ad',
        'soyad',
        'title',
        'user_id',
        'gender',
        'passaportno',
        'travel_date',
        'origin',
        'destination',
        'seat',
        'price',
        'bank_order_id',
        'state',
        'state_definition',
        'pnr_number',
        'bank_response',
        'sale_response',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'travel_date' => 'datetime:d.m.Y H:i',
    ];

    public function get_roles()
    {
        $roles = [];
        foreach ($this->getRoleNames() as $key => $role) {
            $roles[$key] = $role;
        }

        return $roles;
    }

    public function getFullNameAttribute()
    {
        return "{$this->ad} {$this->soyad}";
    }

    public function contact()
    {
        return $this->belongsTo(ContactInformation::class, "contact_information_id");
    }

    public function originLocation()
    {
        return $this->belongsTo(Location::class, 'origin', 'code');
    }

    public function destinationLocation()
    {
        return $this->belongsTo(Location::class, 'destination', 'code');
    }
}
