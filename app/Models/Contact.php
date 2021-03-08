<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;
    
    public $fillable = [
        'title', 'first_name','last_name','email','phone'
    ];

    public function clients()
    {
        return $this->belongsToMany('App\Models\Client');
    }
}
