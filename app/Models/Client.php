<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FullTextSearch;

class Client extends Model
{

    use SoftDeletes, FullTextSearch;

    public $fillable = [
        'name',
        'address_line_1',
        'address_line_2',
        'address_town',
        'address_county',
        'address_postcode',
        'address_country',
        'phone',
        'email',
        'vat_exempt'
    ];

    /**
     * The attributes that we autmatically append to the model
     *
     * @var array
     */
    protected $with = [
        'contacts'
    ];

    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'name',
        'email'
    ];
    
    /**
     * Get the comments for the blog post.
     */
    public function jobs()
    {
        return $this->belongsToMany('App\Models\Job');
    }

    public function contacts()
    {
        return $this->belongsToMany('App\Models\Contact');
    }
}
