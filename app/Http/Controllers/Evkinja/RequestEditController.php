<?php

namespace App\Http\Controllers\Evkinja;

use App\Http\Controllers\Evkinja\EvkinjaController;
use App\Models\PersonnelEvaluationValue;
use Illuminate\Http\Request;
use App\Models\JobTitle;
use App\Models\JobDesc;



class RequestEditController extends EvkinjaController
{
    public function editByUser(){
        return $this->index($where='edit_by_user', $value=1);
    }

    public function editByAssessor(){
        if(auth()->user()->hasRole('hrm')){
            return $this->index($where='edit', $value=1);
        }
    }

    public function index($where, $value){ 
        $myWorkZoneIds = collect($this->myZone()->subordinates())->pluck('id');
        $evkinjaValues = collect($this->evkinja( $this->personnel()->evkinjaValue() ));
        $Ids = JobDesc::whereIn('work_zone_id', $myWorkZoneIds)->get();
        $availableEvkinja = $evkinjaValues->whereIn('user_id', $Ids->pluck('user_id'));
        $jobDescs = $this->personnel()->evkinjaPersonnelDetails($Ids, $availableEvkinja);

        return $jobDescs->whereIn('user_id', $availableEvkinja->where($where, $value)->pluck('user_id'));

    }

    public function evkinja($evkinja){
        $data=[];
        foreach( $evkinja as $item){
            $value = PersonnelEvaluationValue::find($item['id']);
            $item['edit_by_user'] = $value->edit_by_user;
            $item['edit'] = $value->edit;
            $data[] = $item;
        }
        return $data;
    }
}
