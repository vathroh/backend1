<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonnelEvaluationSetting extends Model
{
    use HasFactory;


    public function jobDesc(){
        return $this->hasMany(JobDesc::class, 'job_title_id', 'jobTitleId');

    }

    public function evkinjaValue(){
        return $this->hasMany(PersonnelEvaluationValue::class, 'settingId');
    }
}
