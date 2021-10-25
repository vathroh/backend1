<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\JobDesc;

class PersonnelController extends Controller
{
    public function personnels(){
        $personnels = JobDesc::get();
        return $this->details($personnels);
    }

    public function personnelsAt($day, $month, $year){
        $date =  \Carbon\Carbon::createFromDate($year, $month, $day, 'Asia/Jakarta');
        $personnels = JobDesc::withoutGlobalScopes()->where('starting_date', '<=', $date)->where('finishing_Date', '>=', $date)->get();
        return $this->details($personnels);
    }

    public function details($personnels){
        foreach($personnels as $personnel){
            $personnel->name = $personnel->User->name;
            $personnel->jobTitle = $personnel->JobTitle->job_title;

            $district_id = $personnel->Workzone->district_id;

            if($personnel->Workzone->district_id != 0){
                $personnel->district = District::find($district_id)->nama_kab;
            }
        }

        return $personnels->map(function ($item, $key) {
            return [
                'id' => $item->id,
                'user_id' => $item->user_id,
                'name' => $item->name,
                'jobTitle' => $item->jobTitle,
                'district' => $item->district
            ];
        });
    }

    public function count(){
        return $personnels = JobDesc::get();
        
    }
}
