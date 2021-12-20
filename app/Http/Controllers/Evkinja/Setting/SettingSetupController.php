<?php

namespace App\Http\Controllers\Evkinja\Setting;

use Illuminate\Http\Request;
use App\Models\PersonnelEvaluationAspect;
use App\Models\PersonnelEvaluationSetting;
use App\Models\PersonnelEvaluationCriteria;
use App\Http\Controllers\Evkinja\EvkinjaController;
use App\Http\Controllers\Evkinja\SettingController;

class SettingSetupController extends EvkinjaController
{
    public function setting(){
        return new SettingController;
    }

    public function thisSetting(Request $request){
        return PersonnelEvaluationSetting::find($request->id);
    }

    public function settingBefore(Request $request){
        $setting = PersonnelEvaluationSetting::where('jobTitleId', $request->job_title_id)
            ->whereNotIn('id', [$request->id])
            ->where('isActive', 1)
            ->latest()
            ->first();

        return $this->setting()->details($setting);
    }

    public function removeAspects(Request $request){
        PersonnelEvaluationSetting::find($request->id)->update([
            'aspectId' => ''
        ]);
    }

    public function storeAspects(Request $request){
        $aspects = [];
        foreach($request->aspects as $key => $arr){
            foreach($arr['aspect'] as $i => $as){
                $aspects[$key][0] = $arr['id'];
                $aspects[$key][$i+1] = $as['id'];
            }
        }

        return PersonnelEvaluationSetting::where('id', $request->id)->update([
            'aspectId' => serialize($aspects)
        ]);

    }
}
