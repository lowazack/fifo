<?php

namespace App\Http\Controllers\API;

class EventController extends APIController
{
    public function __construct()
    {
        $this->model = 'App\Models\Event';
        $this->modelResource = 'App\Http\Resources\Event';
        $this->modelCollectionResource = 'App\Http\Resources\EventCollection';
    }
}
