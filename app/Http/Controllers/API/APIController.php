<?php

namespace App\Http\Controllers\API;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

class APIController extends Controller
{

    public $model;
    public $modelResource;
    public $modelCollectionResource;
    public $hierarchy = [];
    public $filters = [];

    protected function filters()
    {

        return array_merge([
            AllowedFilter::trashed(),
            AllowedFilter::scope('onlycompleted', 'onlyCompleted'),
            AllowedFilter::scope('withcompleted', 'withCompleted')
        ], $this->filters);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(...$resources)
    {
        if (count($resources) > 0 && count($this->hierarchy) > 0) {
            $resourcesCombined = array_combine($this->hierarchy, $resources);
            foreach ($resourcesCombined as $resourceKey => $resourceValue) {
                if (!isset($resource)) {
                    $resource = $resourceKey::find($resourceValue);
                } else {
                    $resource = $resource->$resourceKey()->find($resourceValue);
                }
            }
            return new $this->modelCollectionResource(QueryBuilder::for($resource->{$this->model}()->getQuery())->allowedFilters($this->filters())->paginate(100));
        } else {
            return new $this->modelCollectionResource(QueryBuilder::for($this->model)->allowedFilters($this->filters())->paginate(100));
        }
    }

    /**
     * @param Request $request
     * @param mixed ...$resources
     * @return mixed
     */
    public function store(Request $request, ...$resources)
    {
        if (count($resources) > 0 && count($this->hierarchy) > 0) {
            $resourcesCombined = array_combine($this->hierarchy, $resources);
            foreach ($resourcesCombined as $resourceKey => $resourceValue) {
                if (!isset($resource)) {
                    $resource = $resourceKey::find($resourceValue);
                } else {
                    $resource = $resource->$resourceKey()->find($resourceValue);
                }
            }
            return new $this->modelResource($resource->{$this->model}()->create($request->all()));
        } else {
            return new $this->modelResource($this->model::create($request->all()));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $model
     * @return \Illuminate\Http\Response
     */
    public function show(...$resources)
    {

        $modelId = array_pop($resources);

        if (count($resources) > 0 && count($this->hierarchy) > 0) {
            $resourcesCombined = array_combine($this->hierarchy, $resources);
            foreach ($resourcesCombined as $resourceKey => $resourceValue) {
                if (!isset($resource)) {
                    $resource = $resourceKey::find($resourceValue);
                } else {
                    $resource = $resource->$resourceKey()->find($resourceValue);
                }
            }
            return new $this->modelResource(QueryBuilder::for($resource->{$this->model}()->getQuery())->allowedFilters($this->filters())->findOrFail($modelId));
        } else {
            return new $this->modelResource(QueryBuilder::for($this->model)->allowedFilters($this->filters())->findOrFail($modelId));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client $model
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ...$resources)
    {

        $modelId = array_pop($resources);

        if (count($resources) > 0 && count($this->hierarchy) > 0) {
            $resourcesCombined = array_combine($this->hierarchy, $resources);
            foreach ($resourcesCombined as $resourceKey => $resourceValue) {
                if (!isset($resource)) {
                    $resource = $resourceKey::find($resourceValue);
                } else {
                    $resource = $resource->$resourceKey()->find($resourceValue);
                }
            }
            $model = QueryBuilder::for($resource->{$this->model}()->getQuery())->allowedFilters($this->filters())->findOrFail($modelId);
            $model->update($request->all());
            $model->save();
            return new $this->modelResource($model);
        } else {
            $model = QueryBuilder::for($this->model)->allowedFilters($this->filters())->findOrFail($modelId);
            $model->update($request->all());
            $model->save();
            return new $this->modelResource($model);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $model
     * @return \Illuminate\Http\Response
     */
    public function destroy(...$resources)
    {
        $modelId = array_pop($resources);

        if (count($resources) > 0 && count($this->hierarchy) > 0) {
            $resourcesCombined = array_combine($this->hierarchy, $resources);
            foreach ($resourcesCombined as $resourceKey => $resourceValue) {
                if (!isset($resource)) {
                    $resource = $resourceKey::find($resourceValue);
                } else {
                    $resource = $resource->$resourceKey()->find($resourceValue);
                }
            }
            //print_r($resource->{$this->model}()); die();

            $model = QueryBuilder::for($resource->{$this->model}()->getQuery())->allowedFilters($this->filters())->findOrFail($modelId);
            $model->delete();
            return response()->json([
                'result' => 'deleted'
            ]);
        } else {
            $model = QueryBuilder::for($this->model)->allowedFilters($this->filters())->findOrFail($modelId);
            $model->delete();
            return response()->json([
                'result' => 'deleted'
            ]);
        }
    }
}
