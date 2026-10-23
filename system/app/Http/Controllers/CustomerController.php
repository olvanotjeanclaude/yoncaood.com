<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
	public function biletlerim(Request $request)
	{
		$biletler = $request->user()
			->myTickets()
			->orderBy("LOCATIONDATE", "desc")
			->paginate(10);

		return view('site.biletlerim', ['user' => auth()->user(), 'biletler' => $biletler]);
	}

	public function bilgilerim()
	{
		return view('site.bilgilerim', ['user' => auth()->user()]);
	}

	public function updateCustomer(Request $request)
	{
		$user = $request->user();

		$validated = $request->validate([
			'phone'    => ['required', 'digits_between:7,15'],
			'identity' => ['required', 'digits:11'], // T.C. Kimlik No
			'name'     => ['required', 'string', 'max:255'],
			'surname'  => ['required', 'string', 'max:255'],
			'email'    => ['required', 'email', 'max:255'],
		]);

		DB::table("LUKSYONCA.MEMBER")->where("MEMBERNO", $user->member_no)->update(
			[
				"CEPNUMBER" => $request->phone,
				"MEMBERNAME" => $request->name,
				"MEMBERSURNAME" => $request->surname,
				"UPDATETIME" => now(),
				"TCNUMBER" => $request->identity,
				"EMAIL" => $request->email,
			]
		);

		$user->update($validated);

		return response()->json(['message' => __('auth.profile_updated_successfully')]);
	}

	public function changePassword(Request $request)
	{
		$user = $request->user();

		$request->validate([
			'password' => ['required', 'string', 'min:8', 'confirmed'],
			'password_confirmation' => 'required|min:8'
		]);

		$user->update(["password" => Hash::make($request->password), "updated_at" => now()]);

		DB::table('LUKSYONCA.MEMBER')->where('CEPNUMBER', $user->phone)->update(["PASSWORD" => $request->password]);

		return redirect()->back()->with('success', __('auth.password_updated_successfully'));
	}
}
