<?php

namespace App\Http\Controllers;

use App\Services\Mydata\MydataService;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Location;
use App\Models\Travel;
use Illuminate\Support\Facades\Log;

class FrontendController extends Controller
{
	private MydataService $mydata;

	public function __construct(MydataService $mydata)
	{
		$this->mydata = $mydata;
	}

	public function index(): View
	{

		return view('site.home');
	}

	public function seferSorgula($origin, $destination, $travelDate)
	{
		// return back();
		try {
			Location::where("code", $destination)->firstOrFail();
			Location::where("code", $origin)->firstOrFail();

			$carbonDate = Carbon::createFromFormat("m-d-Y", $travelDate)->startOfDay();

			$formattedDate = $carbonDate->format("Y-m-d");

			$travels = $this->mydata->getTravels($origin, $destination, $formattedDate);


			// $travels = Travel::where("departure", $origin)
			// 	->where("arrival", $destination)
			// 	->whereDate("travel_date", $formattedDate)
			// 	->get();


			return view('site.seyehatsonuc', [
				'origin' => $origin,
				'destination' => $destination,
				'travelDate' => $travelDate,
				'travels' => $travels,
			]);
		} catch (\Throwable $th) {
			Log::warning("Error: {$th->getMessage()}");
			abort(404, 'Sorry, something went wrong.');
		}
	}

	public function seyehatsec(Request $request)
	{
		$validated = $request->validate([
			'travelId'     => 'required|exists:travels,travel_id',
			'origin'       => 'required|exists:travels,departure',
			'destination'  => 'required|exists:travels,arrival|different:origin',
			'travel_date'  => 'required|date',
		]);

		$travelId     = $validated['travelId'];
		$origin       = $validated['origin'];
		$destination  = $validated['destination'];
		$locationdate = $validated['travel_date'];

		$travel = Travel::where('travel_id', $travelId)
			->where('departure', $origin)
			->where('arrival', $destination)
			->where('travel_date', $locationdate)
			->firstOrFail();

		$seats = $this->mydata->getSeatLayout($travelId, $origin, $destination);

		// dd($seats);
		// $fiyatx = $this->mydata->getExchangeRate("BGN");

		$internetprice = $seats['line'][0]['seat'][0]['internetprice'] ?? 0; // Örnek olarak '1.000,00' şeklinde olduğunu varsayalım

		if ($internetprice) {
			// Önce nokta ve virgül karakterlerini kaldırarak sadece sayısal değeri alalım
			$cleaned_price = preg_replace("/[^0-9]/", "", $internetprice);

			// Şimdi temizlenmiş değeri float (ondalıklı sayı) olarak dönüştürelim
			$price = floatval($cleaned_price) / 100;

			// $price = round($price / $fiyatx);
			session(["locationdate" => $locationdate]);

			return view('site.rezervasyon', compact('seats', 'origin', 'destination', 'travelId', 'price', 'locationdate'));
		}

		return redirect("/");
	}

	public function aboutus()
	{
		return view('site.aboutus');
	}

	public function offices()
	{
		return view('site.offices');
	}

	public function iletisim()
	{
		return view('site.iletisim');
	}

	public function checkPnr(Request $request)
	{
		$request->validate(
			[
				"phone" => ["required", "digits_between:10,11"],
				"pnr_code" => ["required", "digits_between:6,9"]
			],
			[
				"phone.required" => "Telefon numarası gerekli.",
				"phone.digits_between" => "Telefon numarası 10 ile 11 haneli olmalıdır.",
				"pnr_code.required" => "PNR kodu gerekli.",
				"pnr_code.digits_between" => "PNR kodu 6 ile 9 haneli olmalıdır.",
			]
		);

		$ticketsExist  = Travel::getTickets()
			->where('T.TELEPHONE', 'LIKE', "%{$request->phone}")
			->where("T.ID", $request->pnr_code)
			->exists();

		if ($request->ajax()) {
			if ($ticketsExist) {
				return response()->json([
					'success' => true,
					'message' => 'Ticket found.'
				]);
			}

			return response()->json([
				'success' => false,
				'message' => __("messages.ticket_not_found")
			],404);
		}

		$ticket  = Travel::getTickets()
			->where('T.TELEPHONE', 'LIKE', "%{$request->phone}")
			->where("T.ID", $request->pnr_code)
			->first();

		abort_if(is_null($ticket), 404);

		return view("site.pnr.show", ["ticket" => $ticket]);
	}

	public function getSeatLayout($travelId, $destination, $originx)
	{
		Location::where("code", $destination)->firstOrFail();
		Location::where("code", $originx)->firstOrFail();

		$seats = $this->mydata->getSeatLayout($travelId, $destination, $originx);


		if (count($seats)) {
			return response()->json($seats);
		}

		return response()->json(['error' => 'Koltuk düzeni getirme hatası!'], 500);
	}
}
