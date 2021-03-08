<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Traits\Completable;
use App\Traits\UserOrdered;

class Status extends Model
{
    use SoftDeletes, UserOrdered;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'color', 'job_id', 'order', 'completed'
    ];

    /**
     * The attributes that are returned in API calls.
     *
     * @var array
     */
    protected $visible = [
        'id', 'name', 'color', 'job', 'job_id', 'order', 'completed'
    ];

    /**
     * The attributes that we autmatically append to the model
     *
     * @var array
     */
    protected $with = [
        'job'
    ];

    /**
     * The default values for attributes on this model
     *
     * @var array
     */
    protected $attributes = [
        'color' => '#FFFFFF'
    ];

    /**
     * Cast certain attributes with specific formats
     *
     * @var array
     */
    protected $casts = [
        'completed' => 'boolean',
    ];

    /**
     * Get the job for this task
     */
    public function job()
    {
        return $this->belongsTo('App\Models\Job');
    }

    /**
     * Get the tasks for this task
     */
    public function tasks()
    {
        return $this->hasMany('App\Models\Status');
    }

}
