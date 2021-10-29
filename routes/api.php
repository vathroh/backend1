<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */


/*
 * Authentication
*/
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', [\App\Http\Controllers\AuthController::class, 'user']);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function(){
    /*
    *
    | Personnels
    *
    */
    Route::get('me', [\App\Http\Controllers\Personnel\PersonnelController::class, 'me']);
    Route::get('personnels', [\App\Http\Controllers\Personnel\PersonnelController::class, 'personnels']);
    Route::get('personnels-at/{day}/{month}/{year}', [\App\Http\Controllers\Personnel\PersonnelController::class, 'personnelsAt']);
    /*
    *
    | Personnels per districts
    *
    */
    Route::get('personnels-at-district/{id}', [\App\Http\Controllers\Zone\DistrictController::class, 'personnels']);
    Route::get('count-of-korkot-faskel-teams', [\App\Http\Controllers\Zone\PersonnelController::class, 'countKorkotFaskelTeams']);
    Route::get('count-of-korkot-teams', [\App\Http\Controllers\Zone\PersonnelController::class, 'countKorkotTeams']);
    Route::get('count-of-faskel-teams', [\App\Http\Controllers\Zone\PersonnelController::class, 'countFaskelTeams']);
    /*
    *
    | WorkZone
    *
    */
    Route::get('zone', [\App\Http\Controllers\Zone\MyZoneController::class, 'myZone']);
    Route::get('zone/districts', [\App\Http\Controllers\Zone\MyZoneController::class, 'districts']);
    Route::get('zone/myteams', [\App\Http\Controllers\Zone\MyZoneController::class, 'myteams']);
    Route::get('zone/subordinates', [\App\Http\Controllers\Zone\MyZoneController::class, 'subordinates']);
    Route::get('zone/personnels', [\App\Http\Controllers\Zone\MyZoneController::class, 'personnels']);
    Route::get('zone/personnels-by-district/{district_id}', [\App\Http\Controllers\Zone\MyZoneController::class, 'personnelsByDistrict']);

    /*
    *
    | Evkinja
    *
    */
    Route::get('evkinja/current-setting', [\App\Http\Controllers\Evkinja\SettingController::class, 'currentSetting']);
    Route::get('evkinja/current-job-title', [\App\Http\Controllers\Evkinja\PersonnelController::class, 'currentJobTitle']);
    Route::get('evkinja/belum-mengisi', [\App\Http\Controllers\Evkinja\PersonnelController::class, 'belumMengisi']);
    Route::get('evkinja/proses-mengisi', [\App\Http\Controllers\Evkinja\PersonnelController::class, 'prosesMengisi']);
    Route::get('evkinja/selesai-mengisi', [\App\Http\Controllers\Evkinja\PersonnelController::class, 'selesaiMengisi']);
    Route::get('evkinja/siap-evaluasi', [\App\Http\Controllers\Evkinja\PersonnelController::class, 'siapEvaluasi']);
    Route::get('evkinja/proses-evaluasi', [\App\Http\Controllers\Evkinja\PersonnelController::class, 'prosesEvaluasi']);
    Route::get('evkinja/selesai-evaluasi', [\App\Http\Controllers\Evkinja\PersonnelController::class, 'selesaiEvaluasi']);
    Route::get('evkinja/role', [\App\Http\Controllers\Evkinja\PersonnelController::class, 'role']);

    Route::get('evkinja/personil/{evkinja_id}', [\App\Http\Controllers\Evkinja\ValueController::class, 'thisValueUser']);
    Route::get('evkinja/content/{evkinja_id}', [\App\Http\Controllers\Evkinja\ValueController::class, 'thisValueContent']);
    Route::get('evkinja/data/{evkinja_id}', [\App\Http\Controllers\Evkinja\ValueController::class, 'thisValue']);
    Route::get('evkinja/setting/{evkinja_id}', [\App\Http\Controllers\Evkinja\ValueController::class, 'thisSetting']);

    Route::get('evkinja/aspects', [\App\Http\Controllers\Evkinja\AspectController::class, 'index']);
    Route::get('evkinja/criteria', [\App\Http\Controllers\Evkinja\CriteriaController::class, 'index']);
});


