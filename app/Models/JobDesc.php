<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\JobDescScope;

class JobDesc extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::addGlobalScope(new JobDescScope);
    }
 
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function jobTitle(){
        return $this->belongsTo(JobTitle::class);
    }

    public function workZone(){
        return $this->belongsTo(WorkZone::class);
    }

}
