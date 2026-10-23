<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\PaymentSuccess;
use App\Models\PassengerInformation;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function email()
    {
        $customer = PassengerInformation::first();

        try {
            Mail::to("olvanot@mydata.com.tr")->send(new PaymentSuccess($customer));
        } catch (Exception $ext) {
            return response()->json([
                'message' => 'Mail gönderilemedi',
                'error' => $ext->getMessage()
            ], 500);
        }
    }
}
