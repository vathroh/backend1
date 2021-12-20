<?php

namespace App\Http\Controllers\Evkinja;

use App\Http\Controllers\Evkinja\EvkinjaController;
use Illuminate\Http\Request;
use App\Models\JobDesc;
use App\Models\JobTitle;
use App\Models\ZoneLocation;
use App\Models\PersonnelEvaluationAspect;
use App\Models\PersonnelEvaluationSetting;
use App\Models\PersonnelEvaluationCriteria;

class SettingController extends EvkinjaController
{
    public function currentSetting(){
        return $this->settingAt($this->thisQuarter(), $this->thisYear());
    }

    public function settingAt($quarter, $year){
        $settings = personnelEvaluationSetting::where('quarter', $quarter)->where('year', $year)->orderBy('created_at', 'desc')->get();

        foreach( $settings as $setting){
            $setting->posisi = $setting->jobTitle->job_title;
            $setting->location = $setting->zoneLocation ? $setting->zoneLocation->location_type : '';
        }

        return $settings;
    }

    public function meNow(){
        return $this->meAt($this->thisQuarter(), $this->thisYear());
    }

    public function meAt($quarter, $year){
        $month = $quarter * 3;
        $day = 28;
        
        $date =  \Carbon\Carbon::createFromDate($year, $month, $day, 'Asia/Jakarta');

        $myJobDesc = JobDesc::withoutGlobalScopes()->where('starting_date', '<=', $date)->where('finishing_Date', '>=', $date)->where('user_id', auth()->user()->id )->first();

        $zoneLocationId = $myJobDesc->workZone->zone_location_id;

        return $this->settingAt($quarter, $year)->where('jobTitleId', $myJobDesc->job_title_id)->where('zone_location_id', $zoneLocationId)->first();
    }

    public function details($setting){
        $setting->job_title = JobTitle::find($setting->jobTitleId)->job_title??'';
        $setting->location = ZoneLocation::find($setting->zone_location_id)->location_type??"";
        $aspects = unserialize($setting->aspectId);

        $content = [];
        if($aspects != null){
            foreach($aspects as $key => $aspect){
                $content[$key]['id'] = PersonnelEvaluationCriteria::find($aspect[0])->id;
                $content[$key]['criteria'] = PersonnelEvaluationCriteria::find($aspect[0])->criteria;
                foreach($aspect as $i => $item){
                    if($i != 0){
                        $content[$key]['aspect'][$i]['id'] = PersonnelEvaluationAspect::find($item)->id;
                        $content[$key]['aspect'][$i]['aspect'] = PersonnelEvaluationAspect::find($item)->aspect;
                    }
                }
            }
        }

        $setting->aspects = $content;
        return $setting;
    }
}
