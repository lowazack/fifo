<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use SoftDeletes;
    

    protected $fillable = [
        'name'
    ];

    public function users()
    {
        return $this->morphedByMany('App\Models\User', 'skillable');
    }

    public function tasks()
    {
        return $this->morphedByMany('App\Models\Task', 'skillable');
    }
}
