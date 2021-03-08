<?php

namespace App\Http\Controllers\API;

class ClientController extends APIController
{
    public function __construct()
    {
        $this->model = 'App\Models\Client';
        $this->modelResource = 'App\Http\Resources\Client';
        $this->modelCollectionResource = 'App\Http\Resources\ClientCollection';
    }
}
