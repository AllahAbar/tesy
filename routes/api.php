<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//--add new -----
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Api\Atg_memberController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//---------add recently------------

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/profile', [AuthController::class, 'me']);
});

Route::post('password/email', [PasswordResetController::class, 'sendOtp']);
Route::post('password/reset', [PasswordResetController::class, 'verifyOtp']);



//---------------------------------------Hoàng--------------------------------------------
Route::get('/getdb', function () {
    $gay= DB::table('table_user')->where('id',1)->first();
    if($gay){
        return response()->json($gay);
    }
    else
    return "you wrong";
});

Route::get('/db', function () {
    return view('truyvan');
});

Route::get('/delete', function () {
    DB::table('table_atg_members')->where('username', 'mrHuy')->delete();
});

Route::get('/ins', function () {
    DB::table('table_atg_members')->insert([
        'username' => 'KKK',
        'email' => 'GGGoe@example.com',
        'password' => '34',
    ]);
});

Route::get('/upd', function () {
    DB::table('table_atg_members')->where('username', 'John Doe')->update([
        'username' => 'dung khoai',
    ]);
});

//---------------------------------------Dũng----------------------------------------------

Route::get('showAll', [Atg_memberController::class, 'index']);
Route::get('show/{id}', [Atg_memberController::class, 'show']);
Route::post('create', [Atg_memberController::class, 'store']);
Route::put('update/{id}', [Atg_memberController::class, 'update']);
Route::delete('delete/{id}', [Atg_memberController::class, 'destroy']);
