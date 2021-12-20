<?php

namespace App\Http\Controllers\JobTitle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobTitle;

class TimKorkotController extends Controller
{
  public function index(){
    $data = JobTitle::where('level', 'Korkot')->whereNotIn('job_title', ['Sekretaris', 'Operator'])->get();
    return response()->json($data);
  }
}
