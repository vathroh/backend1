<?php

namespace App\Http\Controllers\Evkinja;

use App\Models\PersonnelEvaluationAspect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AspectController extends Controller
{
    public function index(){
        return PersonnelEvaluationAspect::all();
    }

    public function perJobTitle(Request $request){
        return $this->index()->where('evaluate_to', $request->job_title_id);
    }
}
