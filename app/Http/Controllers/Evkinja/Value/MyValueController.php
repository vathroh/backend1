<?php

namespace App\Http\Controllers\Evkinja\Value;

use Illuminate\Http\Request;
use App\Models\PersonnelEvaluationValue;
use App\Http\Controllers\Evkinja\ValueController;

class MyValueController extends ValueController
{
    public function setting(){
        return new \App\Http\Controllers\Evkinja\SettingController;
    }

    public function myValueAll(){
        return PersonnelEvaluationValue::where('userId', auth()->user()->id)->get();
    }

    public function myValueNow(){
        $settingId =  $this->setting()->meNow() ? $this->setting()->meNow()->id : 0 ;
            
        return PersonnelEvaluationValue::where('settingId', $settingId )->where('userId', auth()->user()->id)->get();
    }

}
