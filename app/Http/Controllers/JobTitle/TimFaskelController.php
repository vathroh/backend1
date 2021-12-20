<?php

namespace App\Http\Controllers\JobTitle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobTitle;

class TimFaskelController extends Controller
{
  public function index(){
    $data = JobTitle::where('level', 'Tim Faskel')->get();
    return response()->json($data);
  }

}
