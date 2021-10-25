<?php

namespace App\Http\Controllers\Zone;

use App\Http\Controllers\Zone\MyZoneController;
use Illuminate\Http\Request;
use App\Models\JobTitle;
use App\Models\JobDesc;

class PersonnelController extends MyZoneController
{
    public function countKorkotFaskelTeams(){
        return $this->countPerDistrict([2,3,4]);
    }

    public function countFaskelTeams(){
        $data = $this->countAllDistrict([4]);

        return response()->json($data);
    }

    public function countKorkotTeams(){
        return $this->countAllDistrict([2,3]);
    }

    public function countAllDistrict($zone_level){
        $jobTitles = JobTitle::whereIn('zone_level_id', $zone_level)->get();

        foreach($jobTitles as $jobTitle){
            $jobTitle->count = JobDesc::where('job_title_id', $jobTitle->id)->count();
        }

        return $jobTitles;
    }

    public function countPerDistrict($zone_level){
        $districts = $this->districts();
        $jobTitles = JobTitle::whereIn('zone_level_id', $zone_level)->get();

        $count = [];
        foreach($jobTitles as $jobTitle){
            foreach($districts as $district){
                $count[$district->kode_kab][$jobTitle->job_title] = $district->personnels->where('job_title_id', $jobTitle->id)->count();
            }
        }

        return $count;
    }
}
