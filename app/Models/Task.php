<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Completable;
use App\Traits\Weighted;
use App\Models\Client;
use App\Models\Job;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;

class Task extends BaseModel
{
    use SoftDeletes, Completable,Uuid;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider', 'reference', 'estimate', 'quote', 'deadline', 'priority', 'summary', 'description', 'link', 'meta', 'movable', 'brief', 'complete', 'job_id', 'user_id', 'status_id'
    ];

    /**
     * The attributes that are returned in API calls.
     *
     * @var array
     */
    protected $visible = [
        'id', 'task_number', 'provider', 'reference', 'estimate', 'quote', 'deadline', 'priority', 'summary', 'description', 'link', 'meta', 'movable', 'brief', 'complete', 'completed_at', 'created_at', 'job', 'job_id', 'user', 'user_id', 'status', 'status_id', 'skills', 'weight'
    ];

    /**
     * The attributes that are appended to the model.
     *
     * @var array
     */
    protected $appends = [
        'complete',
        'task_number',
        'weight'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'array',
        'movable' => 'boolean',
        'complete' => 'boolean'
    ];

    /**
     * The attributes that we autmatically append to the model
     *
     * @var array
     */
    protected $with = [
        'job', 'user', 'status', 'skills'
    ];

    /**
     * The attributes that we can filter and search on
     *
     * @var array
     */
    protected $searchable = [
        'complete'
    ];

    /**
     * The default values for attributes on this model
     *
     * @var array
     */
    protected $attributes = [
        'priority' => 1
    ];

    public static function assignee($id)
    {
        return self::where('user_id', $id);
    }

    /**
     * Get the job for this task
     */
    public function job()
    {
        return $this->belongsTo('App\Models\Job');
    }

    /**
     * Get the assignee for this task
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the status for this task
     */
    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }

    /**
     * Get the tasks for this job
     */
    public function skills()
    {
        return $this->morphToMany('App\Models\Skill', 'skillable');
    }

    /**
     * Get the tasks for this job
     */
    public function timeEntries()
    {
        return $this->hasMany('App\Models\TimeEntry');
    }

    /**
     * Get the formatted task number
     *
     * @return string
     */
    public function getTaskNumberAttribute()
    {
        return 'MANY-' . $this->id;
    }

    /**
     * Get the completion status as a boolean
     *
     * @return string
     */
    public function getCompleteAttribute()
    {
        return ($this->completed_at == null) ? false : true ;
    }

    /**
     * Set the completion status as a boolean
     *
     * @param  string  $value
     * @return void
     */
    public function setCompleteAttribute($value)
    {
        $this->attributes['completed_at'] = ($value == true) ? \Carbon::now() : null;
    }

    /**
     * Calculate a weight for the task according to a weighting formula
     *
     * @param  string  $value
     * @return void
     */
    public function getWeightAttribute(){

        $factor = 200;

        if($this->deadline == null){

            $factor = 50;

        }

        $priority = (isset($this->priority)) ? $this->priority : 1 ;

        $priority_score = ($factor*$priority);

        if($this->deadline == null){

            $created = new \DateTime($this->created_at);
            $now = new \DateTime();

            if($created < $now) {
                // Deadline missed (it's in the past)
                $gap = $created->diff($now)->format("%a");
                $date_score = (1 * $gap);
            }else{
                $date_score = 0;
            }

        }else{

            $deadline = new \DateTime($this->deadline);
            $now = new \DateTime();

            if($deadline < $now) {
                // Deadline missed (it's in the past)
                $gap = $deadline->diff($now)->format("%a");
                $date_score = (5 * $gap);
            }else{
                $gap = $now->diff($deadline)->format("%a");
                $date_score = (5 * -$gap);
            }

        }

        return $date_score;//($priority_score + $date_score);

    }
}
