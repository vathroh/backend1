<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kabupaten extends Model
{
    use HasFactory;

    protected $table = 'kabupaten';

    public function WorkZone()
    {
        return $this->morphToMany('App\work_zone', 'work_zonable');
    }

}
