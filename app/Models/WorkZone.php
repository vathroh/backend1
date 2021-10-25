<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkZone extends Model
{
    use HasFactory;

    public function JobDesc(){
        return $this->hasMany(JobDesc::class);
    }

    public function District(){
        return $this->belongsTo(District::class);
    }

    public function Districts(){
        return $this->morphedByMany(District::class, 'work_zonable');
    }

    public function Villages(){
        return $this->morphedByMany(Village::class, 'work_zonable');
    }

}
