<?php

namespace App\Http\Controllers\Evkinja;

use App\Http\Controllers\Evkinja\EvkinjaController;
use App\Http\Controllers\Evkinja\SettingController;
use App\Models\PersonnelEvaluationSetting;
use App\Models\PersonnelEvaluationValue;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\JobTitle;
use App\Models\JobDesc;

class PersonnelController extends EvkinjaController
{
    public function setting(){
        return new SettingController;
    }

    public function currentJobTitle(){
        $evkinjaValue = $this->evkinjaValue();
        $jobTitleIds =  $this->setting()->currentSetting()->unique('jobTitleId')->pluck('jobTitleId');

        $jobTitles =  JobTitle::find($jobTitleIds);

        foreach($jobTitles as $jobTitle){
            $thisEvkinjaValues = collect($evkinjaValue)->where('job_title_id', $jobTitle->id);
            $jobDescs = JobDesc::where('job_title_id', $jobTitle->id);
            $availableEvkinjaValue = $thisEvkinjaValues->whereIn('user_id', $jobDescs->pluck('user_id'));

            $jobTitle->count = $jobDescs->count();
            $jobTitle->belumMengisi = $jobDescs->whereNotIn('user_id', $thisEvkinjaValues->pluck('user_id'))->count();
            $jobTitle->prosesMengisi = $availableEvkinjaValue->where('ok_by_user', 0)->count();
            $jobTitle->selesaiMengisi = $availableEvkinjaValue->where('ok_by_user', 1)->count();
            $jobTitle->siapEvaluasi = $availableEvkinjaValue->where('totalScore', 0.00)->where('ready', 0)->count();
            $jobTitle->prosesEvaluasi = $availableEvkinjaValue->where('totalScore','!=', 0.00)->where('ready', 0)->count();
            $jobTitle->selesaiEvaluasi = $availableEvkinjaValue->where('ready', 1)->count();
        }

        return $jobTitles->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'job_title' => $item->job_title,
                'count' => $item->count,
                'belumMengisi' => $item->belumMengisi,
                'prosesMengisi' => $item->prosesMengisi,
                'selesaiMengisi' => $item->selesaiMengisi,
                'siapEvaluasi' => $item->siapEvaluasi,
                'prosesEvaluasi' => $item->prosesEvaluasi,
                'selesaiEvaluasi' => $item->selesaiEvaluasi
            ];
        });
    }

    public function belumMengisi(){
        $jobTitleIds =  $this->setting()->currentSetting()->unique('jobTitleId')->pluck('jobTitleId');
        $jobDescs = JobDesc::whereIn('job_title_id', $jobTitleIds)->get();
        $availableEvkinja = collect($this->evkinjaValue());
        $evkinjaUserIds = collect($availableEvkinja)->pluck('user_id');
        $Ids = $jobDescs->whereNotIn('user_id', $evkinjaUserIds);

        return $this->evkinjaPersonnelDetails($Ids, $availableEvkinja);
    }

    public function prosesMengisi(){
        return $this->evkinjaPersonnels($where1="ok_by_user", $operator1="==", $value1=0, $where2="ok_by_user", $operator2="==", $value2=0 );
    }

    public function selesaiMengisi(){
        return $this->evkinjaPersonnels($where1="ok_by_user", $operator1="==", $value1=1, $where2="ok_by_user", $operator2="==", $value2=1);
    }

    public function siapEvaluasi(){
        return $this->evkinjaPersonnels($where1="ok_by_user", $operator1="==", $value1=1, $where2="totalScore", $operator2="==", $value2='0.00');
    }

    public function prosesEvaluasi(){
        return $this->evkinjaPersonnels($where1="totalScore", $operator1="!=", $value1='0.00', $where2="ready", $operator2="==", $value2=0);
    }

    public function selesaiEvaluasi(){
        return $this->evkinjaPersonnels($where1="ready", $operator1="==", $value1=1, $where2="ready", $operator2="==", $value2=1);
    }

    public function evkinjaPersonnels($where1, $operator1, $value1, $where2, $operator2, $value2){
        $jobTitleIds =  $this->setting()->currentSetting()->unique('jobTitleId')->pluck('jobTitleId');
        $jobDescs = JobDesc::whereIn('job_title_id', $jobTitleIds)->get();
        $availableEvkinja = collect($this->evkinjaValue())->where($where1, $operator1, $value1)->where($where2, $operator2, $value2);
        $evkinjaUserIds = $availableEvkinja->pluck('user_id');
        $Ids = $jobDescs->whereIn('user_id', $evkinjaUserIds);

        return $this->evkinjaPersonnelDetails($Ids, $availableEvkinja);
    }

    public function evkinjaPersonnelDetails($Ids, $availableEvkinja){
        foreach($Ids as $Id) {
            $Id->name = $Id->user->name;
            $Id->jobTitle = $Id->jobTitle->job_title;
            $Id->team = $Id->workZone->team;
            $Id->district = $Id->workZone->District->nama_kab;
            $Id->evkinja_value_id = $availableEvkinja->where('user_id', $Id->user_id)->where('job_title_id', $Id->job_title_id)->first()['id'] ?? '';
        }

        return $Ids->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'name' => $item->name,
                'jobTitle' => $item->jobTitle,
                'team' => $item->team,
                'district' => $item->district,
                'evkinja_value_id' => $item->evkinja_value_id
            ];
        });
    }

    public function EvkinjaValue(){
        $currentSettings = $this->setting()->currentSetting();

        $evkinjaValues = [];
        foreach($currentSettings as $currentSetting){
            $thisEvkinjaValue = $currentSetting->evkinjaValue->map(function ($item, $key) {
                return [
                    'id' => $item->id,
                    'user_id' => $item->userId,
                    'ok_by_user' => $item->ok_by_user,
                    'ready' => $item->ready,
                    'userTotalScore' => $item->userTotalScore,
                    'totalScore' => $item->totalScore
                ];
            });

            foreach($thisEvkinjaValue as $user){
                $user['job_title_id'] = $currentSetting->jobTitleId;
                $evkinjaValues[] = $user;
            }

        }
        return $evkinjaValues;
    }

}
