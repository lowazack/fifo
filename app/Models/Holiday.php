<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Holiday extends Model
{

    use SoftDeletes;
    
    protected $fillable = [
        'start', 'end', 'note'
    ];

    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}
