<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MailResetPasswordNotification;
use Carbon\Carbon;
use App\Traits\FullTextSearch;
use GoldSpecDigital\LaravelEloquentUUID\Database\Eloquent\Uuid;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, FullTextSearch, Uuid;

    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email',
    ];

    /**
     * The attributes that are returned in API calls.
     *
     * @var array
     */
    protected $visible = [
        'id', 'first_name', 'last_name', 'name', 'email'
    ];

    /**
     * The attributes that are appended to the model.
     *
     * @var array
     */
    protected $appends = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'availability' => 'array',
    ];

    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'first_name',
        'last_name',
        'email'
    ];

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
    public function tasks()
    {
        return $this->hasMany('App\Models\Task');
    }

    /**
     * Get the tasks for this job
     */
    public function timeEntries()
    {
        return $this->hasMany('App\Models\TimeEntry');
    }

    /**
     * Get the tasks for this job
     */
    public function holidays()
    {
        return $this->belongsToMany('App\Models\Holiday');
    }

    /**
     * Scope a query to only include users where time tracking is available.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTimeTrackable($query)
    {
        return $query->whereNotNull('harvest');
    }

    /**
     * Automatically checks to see if user is available via slack
     *
     * @var array
     */
    public function getIsSlackableAttribute()
    {
        return !empty($this->slack);
    }

    /**
     * Automatically checks to see if user is available via slack
     *
     * @var array
     */
    public function getIsAvailableAttribute()
    {

        $date = Carbon::now();

        $day = strtolower($date->format('l'));
        $time = $date->format('Hi');

        // First check if the day of week is set and isn't empty, if
        // it's not then this user isn't available today
        if (!array_key_exists($day, $this->availability) || count($this->availability[$day]) == 0) {
            return false;
        }

        $timematch = false;

        // Next up, iterate through the available times for the day, and
        // see if the current time fits between those times
        foreach ($this->availability[$day] as $timeslot) {
            list($start, $end) = explode(':', $timeslot);
            if ($time >= $start && $time <= $end) {
                $timematch = true;
            }
        }

        // If we've not found a matching time slot, the user is unavailable
        if ($timematch === false) {
            return false;
        }

        // Now check for any holidays and make sure this user isn't on holiday today.
        if ($this->holidays()->whereRaw('DATE(?) BETWEEN start AND end', [$date->format('Y-m-d H:i:s')])->get()->count() > 0) {
            return false;
        }

        return true;
    }


    /**
     * Automatically checks to see if user is available via phone
     *
     * @var array
     */
    public function getIsPhoneableAttribute()
    {
        return !empty($this->phone);
    }


    /**
     * Automatically checks to see if user is available via phone
     *
     * @var array
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Automatically checks to see if user is available via phone
     *
     * @var array
     */
    public function getHasGoogleCalendarAttribute()
    {
        return !empty($this->google_calendar);
    }

    /**
     * Automatically check if user is tracking time
     *
     * @var array
     */
    public function getIsTrackingTimeAttribute()
    {

        $date = Carbon::now();

        $start = $date->copy()->startOfDay();

        $timeentry = \Harvest::timeEntry()->get($this->harvest, ' ', ' ', null, 'true', null, $start->format('Y-m-d'));

        $count = $timeentry->time_entries->count();

        return ($count > 0);
    }

    /**
     * Automatically hashes the password before saving
     *
     * @var array
     */
    public function setPasswordAttribute($password)
    {
        if (Hash::needsRehash($password)) {
            $this->attributes['password'] = Hash::make($password);
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }
}
