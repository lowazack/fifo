<?php

namespace App\Http\Controllers\API;

class JobController extends APIController
{
    public function __construct()
    {
        $this->model = 'App\Models\Job';
        $this->modelResource = 'App\Http\Resources\Job';
        $this->modelCollectionResource = 'App\Http\Resources\JobCollection';
    }
}
