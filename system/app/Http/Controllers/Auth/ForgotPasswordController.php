<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;

    public function sendResetLinkPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'first_letter' => 'required|string|size:1',
        ]);

        $user = User::check($request->phone);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => __('auth.forgot_password.error_message')
            ]);
        }

        if (strtoupper(substr($user->name, 0, 1)) !== strtoupper($request->first_letter)) {
            return response()->json([
                'success' => false,
                'message' => __('auth.forgot_password.error_message')
            ]);
        }


        $token = rand(100000, 999999);

        DB::table('phone_password_resets')->updateOrInsert(
            ['phone' => $request->phone],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        $page = '/password/forget';
        $pPhone = $request->phone;
        $pMessage = "Your password reset code is: {$token}";
        $pMessage = __('auth.forgot_password.sms_message', ['token' => $token]);

        $encryptedToken = Crypt::encryptString($token);

        sendSmsKobikom($page, $pPhone, $pMessage);


        return response()->json([
            'success' => true,
            'message' => __('auth.forgot_password.success_message'),
            'reset_url' => route("password.reset", ['token' => $encryptedToken])
            // 'token' => $token 
        ]);
    }

    public function showResetForm($cryptedToken)
    {
        try {
            $token = Crypt::decryptString($cryptedToken);
        } catch (\Exception $e) {
            abort(404);
        }

        $reset = DB::table('phone_password_resets')
            ->where('token', $token)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->first();

        if (!$reset) {
            abort(404);
        }

        return view('site.phone_reset', [
            'token' => $cryptedToken,
            'phone' => $reset->phone,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'token' => 'required|string',
            'code'  => 'required|digits:6',
            'password' => 'required|min:6|confirmed'
        ]);

        try {
            Crypt::decryptString($request->token);
        } catch (\Exception $e) {
            abort(404);
        }

        $record = DB::table('phone_password_resets')
            ->where('phone', $request->phone)
            ->where('token', $request->code)
            ->where('created_at', '>=', now()->subMinutes(15))
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => __('auth.forgot_password.code_invalid')
            ]);
        }

        $user = User::where('phone', $request->phone)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        Auth::login($user);

        DB::table('LUKSYONCA.MEMBER')->where('CEPNUMBER', $request->phone)->update(["PASSWORD" => $request->password]);

        DB::table('phone_password_resets')->where('phone', $request->phone)->delete();

        return response()->json([
            'success' => true,
            'message' => __('auth.forgot_password.password_changed'),
            'redirect_url' => route("index")
        ]);
    }
}
