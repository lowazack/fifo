<?php

namespace App\Http\Controllers\API;

class ActivityController extends APIController
{
    public function __construct()
    {
        $this->model = 'App\Models\Activity';
        $this->modelResource = 'App\Http\Resources\Activity';
        $this->modelCollectionResource = 'App\Http\Resources\ActivityCollection';
    }
}
