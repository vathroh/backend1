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
}
