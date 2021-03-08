<?php

namespace App\Http\Controllers\API;

class QuoteController extends APIController
{
    public function __construct()
    {
        $this->model = 'App\Models\Quote';
        $this->modelResource = 'App\Http\Resources\Quote';
        $this->modelCollectionResource = 'App\Http\Resources\QuoteCollection';
    }
}
