<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'quote_id'
    ];

    /**
     * Get the tasks for this job
     */
    public function quote()
    {
        return $this->belongsTo('App\Models\Quote');
    }
}
