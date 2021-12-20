<?php

namespace App\Http\Controllers\Evkinja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EvkinjaController extends Controller
{
    public function thisQuarter(){
        $q = \Carbon\Carbon::now()->quarter;
        $month = \Carbon\Carbon::now()->month;

        if($month % 3 > 0)
        {
            $quarter = $q-1;
        }else{
            $quarter = $q;
        }

        if($quarter == 0)
        {
            return 4;
        }

        return $quarter;
        
    }

    public function thisYear(){
        $month = \Carbon\Carbon::now()->month;
        $year = \Carbon\Carbon::now()->year;

        if($month < 3)
        {
            $year = $year-1;
        }

        return $year;
    }

    public function myZone(){
        return new \App\Http\Controllers\Zone\MyZoneController;
    }

    public function personnel(){
        return new  \App\Http\Controllers\Evkinja\PersonnelController;
    }

    public function setting(){
        return new  \App\Http\Controllers\Evkinja\SettingController;
    }
}
