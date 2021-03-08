<?php

namespace App\Http\Controllers\API;

class ClientContactController extends APIController
{
    public function __construct()
    {
        $this->model = 'contacts';
        $this->modelResource = 'App\Http\Resources\Contact';
        $this->modelCollectionResource = 'App\Http\Resources\ContactCollection';
        $this->hierarchy = ['App\Models\Client'];
    }
}
