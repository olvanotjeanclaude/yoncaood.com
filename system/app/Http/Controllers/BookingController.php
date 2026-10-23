<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\ContactInformation;
use App\Models\PassengerInformation;
use App\Models\Location;

class BookingController extends Controller
{
	public function saveBooking(Request $request)
	{
		$amount = $request->input('amount');
		$origin = $request->input('origin');
		$destination = $request->input('destination');
		$travelId = $request->input('travelId');

		$contactInformation = ContactInformation::create([
			'email' => $request->input('email'),
			'mobile_number' => $request->input('mobileNumber'),
		]);

		$orderNumber = now()->format('YmdHis') . mt_rand(1000, 9999);

		if ($request->has('ad') && is_array($request->input('ad'))) {
			$lastOrderId = null;

			foreach ($request->input('ad') as $key => $ad) {
				$passengerInformation = PassengerInformation::create([
					'contact_information_id' => $contactInformation->id,
					'ad' => $ad,
					'soyad' => $request->input('soyad')[$key],
					'title' => $request->input('title')[$key],
					'passaportno' => $request->input('passaportno')[$key],
					'origin' => $origin,
					'destination' => $destination,
					'travel_id' => $travelId,
					'orderid' => $orderNumber,
					'seat' => $request->input('seat')[$key],
					'gender' => $request->input('gender')[$key],
					'price' => $amount,
					'travel_date' => session("locationdate"),
				]);

				// Update the variable with the ID of the last created record
				$lastOrderId = $passengerInformation->orderid;
			}
			

			$response = Http::asForm()->post('https://epg.dskbank.bg/payment/rest/register.do', [
				'userName' => 'yoncaood-api',
				'password' => '_WY563c7',
				'orderNumber' => $lastOrderId,
				'amount' => $amount * 100,
				'returnUrl' => 'https://www.yoncaood.com/',
			]);

			if ($response->successful()) {
				$data = $response->json();

				return redirect($data['formUrl']);
			}

			$error = $response->status();

			return $response->json();
		}

		abort(404);
	}

	public function getTicket($orderId)
	{
		$passenger  =  PassengerInformation::where("orderid", $orderId)->firstOrFail();
		$passengers = PassengerInformation::with("contact")->where("orderid", $orderId)->get();
		$origin  = Location::where("code", $passenger->origin)->first();
		$destination  = Location::where("code", $passenger->destination)->first();

		return view("site.success-payment", compact("passengers", "origin", "destination"));
	}
}
