<?php

use App\Http\Controllers\QBOController;
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

Route::get('/', function () {
    return view('welcome');
})->middleware('force.ssl');

// Route::get('{any}', function () {
//     return view('welcome');
// })->where('any', '.*');
Route::get('/qb',[QBOController::class,'syncQBO']);
Route::get('/qb/oauth2/callback', [QBOController::class,'qboCallback']);
Route::get('/qb/sync/invoice/{id}',[QBOController::class,'syncInvoice']);
Route::get('/{vue_capture?}', function () {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*')->middleware('force.ssl');;

// Route::fallback(function () {
//     return redirect('/');
// });
