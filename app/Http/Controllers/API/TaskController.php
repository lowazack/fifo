<?php

namespace App\Http\Controllers\API;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TaskController extends APIController
{
    public function __construct()
    {
        $this->model = 'App\Models\Task';
        $this->modelResource = 'App\Http\Resources\Task';
        $this->modelCollectionResource = 'App\Http\Resources\TaskCollection';
    }

//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function index(...$resources)
//    {
//        if (count($resources) > 0 && count($this->hierarchy) > 0) {
//            $resourcesCombined = array_combine($this->hierarchy, $resources);
//            foreach ($resourcesCombined as $resourceKey => $resourceValue) {
//                if (!isset($resource)) {
//                    $resource = $resourceKey::find($resourceValue);
//                } else {
//                    $resource = $resource->$resourceKey()->find($resourceValue);
//                }
//            }
//            return new $this->modelCollectionResource(
//            	collect(QueryBuilder::for($resource->{$this->model}()->getQuery())->allowedFilters($this->filters())
//            	->get()
//            	->sortByDesc('weight')
//            	->paginate(100)));
//        } else {
//            return new $this->modelCollectionResource(
//            	collect(QueryBuilder::for($this->model)->allowedFilters($this->filters())
//            	->get()
//            	->sortByDesc('weight'))
//            	->paginate(100));
//        }
//    }
}
