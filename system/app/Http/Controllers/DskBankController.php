<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccess;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\ContactInformation;
use App\Models\PassengerInformation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\Controller;
use App\Models\Travel;
use App\Models\User;
use App\Services\Mydata\MydataService;

class DskBankController extends Controller
{
	private MydataService $mydata;

	// Mydata account
	// $userName = "yoncaood.com-api";
	// $password = "mjufp-P5";

	// Önceki hesap
	private $userName = "yoncaood-api";
	private	$password = "_WY563c7";

	public function __construct(MydataService $mydata)
	{
		$this->mydata = $mydata;
	}

	public function saveBooking(Request $request)
	{
		$request->validate([
			'email' => 'required|email',
			'mobileNumber' => ['required', 'digits:10', 'regex:/^5\d{9}$/'], // 10 digits starting with 5

			'origin' => 'required|string',
			'destination' => 'required|string',
			'travelId' => 'required|integer',
			'amount' => 'required|numeric|min:0',

			'pax' => 'required|array|min:1',

			'pax.*.ad' => 'required|string|max:255',
			'pax.*.soyad' => 'required|string|max:255',
			'pax.*.title' => 'required|string|max:255',
			'pax.*.passaportno' => 'required|string|max:50',
			'pax.*.seat' => 'required|string|max:10',
			'pax.*.gender' => 'required|in:man,woman',
		]);

		$amount = $request->input('amount');


		$origin = $request->input('origin');
		$destination = $request->input('destination');
		$travelId = $request->input('travelId');

		$contactInformation = ContactInformation::updateOrCreate(
			['email' => $request->input('email')],
			['mobile_number' => $request->input('mobileNumber')]
		);

		$orderNumber = now()->format('YmdHis') . mt_rand(1000, 9999);

		if (session("locationdate") && $request->has('pax') && is_array($request->input('pax'))) {
			$passengers = $request->input('pax');
			$count = count($passengers);
			$lastOrderId = null;
			$user = User::where("phone", $request->input('mobileNumber'))->first();

			foreach ($passengers as $passenger) {
				$passengerInformation = PassengerInformation::create([
					'contact_information_id' => $contactInformation->id,
					'ad' => $passenger['ad'],
					'soyad' => $passenger['soyad'],
					'title' => $passenger['title'],
					'passaportno' => $passenger['passaportno'],
					'seat' => $passenger['seat'],
					'gender' => $passenger['gender'],

					'origin' => $origin,
					'destination' => $destination,
					'travel_id' => $travelId,
					'orderid' => $orderNumber,
					'price' => $amount / $count,
					'travel_date' => session("locationdate"),
					'user_id' => $user ? $user->id : null
				]);

				$lastOrderId = $passengerInformation->orderid;
			}

			return $this->initPayment($lastOrderId, $amount);
		}

		abort(404);
	}

	public function initPayment($orderNumber, $amount)
	{
		// $amount = 0.1;

		$amount = intval($amount * 100);

		$returnUrl = route('dskbank.success', ['order_id' => $orderNumber]);
		$failUrl   = route('dskbank.fail', ['order_id' => $orderNumber]);

		$payload = [
			'userName'    => $this->userName,
			'password'    => $this->password,
			'orderNumber' => $orderNumber,
			'amount'      => $amount,
			'returnUrl'   => $returnUrl,
			'failUrl'     => $failUrl,
		];

		try {
			$response = Http::asForm()->post('https://epg.dskbank.bg/payment/rest/register.do', $payload);

			$data = $response->json();

			if (isset($data['formUrl'])) {
				PassengerInformation::where('orderid', $orderNumber)->update(['bank_order_id' => $data['orderId']]);

				if (request("ajax")) return response()->json($data);

				return redirect($data['formUrl']); // Redirect to DSK payment page
			}

			return response()->json(['error' => 'Failed to register order', 'details' => $data], 400);
		} catch (\Exception $e) {
			Log::error('DSK Payment Exception', ['message' => $e->getMessage()]);
			return response()->json(['error' => 'Server error'], 500);
		}
	}

	public function pay(Request $request)
	{
		$request->validate([
			'orderNumber' => 'required|string',
			'amount' => 'required|numeric|min:0.01',
		]);

		return $this->initPayment($request->input('orderNumber'), $request->input('amount'));
	}

	public function successCallback(Request $request)
	{
		$orderId = request("order_id");

		PassengerInformation::where("orderid", $orderId)->firstOrFail();

		return redirect("/bilet-bilgileri/$orderId");
	}

	public function failCallback(Request $request)
	{
		// $params = [
		// 	"_token" => "sW51nmrGLHNy5unZ3OB6C05t0lzbCPvjVx5VzerB",
		// 	"selectedSeats" => "[object Object],[object Object],[object Object]", // likely needs decoding or correction
		// 	"travelId" => "15781",
		// 	"origin" => "9902",
		// 	"destination" => "3400",
		// 	"totalAmount" => "156",
		// 	"selectedSeatsAndGenders" => '[{"seatNo":"01","gender":"man"},{"seatNo":"04","gender":"man"},{"seatNo":"07","gender":"man"}]',
		// ];

		$orderId = request("order_id");

		$passenger = PassengerInformation::where("orderid", $orderId)->firstOrFail();
		$passengers =  PassengerInformation::with(["contact", "originLocation", "destinationLocation"])
			->where("orderid", $orderId)
			->get();

		$selectedSeats = $passengers->pluck("seat")->toArray();
		$totalAmount = $passengers->sum("price");

		$travelId = $passenger->travel_id;
		$selectedSeatsAndGenders = $passengers->map(function ($passenger) {
			return [
				"seatNo" => $passenger->seat,
				"gender" => $passenger->gender,
				"first_name" => $passenger->ad,
				"last_name" => $passenger->soyad,
				"passaportno" => $passenger->passaportno,
			];
		})->toArray();

		$contact = [
			"email" => $passenger->contact->email,
			"phone" => $passenger->contact->mobile_number,
		];

		$nationalities = $this->mydata->getNationalities();

		return view('site.odeme', [
			'contact' => $contact,
			'selectedSeats' => $selectedSeats,
			'totalAmount' => $totalAmount,
			'origin' => $passenger->origin,
			'destination' => $passenger->destination,
			'travelId' => $travelId,
			'selectedSeatsAndGenders' => $selectedSeatsAndGenders,
			'nationalities' => $nationalities,
			'error' => __('messages.payment_failed'),
		]);
	}

	public function cancelOrder($pnrNumber)
	{
		$ticket = Travel::getTickets()->where("T.ID", $pnrNumber)->first();
		$passenger = PassengerInformation::where("pnr_number", $pnrNumber)->first();

		if (!$ticket) {
			return response()->json([
				"success" => false,
				"message" => "Yanlış PNR numarası veya bilet zaten iptal edilmiş."
			]);
		}

		// dd($ticket, $passenger);

		$response = $this->mydata->cancelTicket(["pnrno" => $pnrNumber]);
		$isCancelled = $response["cancel"]["result"]["ticket"]["cancel"] ?? 0;

		Log::info('Ticket cancel response', [
			'pnr'        => $pnrNumber,
			'response'   => $response,
			'cancelled'  => $isCancelled,
		]);

		if ($isCancelled && $passenger) {
			$passenger->update([
				"state" => "I",
				"state_definition" => "IPTAL EDILDI"
			]);

			$refundedRes = $this->refund($passenger->bank_order_id, $passenger->price);
			$isRefunded = $refundedRes["errorCode"] == "0";
			// $orderStatus = $this->getOrderStatus($passenger->bank_order_id);

			Log::info('Refund result', [
				'pnr' => $pnrNumber,
				'order_id' => $passenger->bank_order_id,
				'amount' => $passenger->price,
				'success' => $refundedRes,
			]);

			if ($isRefunded) {
				$passenger->update(["state_definition" => "IADE EDILDI"]);
			}

			return response()->json([
				"success" => $isRefunded,
				"refunded" => $refundedRes,
				"message" => $isRefunded ? "Bilet iptal edildi ve ücret iade edildi." : "Bilet iptal edildi ancak iade işlemi başarısız oldu.",
			]);
		}

		return response()->json([
			"success"  => false,
			"message"  => "Bilet iptal edilemedi. Lütfen tekrar deneyin.",
		]);
	}

	public function refund($bankOrderId, $amount)
	{
		// $amount = 0.1;
		$payload = [
			'userName'    => $this->userName,
			'password'    => $this->password,
			'orderId'     => $bankOrderId,
			'amount'      => $amount * 100
		];

		$response = Http::asForm()->post('https://epg.dskbank.bg/payment/rest/refund.do', $payload);

		return $response->json();
	}

	public function getOrderStatus($bankOrderId)
	{
		$payload = [
			'userName'    => $this->userName,
			'password'    => $this->password,
			'orderId'     => $bankOrderId,
		];

		$response = Http::asForm()->post('https://epg.dskbank.bg/payment/rest/getOrderStatusExtended.do', $payload);

		return $response->json();
	}

	public function callback(Request $request): bool
	{
		try {
			Log::info($request->all());

			$orderNumber = $request->input('orderNumber');
			$status      = $request->input('status');
			$operation   = $request->input('operation');
			$mdOrder     = $request->input('mdOrder');

			if (!$orderNumber || !$operation) {
				Log::warning("Invalid callback input", $request->all());
				return false;
			}

			$orders = PassengerInformation::where('orderid', $orderNumber)->get();

			if ($orders->isEmpty()) {
				Log::warning("No matching orders found for orderNumber: {$orderNumber}");
				return false;
			}

			// Save bank order and raw response
			PassengerInformation::where('orderid', $orderNumber)->update([
				"bank_order_id" => $mdOrder,
				"bank_response" => json_encode($request->all()),
			]);

			// Check for valid deposited status
			if ($operation === "deposited") {
				if ($status == 1) {
					$response = $this->makeSale($orders);
					Log::info('MyData response', $response);

					PassengerInformation::where('orderid', $orderNumber)->update([
						"sale_response" => json_encode($response),
					]);

					if (($response["sales"]["result"]["error"] ?? 0) == "0") {
						$this->markSaleAsSuccess($orderNumber, $response);
						$this->sendSuccessEmail($orders->first());
						return true;
					}
				} else {
					// Fallback: error in payment or sale
					$this->markAsError($orderNumber);
				}
			}
		} catch (\Throwable $e) {
			Log::error('Callback processing failed: ' . $e->getMessage(), [
				'trace'   => $e->getTraceAsString(),
				'request' => $request->all(),
			]);
		}

		return true;
	}

	private function makeSale($orders): array
	{
		$first = $orders->first();

		$params = [
			'travelId'      => $first->travel_id,
			'departure'     => $first->origin,
			'arrival'       => $first->destination,
			'passengerName' => [],
			'telephone'     => [],
			'tcNo'          => [],
			'seats'         => [],
			'gender'        => [],
		];

		foreach ($orders as $order) {
			$params['passengerName'][] = $order->fullname;
			$params['telephone'][]     = $order->contact->mobile_number;
			$params['tcNo'][]          = $order->passaportno;
			$params['seats'][]         = sprintf('%02d', $order->seat);
			$params['gender'][]        = $order->gender === 'man' ? 'E' : ($order->gender === 'woman' ? 'B' : '');
		}

		foreach (['passengerName', 'telephone', 'tcNo', 'seats', 'gender'] as $field) {
			$params[$field] = implode(',', $params[$field]);
		}

		return $this->mydata->makeSale($params);
	}

	private function markSaleAsSuccess(string $orderNumber, array $response): void
	{
		if (!isset($response["sales"]["result"])) {
			return;
		}

		$results = $response["sales"]["result"];

		if (isset($results["ticket"])) {
			$results = [$results];
		}

		foreach ($results as $pax) {
			if (!isset($pax["ticket"])) {
				continue;
			}

			PassengerInformation::where('orderid', $orderNumber)
				->where("seat", $pax["ticket"]["seat"])
				->update([
					'status'           => 1,
					'state'            => "S",
					'state_definition' => "SATIS",
					'pnr_number'       => $pax["ticket"]["pnrno"]
				]);
		}
	}

	private function sendSuccessEmail($firstOrder): void
	{
		try {
			$email = $firstOrder->contact->email ?? null;

			if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
				Mail::to($email)->send(new PaymentSuccess($firstOrder->orderid));
			} else {
				Log::warning("Email not sent: invalid or missing email for order {$firstOrder->orderid}");
			}
		} catch (\Throwable $mailException) {
			Log::error("Email sending failed: " . $mailException->getMessage());
		}
	}

	private function markAsError(string $orderNumber): void
	{
		PassengerInformation::where('orderid', $orderNumber)->update([
			'state'            => "H",
			'state_definition' => "HATA",
		]);
	}
}
