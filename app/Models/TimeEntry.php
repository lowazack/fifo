<?php

namespace App\Models;

use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\OrderByStartDate;

class TimeEntry extends BaseModel
{
    use SoftDeletes, OrderByStartDate, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_id', 'activity_id', 'user_id', 'start', 'description'
    ];

    /**
     * The attributes that are returned in API calls.
     *
     * @var array
     */
    protected $visible = [
        'id', 'task_id', 'activity_id', 'activity', 'user_id', 'user', 'task', 'start', 'end', 'minutes', 'duration', 'description'
    ];

    /**
     * The attributes that are appended to the model.
     *
     * @var array
     */
    protected $appends = [
        'minutes',
        'duration'
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
        'task', 'user', 'activity'
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

    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function activity()
    {
        return $this->belongsTo('App\Models\Activity');
    }

    public function getStatusAttribute()
    {

        return $this->end ? 'active' : 'closed';

    }

    public function getMinutesAttribute()
    {

        $diff = ($this->end) ? strtotime($this->end) - strtotime($this->start) : time() - strtotime($this->start);

        return floor($diff / 60 % 60);

    }

    public function getDurationAttribute()
    {

        $diff = ($this->end) ? strtotime($this->end) - strtotime($this->start) : time() - strtotime($this->start);

        $hours = floor($diff / 3600);
        $mins = floor($diff / 60 % 60);

        return str_pad($hours, 2, '0', STR_PAD_LEFT) . ':' . str_pad($mins, 2, '0', STR_PAD_LEFT);

    }

    public function setDurationAttribute()
    {

        if (!empty($this->duration)) {

            $hours = explode(':', $this->duration)[0];
            $seconds = explode(':', $this->duration)[1];

            $this->start = new DateTime($this->start);
            $this->end = $this->start->add(new \DateInterval(('PT' . $hours . 'H' . $seconds . 'S')));

        }
        unset($this->duration);
    }
}
