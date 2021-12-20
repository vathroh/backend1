<?php

namespace App\Http\Controllers\Evkinja\Value;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonnelEvaluationValue;

class OkByAssessorController extends Controller
{
  //
  public function setOk($id){
    PersonnelEvaluationValue::find($id)->update([
      'ready' => 1
    ]);
  }

}
