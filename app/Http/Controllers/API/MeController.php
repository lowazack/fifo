<?php

namespace App\Http\Controllers\API;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;

use App\Http\Resources\TaskCollection;

class MeController extends APIController
{

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(['user' => auth()->user()], 200);
    }

    /**
     * Returns Authenticated User Details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tasks()
    {

        return new TaskCollection(
            collect(QueryBuilder::for(Task::class)->allowedFilters($this->filters())
                ->where('user_id', '=', auth()->user()->id)
                ->paginate(100)
                ->sortByDesc('weight'))
        );

    }
}
