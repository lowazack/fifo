<?php

namespace App\Http\Controllers\API;

use App\Events\TimerUpdated as TimerEvent;
use App\Models\TimeEntry;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    public function store(Request $request, ...$resources)
    {
        $openTimers = TimeEntry::where('end', null)
            ->where('user_id', $request->user()->id)
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

    public function myTimeEntries(): JsonResponse
    {
        $user = auth()->guard('api')->user()->id;

        $entries = TimeEntry::where('user_id', '=', $user)
            ->get();

        return response()->json($entries);
    }

    public function pausePlay($id)
    {
        $user = auth()->guard('api')->user()->id;
        $entry = TimeEntry::find($id);
        if ($user->id === $entry->user_id) {
            $entry->end = (new Carbon());
            $entry->save();
            return response()->json($entry);
        }

        return response()->json(
            [
                'message' => 'We were unable to update the timer',
                'errors' => 'Unauthorized'
            ], 400
        );


    }
}
