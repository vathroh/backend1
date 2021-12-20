<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonnelEvaluationValue extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class, 'userId');
    }

    public function setting(){
        return $this->belongsTo(PersonnelEvaluationSetting::class, 'settingId');
    }
}
