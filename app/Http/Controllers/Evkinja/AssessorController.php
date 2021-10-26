<?php

namespace App\Http\Controllers\Evkinja;

use App\Http\Controllers\Evkinja\EvkinjaController;
use App\Http\Controllers\Evkinja\SettingController;
use Illuminate\Http\Request;
use App\Models\PersonnelEvaluator;

class AssessorController extends EvkinjaController
{
    public function setting(){
        return new SettingController;
    }

    public function jobAssessed(){
        $myJobId = auth()->user()->jobDesc->job_title_id;
        return PersonnelEvaluator::where('evaluator', $myJobId)->pluck('jobId');
    }
}
