<?php

namespace App\Http\Controllers\Evkinja\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobTitle;
use App\Models\ZoneLocation;
use App\Models\PersonnelEvaluationAspect;
use App\Models\PersonnelEvaluationSetting;
use App\Models\PersonnelEvaluationCriteria;
use Symfony\Component\HttpFoundation\Response;

class SettingResourceController extends Controller
{
    public function setting(){
        return new \App\Http\Controllers\Evkinja\SettingController;
    }

    public function create(Request $request){
        $find = PersonnelEvaluationSetting::where('quarter', $request->quarter)->where('year', $request->year)->where('jobTitleId', $request->job_title_id)->where('zone_location_id', $request->zone_location_id);

        if($find->exists() ){
            return response([
                'message'=> 'Data sudah ada'
            ], Response::HTTP_CONFLICT);
            
        }else{

            $setting = PersonnelEvaluationSetting::create([
                'quarter' => $request->quarter,
                'year' => $request->year,
                'jobTitleId' => $request->job_title_id,
                'zone_location_id' => $request->zone_location_id,
                'isActive' => 0,
                'status' => 0
            ]);

            $setting->posisi = JobTitle::find($setting->jobTitleId)->job_title??'';
            $setting->location = ZoneLocation::find($setting->zone_location_id)->location_type??"";

            return $setting;
        }
    }

    public function show(Request $request){
        $setting = PersonnelEvaluationSetting::find($request->id);
        return $this->setting()->details($setting);
    }
}
