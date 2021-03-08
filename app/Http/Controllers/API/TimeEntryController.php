<?php

namespace App\Http\Controllers\API;

use App\Events\Timer as TimerEvent;
use App\Models\TimeEntry;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Integer;

class TimeEntryController extends APIController
{
    public function __construct()
    {
        $this->model = 'App\Models\TimeEntry';
        $this->modelResource = 'App\Http\Resources\TimeEntry';
        $this->modelCollectionResource = 'App\Http\Resources\TimeEntryCollection';
    }

    public function store(Request $request, ...$resources): JsonResponse
    {
        $openTimers = TimeEntry::where('end', '=', null)
            ->where('user_id', '=', $request->user()->id)
            ->get();

        foreach ($openTimers as $timer) {
            $timer->end = new Carbon();
            $timer->save();
        }


        $entry = new TimeEntry($request->all());
        $entry->user_id = $request->user()->id;

        $entry->save();

        return response()->json($openTimers);
    }
}
