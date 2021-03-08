<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'provider', 'reference', 'deleted_at'
    ];

    /**
     * The attributes that are returned in API calls.
     *
     * @var array
     */
    protected $visible = [
        'id', 'job_number', 'name', 'provider', 'reference', 'deleted_at', 'clients'
    ];

    /**
     * The attributes that are appended to the model.
     *
     * @var array
     */
    protected $appends = [
        'job_number'
    ];

    /**
     * The attributes that we autmatically append to the model
     *
     * @var array
     */
    protected $with = [
        'clients'
    ];
    
    /**
     * Get the comments for the blog post.
     */
    public function clients()
    {
        return $this->belongsToMany('App\Models\Client');
    }

    /**
     * Get the tasks for this job
     */
    public function statuses()
    {
        return $this->hasMany('App\Models\Status');
    }

    /**
     * Get the tasks for this job
     */
    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    /**
     * Get the formatted task number
     *
     * @return string
     */
    public function getJobNumberAttribute()
    {
        return 'JOB-' . $this->id;
    }

}
