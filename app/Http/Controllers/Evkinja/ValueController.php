<?php

namespace App\Http\Controllers\Evkinja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobDesc;
use Validator;
use App\Models\JobTitle;
use App\Models\PersonnelEvaluationValue;
use App\Models\PersonnelEvaluationAspect;
use App\Models\PersonnelEvaluationSetting;
use App\Models\PersonnelEvaluationCriteria;
use App\Http\Controllers\Evkinja\SettingController;
use App\Http\Controllers\Evkinja\PersonnelController;
use App\Http\Controllers\Zone\MyZoneController;

class ValueController extends Controller
{
    public function setting(){
        return new SettingController;
    }

    public function personnel(){
        return new PersonnelController;
    }

    public function myZone(){
        return new MyZoneController;
    }

    public function thisValue($id){
        $value = PersonnelEvaluationValue::find($id);
        $content = unserialize(PersonnelEvaluationValue::find($id)->content);
        $value->userTotalVariable = $content['userTotalVariabel']??'';
        $value->totalVariable = $content['totalVariabel']??'';

        return $value;
    }

    public function thisValueUser($id){
        $users = JobDesc::where('user_id', $this->thisValue($id)->userId)->get();

        foreach($users as $user){
            $user->name = $user->user->name;
            $user->jobtitle = $user->jobTitle->job_title;
            $user->team = $user->workZone->team;
            $user->district = $user->workZone->District->nama_kab;
        }

        return $users->map(function($item, $key){
            return [
                'user_id' => $item->user_id,
                'name' => $item->name,
                'job_title' => $item->jobtitle,
                'team' => $item->team,
                'district' => $item->district
            ];
        })[0];
    }

    public function thisValueContent($id){
        $criterias = unserialize(PersonnelEvaluationSetting::find($this->thisValue($id)->settingId)->aspectId);
        $value = unserialize(PersonnelEvaluationValue::find($id)->content);

        $content = [];
        foreach($criterias as $index => $criteria){
            $content[$index]['criteria_id'] = PersonnelEvaluationCriteria::find($criteria[0])->id;
            $content[$index]['criteria'] = PersonnelEvaluationCriteria::find($criteria[0])->criteria;
            $content[$index]['userSumVariable'] = $value[$criteria[0]]['userSumVariabel']??0;
            $content[$index]['userSumScores'] = $value[$criteria[0]]['userSumScores']??0;
            $content[$index]['sumVariabel'] = $value[$criteria[0]]['sumVariabel']??'';
            $content[$index]['sumScores'] = $value[$criteria[0]]['sumScores']??'';
            $content[$index]['proportion'] = PersonnelEvaluationCriteria::where('id', $criteria[0])->first()->proportion??0;

            $aspects = [];
            foreach($criteria as $key => $ctr){
                if($key !=0){
                    $aspects[$key-1]['aspect_id'] = PersonnelEvaluationAspect::find($ctr)->id;
                    $aspects[$key-1]['aspect'] = PersonnelEvaluationAspect::find($ctr)->aspect;
                    $aspects[$key-1]['variable'] = $value[$criteria[0]][$ctr]['variabel']??0;
                    $aspects[$key-1]['userVariable'] = $value[$criteria[0]][$ctr]['userVariable']??$aspects[$key-1]['variable'];
                    $aspects[$key-1]['assessmentVariable'] = $value[$criteria[0]][$ctr]['assessmentVariable']??$aspects[$key-1]['variable'];
                    $aspects[$key-1]['achievement'] = $value[$criteria[0]][$ctr]['capaian']??0;
                    $aspects[$key-1]['userScore'] = $value[$criteria[0]][$ctr]['userScore']??0;
                    $aspects[$key-1]['assessment'] = $value[$criteria[0]][$ctr]['assessment']??0;
                    $aspects[$key-1]['score'] = $value[$criteria[0]][$ctr]['score']??0;
                }
            }

            $content[$index]['aspects'] = $aspects;
        }

        return $content;
    }

    public function createNewValue(Request $request){
        $validator = Validator::make($request->all(), [
            'settingId' => 'required'
        ]);

        if( $validator->fails() ){
            return response()->json($validator->errors(), 400);
        };

        $value =  PersonnelEvaluationValue::where('userId', auth()->user()->id)->where('settingId', $request->settingId);
        
        if($value->exists()){
            return $value->first();
        }else{

            $create = PersonnelEvaluationValue::create([
                'settingId' => $request->settingId,
                'userId' => auth()->user()->id
            ]);

            return $create;
        }
    }

    public function thisSetting($id){
        return PersonnelEvaluationSetting::find($this->thisValue($id)->settingId);
    }
}
