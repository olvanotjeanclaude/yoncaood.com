<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\PasswordChangeRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\PassengerInformation;
use App\Models\ContactInformation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class AuthController extends BaseController
{
    public function login(AuthRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        if (auth()->attempt($credentials)) {
            $accessToken = auth()->user()->createToken('authToken')->accessToken;

            return $this->successResponse([
                'access_token' => $accessToken,
            ]);
        }

        return $this->failedResponse('Invalid Credentials!');
    }

    public function profile(Request $request): JsonResponse
    {
        if ($user = auth()->user()) {
            $roles = $user->getRoleNames();
            $permission = $user->getAllPermissions();

            return $this->successResponse($user);
        }

        return $this->failedResponse();
    }

    public function changePassword(PasswordChangeRequest $request): JsonResponse
    {
        $user = auth()->user();
        if ($user && Hash::check($request->old_password, $user->password)) {
            User::find($user->id)
                ->update([
                    'password' => Hash::make($request->password),
                ]);

            return $this->successResponse([
                'message' => 'Password has been changed',
            ]);
        }

        return $this->failedResponse();
    }

    public function updateProfile(ProfileUpdateRequest $request): JsonResponse
    {
        $user = auth()->user();
        // check unique email except this user
        if (isset($request->email)) {
            $check = User::where('email', $request->email)
                ->where('id', '!=', $user->id)
                ->first();

            if ($check) {
                return $this->failedResponse('The email address is already used!');
            }
        }

        $user->update(
            $request->only([
                'name',
                'email',
            ])
        );

        return $this->successResponse([
            'message' => 'Profile updated successfully!',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = auth()->user()->token();
        $user->revoke();

        return $this->successResponse([
            'message' => 'Logged out succesfully!',
        ]);
    }

    public function callback(Request $request)
    {

        $requestData = $request->all();

        Log::info($requestData);

        $orderNumber = $request->input('orderNumber');

        $mdOrder = $request->query('mdOrder');
        $operation = $request->query('operation');
        $callbackCreationDate = $request->query('callbackCreationDate');
        $status = $request->input('status');


        // https://luksyonca.mydata.com.tr/vts/sales.php?UserId=luksyoncaood&Password=JsE7Ozw59d3Q&travelId=240&departure=1600&arrival=9902&passengerName=SEFTIN YILDIZ&telephone=5438266324&tcNo=12345678912&seats=35&gender=E

        //

        if ($status == 1) {
            $orders = PassengerInformation::where('orderid', $orderNumber)->get();

            $items = []; // Boş bir dizi oluştur

            $apiUrl = 'https://luksyonca.mydata.com.tr/vts/sales.php';
            $userId = 'luksyoncaood';
            $password = 'JsE7Ozw59d3Q';

            // Kontrol etmek için yazdırma ve items dizisine ekleme
            foreach ($orders as $order) {
                $contactInformationId = $order->contact_information_id;
                $contactInformation = ContactInformation::find($contactInformationId);
                $phoneNumber = $contactInformation->mobile_number;

                // echo 'Order ID: ' . $order->orderid . '<br>';

                // Diğer özelliklere benzer şekilde erişim sağlayabilirsiniz
                $params = [
                    'UserId' => $userId,
                    'Password' => $password,
                    'travelId' => $order->travel_id,
                    'departure' => $order->origin,
                    'arrival' => $order->destination,
                    'passengerName' => $order->ad,
                    'telephone' => $phoneNumber,
                    'tcNo' => $order->passaportno,
                    'seats' => $order->seat,
                    'gender' => ($order->gender === 'man') ? 'E' : (($order->gender === 'woman') ? 'B' : ''),
                ];

                // print_r($params);
                // die;

                // Items dizisine ekleyin
                $fullApiUrl = $apiUrl . '?' . http_build_query($params);
                $response = file_get_contents($fullApiUrl);

                echo 'Response for ' . $order->ad . ': ' . $response . '<br>';
            }
            
            // $items dizisini başka bir yerde kullanabilirsiniz
            print_r($items);
        }

        if ($status == 1) {
            $order = PassengerInformation::where('orderid', $orderNumber)->update(['status' => 1]);
        }
    }
}
