<?php

namespace App\Http\Controllers\Evkinja;

use App\Http\Controllers\Evkinja\EvkinjaController;
use App\Models\PersonnelEvaluationValue;
use Illuminate\Http\Request;
use App\Models\JobTitle;
use App\Models\JobDesc;

class RecapController extends EvkinjaController
{

    public function recap(){

        $jobTitleIds = $this->personnel()->jobTitleIds();
        $myWorkZoneIds = collect($this->myZone()->subordinates())->pluck('id');
        $evkinjaValue = $this->personnel()->evkinjaValue();

        $jobTitles =  JobTitle::find($jobTitleIds);

        foreach($jobTitles as $jobTitle){
            $thisEvkinjaValues = collect($evkinjaValue)->where('job_title_id', $jobTitle->id);
            $Ids = JobDesc::where('job_title_id', $jobTitle->id)->whereIn('work_zone_id', $myWorkZoneIds)->get();

            $availableEvkinja = $thisEvkinjaValues->whereIn('user_id', $Ids->pluck('user_id'));

            $jobDescs = $this->personnel()->evkinjaPersonnelDetails($Ids, $availableEvkinja);

            $jobTitle->selesaiEvaluasi = $jobDescs->whereIn('user_id', $availableEvkinja->where('ready', 1)->pluck('user_id'));
        }

        return $this->recapDetails($jobTitles);
    }

    public function recapDetails($jobTitles){ 
        $here = [];
        foreach($jobTitles as $key => $data){
            $here[$key]['job_title'] = $data['job_title'];
            $here[$key]['user'] = $data['selesaiEvaluasi'];
        }

        $et = [];
        foreach($here as $key => $er){
            $et[$key]['job_title'] = $er['job_title'];
            $et[$key]['user'] = [];
            if($er['user'] != null){
                foreach($er['user'] as $el => $user){
                    $evkinja = PersonnelEvaluationValue::find($user['evkinja_value_id']);
                    $user['finalResult'] = $evkinja->finalResult;
                    $user['totalScore'] = $evkinja->totalScore;
                    $user['issue'] = $evkinja->issue;
                    $user['recommendation'] = $evkinja->recommendation;
                    $et[$key]['user'][$el] = $user;
                }
            }
        }
        return $et;
    }
}
