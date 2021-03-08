<?php
 
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserOrderedScope;

 
trait OrderByStartDate
{
    
    public static function bootUserOrdered()
    {
        static::addGlobalScope('orderbystartdate', function (Builder $builder) {
            $builder->orderBy('start');
        });
    }

}