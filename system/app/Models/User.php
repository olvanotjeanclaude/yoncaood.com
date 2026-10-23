<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
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
        'name',
        'surname',
        'email',
        'phone',
        'identity',
        'gender',
        'password',
        'sms_password',
        'phone_confirmed',
        'member_no'
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
    ];

    public function get_roles()
    {
        $roles = [];
        foreach ($this->getRoleNames() as $key => $role) {
            $roles[$key] = $role;
        }

        return $roles;
    }

    public function myTickets()
    {
        $user = auth()->user();
        $phone = ltrim($user->phone, '0');

        $tickets = Travel::getTickets()->where('T.TELEPHONE', 'LIKE', "%$phone");

        return $tickets;

        // return $this->hasMany(PassengerInformation::class)
        //     ->where("status", 1)
        //     ->whereNotNull("pnr_number");
    }


    public static function check(string $phone, ?string $password = null)
    {
        $user = self::where('phone', $phone)->first();

        if ($user) {
            if ($password && !Hash::check($password, $user->password)) {
                return null;
            }

            return $user;
        }

        $query = DB::table('LUKSYONCA.MEMBER')->where('CEPNUMBER', $phone);

        if ($password) {
            $query->where('PASSWORD', $password);
        }

        $member = $query->first();

        if (!$member) {
            return null;
        }

        return self::updateOrCreate(
            ['phone' => $member->CEPNUMBER],
            [
                'name' => $member->MEMBERNAME ?? '',
                'surname' => $member->MEMBERSURNAME ?? '',
                'identity' => $member->TCNUMBER ?? '',
                'gender' => $member->GENDER === 'E' ? 'man' : 'woman',
                'phone_confirmed' => $member->AKTIF ? 1 : 0,
                'email' => $member->EMAIL,
                'password' => Hash::make($member->PASSWORD ?? Str::random(8)),
                'created_at' => now(),
                'updated_at' => $member->UPDATETIME ?? now(),
            ]
        );
    }
}
