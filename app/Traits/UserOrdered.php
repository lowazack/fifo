<?php
 
namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\UserOrderedScope;

 
trait UserOrdered
{
    
    public static function bootUserOrdered()
    {
        static::addGlobalScope('userordered', function (Builder $builder) {
            $builder->orderBy('order');
        });
    }

}