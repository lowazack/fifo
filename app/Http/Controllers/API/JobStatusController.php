<?php

namespace App\Http\Controllers\API;

class JobStatusController extends APIController
{
    public function __construct()
    {
        $this->model = 'statuses';
        $this->modelResource = 'App\Http\Resources\Status';
        $this->modelCollectionResource = 'App\Http\Resources\StatusCollection';
        $this->hierarchy = ['App\Models\Job'];
    }
}
