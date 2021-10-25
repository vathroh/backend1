<?php

namespace App\Http\Controllers\Evkinja;

use App\Http\Controllers\Evkinja\EvkinjaController;
use Illuminate\Http\Request;
use App\Models\PersonnelEvaluationSetting;

class SettingController extends EvkinjaController
{
    public function currentSetting(){
        return personnelEvaluationSetting::where('quarter', $this->thisQuarter())->where('year', $this->thisYear())->get();
    }
}
