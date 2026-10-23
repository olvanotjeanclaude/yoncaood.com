<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DskBankController;
use App\Http\Controllers\LanguageController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test-mail', [LanguageController::class, 'email'])->name('testmail');

Route::group(['middleware' => 'auth'], function () {
	Route::get('/biletlerim', [CustomerController::class, 'biletlerim'])->name('biletlerim');
	Route::post('/cancel-order/{pnr_number}', [DskBankController::class, 'cancelOrder'])->name("cancelOrder");
	Route::get('/bilgilerim', [CustomerController::class, 'bilgilerim'])->name('bilgilerim');
	Route::get('/sifremi-unuttum', [CustomerController::class, 'forgotPassword'])->name('forgotPassword');
	Route::post('/update-profile', [CustomerController::class, 'updateCustomer'])->name('updateCustomer');
	Route::post('/update-password', [CustomerController::class, 'changePassword'])->name('update-password');
});

// Auth routes
// Route::get('login', [LoginController::class,'showLoginForm'])->name('login');
Route::redirect('login', '/?login=false')->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'create']);
Route::post('/confirm-phone', [RegisterController::class, 'confirmPhone']);

Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::get('/aboutus', [FrontendController::class, 'aboutus'])->name('aboutus');
Route::get('/offices', [FrontendController::class, 'offices'])->name('offices');
Route::get('/iletisim', [FrontendController::class, 'iletisim'])->name('iletisim');
Route::post('/language-switch', [LanguageController::class, 'switch'])->name('language.switch');

// PNR SORGULAMA
Route::view('/search-pnr', "site.pnr.form")->name("pnrSearch");
Route::redirect('/check-pnr', '/search-pnr');
Route::post('/check-pnr', [FrontendController::class, 'checkPnr'])->name('checkPnr');


// SEFER İŞLEMLERİ
Route::get('/sefersorgula/{origin}/{destination}/{checkInDate}', [FrontendController::class, 'seferSorgula'])->name('sefersorgula');
Route::post('seyehatsec', [FrontendController::class, 'seyehatsec'])->name("seyehatsec");
Route::post('satinal', [TicketController::class, 'satinal']);
Route::get('/get-seat-layout/{travelId}/{destination}/{originx}', [FrontendController::class, 'getSeatLayout']);
Route::post('/save-booking', [DskBankController::class, 'saveBooking'])->name("saveBooking");
Route::get('/bilet-bilgileri/{order_id}', [BookingController::class, 'getTicket'])->name('getTicket');

Route::prefix("/dskbank")->group(function () {
	// Route::post('/pay', [DskBankController::class, 'pay']);
	Route::get('/success', [DskBankController::class, 'successCallback'])->name('dskbank.success');
	Route::get('/fail', [DskBankController::class, 'failCallback'])->name('dskbank.fail');
});


Route::put('/user/{id}', [CustomerController::class, 'updateCustomer'])->name('updateCustomer');


Route::prefix('password')->name('password.')->group(function () {
	Route::view('forget', 'site.forgot-password')->name('forget');
	Route::post('/check-phone', [ForgotPasswordController::class, 'sendResetLinkPhone'])->name('check_phone');
	Route::get('reset/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('reset');
	Route::post('reset', [ForgotPasswordController::class, 'resetPassword'])->name('update');
});

Route::group(['middleware' => 'auth'], function () {
	// logout route
	Route::post('/logout', [LoginController::class, 'logout']);
	Route::get('/clear-cache', [HomeController::class, 'clearCache']);

	// dashboard route  
	Route::get('/dashboard', function () {
		return view('pages.dashboard');
	})->name('dashboard');

	//only those have manage_user permission will get access
	Route::group(['middleware' => 'can:manage_user'], function () {
		Route::get('/users', [UserController::class, 'index']);
		Route::get('/user/get-list', [UserController::class, 'getUserList']);
		Route::get('/user/create', [UserController::class, 'create']);
		Route::post('/user/create', [UserController::class, 'store'])->name('create-user');
		Route::get('/user/{id}', [UserController::class, 'edit']);
		Route::post('/user/update', [UserController::class, 'update']);
		Route::get('/user/delete/{id}', [UserController::class, 'delete']);
	});

	//only those have manage_role permission will get access
	Route::group(['middleware' => 'can:manage_role|manage_user'], function () {
		Route::get('/roles', [RolesController::class, 'index']);
		Route::get('/role/get-list', [RolesController::class, 'getRoleList']);
		Route::post('/role/create', [RolesController::class, 'create']);
		Route::get('/role/edit/{id}', [RolesController::class, 'edit']);
		Route::post('/role/update', [RolesController::class, 'update']);
		Route::get('/role/delete/{id}', [RolesController::class, 'delete']);
	});


	//only those have manage_permission permission will get access
	Route::group(['middleware' => 'can:manage_permission|manage_user'], function () {
		Route::get('/permission', [PermissionController::class, 'index']);
		Route::get('/permission/get-list', [PermissionController::class, 'getPermissionList']);
		Route::post('/permission/create', [PermissionController::class, 'create']);
		Route::get('/permission/update', [PermissionController::class, 'update']);
		Route::get('/permission/delete/{id}', [PermissionController::class, 'delete']);
	});

	// get permissions
	Route::get('get-role-permissions-badge', [PermissionController::class, 'getPermissionBadgeByRole']);

	// Basic demo routes
	include('modules/demo.php');
	// Inventory routes
	include('modules/inventory.php');
	// Accounting routes
	include('modules/accounting.php');
});


// Route::get('/register', function () { return view('pages.register'); });
// Route::get('/login-1', function () { return view('pages.login'); });
