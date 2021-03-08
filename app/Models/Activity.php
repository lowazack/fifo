<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Completable;
use App\Traits\Weighted;
use App\Models\Client;
use App\Models\Job;

class Activity extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * The attributes that are returned in API calls.
     *
     * @var array
     */
    protected $visible = [
        'id', 'name',
    ];

    /**
     * The attributes that are appended to the model.
     *
     * @var array
     */
    protected $appends = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The attributes that we autmatically append to the model
     *
     * @var array
     */
    protected $with = [
    ];

    /**
     * The attributes that we can filter and search on
     *
     * @var array
     */
    protected $searchable = [
    ];

    /**
     * The default values for attributes on this model
     *
     * @var array
     */
    protected $attributes = [
    ];

    /**
     * Get the tasks for this job
     */
    public function timeEntries()
    {
        return $this->hasMany('App\Models\TimeEntry');
    }

}
