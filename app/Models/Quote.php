<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;
    
    protected $fillable = [];

    /**
     * Get the items that form this quote.
     */
    public function items()
    {
        return $this->hasMany('App\Models\Item');
    }
}
