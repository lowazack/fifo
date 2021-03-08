<?php
 
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserOrderedScope;

 
trait Weighted
{
    
    public static function bootWeighted()
    {
        static::addGlobalScope('weighted', function (Builder $builder) {
            $builder->orderByDesc('weight');
        });
    }

}