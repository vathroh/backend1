<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimeController extends Controller
{
    public function thisYear(){
        if($this->thisMonth() < 4 ) {
            return \Carbon\Carbon::now()->year - 1;
        } else {
            return \Carbon\Carbon::now()->year;
        }
    }

    public function thisMonth(){
        return \Carbon\Carbon::now()->month;
    }

    public function today()
    {
        return Carbon::now()->day;
    }

    public function this_quarter()
    {
        return Carbon::now()->quarter;
    }

}
