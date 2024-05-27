<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/logAdmin', function () {
    return view('user.login');
});

Route::get('/', function () {
    return view('user.loginClient');
})->name('login');

//USER
Route::post('user/login', [\App\Http\Controllers\UserController::class,'login']);
Route::get('reinitialiser', [\App\Http\Controllers\UserController::class,'trun']);
Route::post('user/loginClient', [\App\Http\Controllers\UserController::class,'loginClient']);
Route::get('user/goToLoginClient', [\App\Http\Controllers\UserController::class,'goToLoginClient']);
Route::get('user/logout', [\App\Http\Controllers\UserController::class,'logout']);
Route::get('user/createAdmin', [\App\Http\Controllers\UserController::class,'createAdmin']);
Route::get('user/admin', [\App\Http\Controllers\UserController::class,'admin'])->middleware('admin');
Route::get('user/client', [\App\Http\Controllers\UserController::class,'client'])->middleware('client');


//DEVIS
Route::get('devis/goToCreate', [\App\Http\Controllers\DevisController::class,'goToCreateDevis'])->middleware('client');
Route::get('devis/create', [\App\Http\Controllers\DevisController::class,'createDevis'])->middleware('client');
Route::get('devis/liste', [\App\Http\Controllers\DevisController::class,'listeDevis'])->middleware('client');
Route::get('devis/admin/liste', [\App\Http\Controllers\DevisController::class,'listeDevisAdmin'])->middleware('admin');
Route::post('devis/paiement', [\App\Http\Controllers\DevisController::class,'paiementDevis'])->middleware('client');
Route::get('devis/goToPaiement/{ref}', [\App\Http\Controllers\DevisController::class,'getPaiement'])->middleware('client');
Route::get('devis/{ref}', [\App\Http\Controllers\DevisController::class,'getAllInfoDevis'])->middleware('client');
Route::get('devis/detail/{ref}', [\App\Http\Controllers\DevisController::class,'getDetailDevis'])->middleware('admin');

//AJAX
Route::post('bar/change', [\App\Http\Controllers\AjaxController::class,'changeChoix'])->middleware('admin');

//IMPORT
Route::post('import', [\App\Http\Controllers\ImportController::class,'importCsv']);
Route::post('importPaiement', [\App\Http\Controllers\ImportController::class,'paiement']);
Route::get('import/maison', [\App\Http\Controllers\ImportController::class,'goToImport']);
Route::get('import/paiement', [\App\Http\Controllers\ImportController::class,'goToPaiement']);

//MAISON ET TRAVAUX
Route::get('travaux/liste', [\App\Http\Controllers\MaisonController::class,'listeTravaux']);
Route::get('travaux/update', [\App\Http\Controllers\MaisonController::class,'updateTravaux']);
Route::get('finition/liste', [\App\Http\Controllers\MaisonController::class,'listeFinition']);
Route::get('finition/update', [\App\Http\Controllers\MaisonController::class,'updateFinition']);
