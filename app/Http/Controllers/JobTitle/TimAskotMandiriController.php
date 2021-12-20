<?php

namespace App\Http\Controllers\JobTitle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobTitle;

class TimAskotMandiriController extends Controller
{
  public function index(){
    $data = JobTitle::where('level', 'Askot Mandiri')->whereNotIn('job_title', ['Sekretaris', 'Operator'])->get();
    return response()->json($data);
  }

}
