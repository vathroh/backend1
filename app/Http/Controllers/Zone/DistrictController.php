<?php

namespace App\Http\Controllers\Zone;

use App\Http\Controllers\Personnel\PersonnelController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\District;

class DistrictController extends Controller
{
    public function user(){
        return new PersonnelController;
    }

    public function personnels($id){
        $district = District::find($id);
        $Personnels = $district->personnels;

        return $this->user()->details($Personnels);
    } 
}
