<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Evkinja\RecapController;
use App\Http\Controllers\Evkinja\ValueController;
use App\Http\Controllers\Evkinja\AspectController;
use App\Http\Controllers\Evkinja\SettingController;
use App\Http\Controllers\Evkinja\EvkinjaController;
use App\Http\Controllers\Evkinja\CriteriaController;
use App\Http\Controllers\Evkinja\PersonnelController;
use App\Http\Controllers\Evkinja\RequestEditController;
use App\Http\Controllers\Evkinja\Value\UpdateController;
use App\Http\Controllers\Evkinja\Value\MyValueController;
use App\Http\Controllers\Evkinja\Value\OkByUserController;
use App\Http\Controllers\Evkinja\Value\OkByAssessorController;
use App\Http\Controllers\Evkinja\Attachment\AttachmentController;
use App\Http\Controllers\Evkinja\Setting\SettingResourceController;
use App\Http\Controllers\Evkinja\Setting\SettingSetupController;
use App\Http\Controllers\JobTitle\TimAskotMandiriController;
use App\Http\Controllers\JobTitle\TimKorkotController;
use App\Http\Controllers\JobTitle\TimFaskelController;
use App\Http\Controllers\Zone\ZoneLocationController;
use App\Http\Controllers\Zone\MyZoneController;

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
    Route::get('zone', [MyZoneController::class, 'myZone']);
    Route::get('zone/districts', [MyZoneController::class, 'districts']);
    Route::get('zone/myteams', [MyZoneController::class, 'myteams']);
    Route::get('zone/subordinates', [MyZoneController::class, 'subordinates']);
    Route::get('zone/personnels', [MyZoneController::class, 'personnels']);
    Route::get('zone/personnels-by-district/{district_id}', [MyZoneController::class, 'personnelsByDistrict']);

    Route::post('this-location', [ZoneLocationController::class, 'thisLocation']);
});


Route::middleware('auth:sanctum')->prefix('job-title')->group(function(){
  Route::get('tim-askot-mandiri', [TimAskotMandiriController::class, 'index']);
  Route::get('tim-faskel', [TimFaskelController::class, 'index']);
  Route::get('tim-korkot', [TimKorkotController::class, 'index']);
});


    /*
    *
    | Evkinja
    *
    */

Route::middleware('auth:sanctum')->prefix('evkinja')->group(function(){
    Route::get('this-quarter', [EvkinjaController::class, 'thisQuarter']);
    Route::get('this-year', [EvkinjaController::class, 'thisYear']);

    Route::get('mysettingnow', [SettingController::class, 'meNow']);
    Route::get('current-setting', [SettingController::class, 'currentSetting']);
    Route::get('setting/{quarter}/{year}', [SettingController::class, 'settingAt']);
    Route::post('setting/create', [SettingResourceController::class, 'create']);
    Route::post('setting/show', [SettingResourceController::class, 'show']);
    Route::post('setting/store-aspects', [SettingSetupController::class, 'storeAspects']);
    Route::post('setting/copy-before', [SettingSetupController::class, 'settingBefore']);

    Route::get('role', [PersonnelController::class, 'role']);
    Route::get('siap-evaluasi', [PersonnelController::class, 'siapEvaluasi']);
    Route::get('belum-mengisi', [PersonnelController::class, 'belumMengisi']);
    Route::get('proses-mengisi', [PersonnelController::class, 'prosesMengisi']);
    Route::get('selesai-mengisi', [PersonnelController::class, 'selesaiMengisi']);
    Route::get('proses-evaluasi', [PersonnelController::class, 'prosesEvaluasi']);
    Route::get('selesai-evaluasi', [PersonnelController::class, 'selesaiEvaluasi']);
    Route::get('current-job-title', [PersonnelController::class, 'currentJobTitle']);

    Route::get('personil/{evkinja_id}', [ValueController::class, 'thisValueUser']);
    Route::get('content/{evkinja_id}', [ValueController::class, 'thisValueContent']);
    Route::get('setting/{evkinja_id}', [ValueController::class, 'thisSetting']);
    Route::post('create-new-value', [ValueController::class, 'createNewValue']);
    Route::get('data/{evkinja_id}', [ValueController::class, 'thisValue']);

    Route::get('ok_by_user/{evkinja_id}', [OkByUserController::class, 'setOk']);
    Route::get('ok_by_assessor/{evkinja_id}', [OkByAssessorController::class, 'setOk']);

    Route::post('update-content/{evkinja_id}', [UpdateController::class, 'updateContent']);

    Route::get('myvalueall', [MyValueController::class, 'myValueAll']);
    Route::get('myvaluenow', [MyValueController::class, 'myValueNow']);

    Route::get('recap', [RecapController::class, 'recap']);
    Route::get('aspects', [AspectController::class, 'index']);
    Route::post('aspects', [AspectController::class, 'perJobTitle']);
    Route::get('criteria', [CriteriaController::class, 'index']);
    Route::get('request-edit-by-user', [RequestEditController::class, 'editByUser']);
    Route::get('request-edit-by-assessor', [RequestEditController::class, 'editByAssessor']);

    Route::get('attachment/{evkinja_id}', [AttachmentController::class,'index']);
    Route::post('attachment/upload/{evkinja_id}', [AttachmentController::class,'upload']);
});
