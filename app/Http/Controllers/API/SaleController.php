<?php

namespace App\Http\Controllers\API;

class SaleController extends APIController
{
    public function __construct()
    {
        $this->model = 'App\Models\Sale';
        $this->modelResource = 'App\Http\Resources\Sale';
        $this->modelCollectionResource = 'App\Http\Resources\SaleCollection';
    }
}
