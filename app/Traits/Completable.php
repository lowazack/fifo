<?php
 
namespace App\Traits;

use App\Scopes\CompletableScope;
 
trait Completable
{
    
    public static function bootCompletable()
    {
        static::addGlobalScope(new CompletableScope);
    }

}