<?php

namespace App\Http\Controllers\API;

class QuoteItemController extends APIController
{
    public function __construct()
    {
        $this->model = 'items';
        $this->modelResource = 'App\Http\Resources\Item';
        $this->modelCollectionResource = 'App\Http\Resources\ItemCollection';
        $this->hierarchy = ['App\Models\Quote'];
    }
}
