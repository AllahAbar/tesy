<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/getdb', function () {
    $gay= DB::table('table_user')->where('id',1)->first();
    if($gay){
        return response()->json($gay);
    }
    else
    return "you wrong";
});

route::get('/jjj', function(){
    return "aoiweroaweoiri";
});