<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

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

Route::get('reset-system', function (Request $request) {

    ini_set('max_execution_time', 300); // 300 (seconds) = 5 Minutes

    if (Artisan::call('migrate:refresh', ['--seed' => true]) == 0
        && Artisan::call('optimize:clear') == 0) {
        echo "Reset Complete";
        echo "<a href=\"" . \route('backend.auth.login') . "\">Login Page</a>";
    } else {
        echo "Reset Failed";
    }
});

Route::get('update-system', function (Request $request) {
    if (Artisan::call('migrate') == 0
        && Artisan::call('optimize:clear') == 0) {
        echo "New Migration Complete";
        echo "<a href=\"" . \route('backend.auth.login') . "\">Login Page</a>";
    } else {
        echo "Reset Failed";
    }
});

