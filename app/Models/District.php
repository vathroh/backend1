<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = "kabupaten";

    public function personnels(){
        return $this->hasManyThrough(JobDesc::class, WorkZone::class);
    }

    public function WorkZone(){
        return $this->morphToMany(WorkZone::class, 'work_zonable');
    }

}
