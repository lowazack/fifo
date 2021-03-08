<?php

namespace App\Http\Controllers\API;

class UserController extends APIController
{
    public function __construct()
    {
        $this->model = 'App\Models\User';
        $this->modelResource = 'App\Http\Resources\User';
        $this->modelCollectionResource = 'App\Http\Resources\UserCollection';
    }
}
