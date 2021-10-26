<?php

namespace App\Http\Controllers\Evkinja;

use App\Models\PersonnelEvaluationCriteria;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index(){
        return PersonnelEvaluationCriteria::all();
    }
}
