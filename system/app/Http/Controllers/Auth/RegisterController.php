<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
        ]);
    }

    public function create(Request $request)
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('GRSECRET'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        if (!$response->json('success')) {
            // return response()->json(['errors' => 'reCAPTCHA doğrulaması başarısız.'], 422);
        }

        $validated = $request->validate([
            'phone' => ['required','string','unique:users,phone','regex:/^5\d{9}$/'],
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'gender' => ['required', Rule::in(['man', 'woman'])],
            'identity' => 'required|numeric|digits:11|unique:users,identity',
            'password' => 'required|string|min:6',
        ]);

        $smsPassword = random_int(1111, 9999);

        $user = User::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'phone' => $validated['phone'],
            'member_no' => $validated['phone'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'identity' => $validated['identity'],
            'password' => Hash::make($validated['password']),
            'sms_password' => $smsPassword,
        ]);

        $this->sendSms($user, $smsPassword);

        return response()->json(['message' => __('auth.sms_verification_sent')]);
    }

    private function sendSms(User $user, $smsPassword)
    {
        $adSoyad = $user->name . ' ' . $user->surname;
        $smsMessage = "SN. " . transsmallbig($adSoyad) . ", LUKS YONCAOOD UYELIGINIZI ONAYLAMAK ICIN AKTIVASYON KODUNUZ:$smsPassword";

        return sendSmsKobikom("REGISTERACTIVITESMS", $user->phone, $smsMessage);
    }

    public function confirmPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'smsVerificationCode' => 'required|digits:4',
        ]);

        $phone = $request->input('phone');
        $code = $request->input('smsVerificationCode');

        $user = User::where('phone', $phone)->first();

        if ($user && $user->sms_password == $code) {
            $user->update([ 'phone_confirmed' => true]);

            Auth::login($user);

            return response()->json([
                'message' => __('validation.phone_confirmed'),
            ]);
        }

        return response()->json([
            'message' => __('validation.sms_code_invalid'),
        ], 422);
    }
}
