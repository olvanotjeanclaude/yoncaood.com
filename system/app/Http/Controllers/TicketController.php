<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;
use Illuminate\Http\Request;
use  App\Services\Mydata\MydataService;

class TicketController extends Controller
{
	public function satinal(Request $request, MydataService $mydata)
	{
		$validated = $request->validate([
			'travelId'                => 'required|exists:travels,travel_id',
			'origin'                  => 'required|exists:travels,departure',
			'destination'             => 'required|exists:travels,arrival|different:origin',
			'totalAmount'             => 'required|numeric|min:1',
			'selectedSeatsAndGenders' => 'required|json',
		]);

		$travelId      = $validated['travelId'];
		$origin        = $validated['origin'];
		$destination   = $validated['destination'];
		$totalAmount   = $validated['totalAmount'];
		
		$selectedSeatsAndGenders = json_decode($validated['selectedSeatsAndGenders'], true);

		$nationalities = $mydata->getNationalities();


		return view('site.odeme', [
			'totalAmount'             => $totalAmount,
			'origin'                  => $origin,
			'destination'             => $destination,
			'travelId'                => $travelId,
			'selectedSeatsAndGenders' => $selectedSeatsAndGenders,
			'nationalities'           => $nationalities,
		]);
	}
}
