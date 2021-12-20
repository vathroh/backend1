<?php

namespace App\Http\Controllers\Zone;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ZoneLocation;

class ZoneLocationController extends Controller
{
    public function thisLocation(Request $request){
        return ZoneLocation::where('year', $request->year)->get();
    }
}
