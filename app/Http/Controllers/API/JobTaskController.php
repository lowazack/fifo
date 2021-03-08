<?php

namespace App\Http\Controllers\API;

class JobTaskController extends APIController
{
    public function __construct()
    {
        $this->model = 'tasks';
        $this->modelResource = 'App\Http\Resources\Task';
        $this->modelCollectionResource = 'App\Http\Resources\TaskCollection';
        $this->hierarchy = ['App\Models\Job'];
    }
}
