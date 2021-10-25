<?php

namespace App\Http\Controllers\Evkinja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobDesc;
use App\Models\PersonnelEvaluationValue;
use App\Models\PersonnelEvaluationSetting;
use App\Models\PersonnelEvaluationCriteria;
use App\Models\PersonnelEvaluationAspect;

class ValueController extends Controller
{
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
            $content[$index]['criteria'] = PersonnelEvaluationCriteria::find($criteria[0])->criteria;
            $content[$index]['userSumVariable'] = $value[$criteria[0]]['userSumVariabel'];
            $content[$index]['userSumScores'] = $value[$criteria[0]]['userSumScores'];
            $content[$index]['sumVariabel'] = $value[$criteria[0]]['sumVariabel']??'';
            $content[$index]['sumScores'] = $value[$criteria[0]]['sumScores']??'';

            $aspects = [];
            foreach($criteria as $key => $ctr){
                if($key !=0){
                    $aspects[$key-1]['aspect'] = PersonnelEvaluationAspect::find($ctr)->aspect;
                    $aspects[$key-1]['variable'] = $value[$criteria[0]][$ctr]['variabel']??0;
                    $aspects[$key-1]['userVariable'] = $value[$criteria[0]][$ctr]['userVariabel']??$aspects[$key-1]['variable'];
                    $aspects[$key-1]['assessmentVariable'] = $value[$criteria[0]][$ctr]['assessmentVariabel']??$aspects[$key-1]['variable'];
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

    public function thisSetting($id){
        return PersonnelEvaluationSetting::find($this->thisValue($id)->settingId);

    }
}
