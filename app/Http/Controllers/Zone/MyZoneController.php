<?php

namespace App\Http\Controllers\Zone;

use App\Http\Controllers\TimeController;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\WorkZone;
use App\Models\JobDesc;

class MyZoneController extends TimeController
{
    public function myZone(){
        $myJobDesc = JobDesc::where('user_id', auth()->user()->id)->get()->first();
        $zone = $myJobDesc->WorkZone;
        return $zone;
    }

    public function districts(){
        return $this->myZone()->Districts;
    }

    public function personnels(){
        $Personnels = [];

        foreach($this->districts() as $District){
            foreach( $District->personnels as $Personnel){
                $Personnel->name = $Personnel->user->name;
                $Personnel->position = $Personnel->jobTitle->job_title;
                $Personnel->job_title_sort = $Personnel->jobTitle->sort;
                $Personnel->team = $Personnel->workZone->team;
                $Personnel->district_code = $Personnel->workZone->District->kode_kab;
                $Personnel->district = $Personnel->workZone->District->nama_kab;
                $Personnels[] = $Personnel;
            }
        }

        return $Personnels;
    }

    public function subordinates(){
        $workzones = [];

        foreach($this->districts() as $key => $District){
            $datas = WorkZone::where('district_id', $District->id)->where('year', $this->thisYear())->get();

            foreach($datas as $data){
                $workzones[] = $data;
            } 
        }

        return $workzones;
    }
}
