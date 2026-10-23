<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // custom logout function
    // redirect to login page
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new Response('', 204)
            : redirect('/login');
    }

    public function login(Request $request)
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('GRSECRET'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        if (!$response->json('success')) {
            return response()->json(['errors' => 'reCAPTCHA doğrulaması başarısız.'], 422);
        }

        $request->validate([
            'phone' => ['required'],
            'password' => ['required'],
        ]);

        $member = DB::table('LUKSYONCA.MEMBER')
            ->where("CEPNUMBER", $request->phone)
            ->where("PASSWORD", $request->password)
            ->first();

        if (is_null($member)) {
            return response()->json(['errors' => 'Telefon veya şifre geçersiz.'], 422);
        }

        $user = User::updateOrCreate(
            [
                'phone' => $member->CEPNUMBER,
            ],
            [
                'name' => $member->MEMBERNAME ?? '',
                'surname' => $member->MEMBERSURNAME ?? '',
                'identity' => $member->TCNUMBER ?? '',
                'gender' => $member->GENDER == "E" ? "man" : "woman",
                'phone_confirmed' => $member->AKTIF ? 1 : 0,
                'email' => $member->EMAIL,
                'password' => Hash::make($member->PASSWORD),
                'member_no' => $member->MEMBERNO,
                'created_at' => now(),
                'updated_at' => $member->UPDATETIME ?? now(),
            ]
        );

        Auth::loginUsingId($user->id);

        // Auth::loginUsingId(31);

        return response()->json([
            'message' => 'Login successful',
            'redirect' => '/biletlerim'
        ]);
    }
}
