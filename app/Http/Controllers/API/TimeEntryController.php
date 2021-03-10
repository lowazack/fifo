<?php

namespace App\Http\Controllers\API;

use App\Events\TimerUpdated as TimerEvent;
use App\Models\TimeEntry;
use App\Models\TimeTracking;
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
        $activeEntries = TimeEntry::where('is_active', true)
            ->where('user_id', $request->user()->id)
            ->get();

        foreach ($activeEntries as $activeEntry) {
            $activeEntry->end = new Carbon();
            $activeEntry->is_active = false;

            $activeTimers = TimeTracking::where('time_entry_id', $activeEntry->id)
                ->where('end', null)->get();
            foreach ($activeTimers as $timer) {
                $timer->end = new Carbon();
                $timer->save();
            }
            $activeEntry->save();
        }

        $entry = new TimeEntry($request->all());
        $entry->user_id = $request->user()->id;

        $entry->save();
        $trackingRow = new TimeTracking();
        $trackingRow->start = new carbon();
        $trackingRow->time_entry_id = $entry->id;

        $trackingRow->save();

        return response()->json($activeEntries);

    }

    public function myTimeEntries(): JsonResponse
    {
        $user = auth()
            ->guard('api')
            ->user()->id;

        $entries = TimeEntry::where('user_id', '=', $user)
            ->get();

        return response()->json($entries);
    }

    public function pausePlay($id)
    {

        $user = auth()->guard('api')->user();
        $entry = TimeEntry::find($id);

        if ($user->id !== $entry->user_id) {
            return response()->json([
                'message' => 'We were unable to update the timer',
                'errors' => 'Unauthorized'
            ], 400
            );
        }


        if ($entry->is_active) {

            $entry->is_active = false;
            $entry->end = new Carbon();
            $entry->save();
            return response()->json([
                "message" => 'Timer Paused'
            ]);
        }
        $activeEntries = TimeEntry::where('user_id', $entry->user_id)->where('is_active', true)->get();

        foreach ($activeEntries as $activeEntry) {
            $activeEntry->end = new Carbon();
            $activeEntry->is_active = false;
            $activeEntry->save();
        }

        $activeTimers = TimeTracking::where('time_entry_id', $entry->id)->where('end', null)->get();

        foreach ($activeTimers as $timer) {
            $timer->end = new Carbon();
            $timer->save();
        }

        $newTimer = new TimeTracking();
        $newTimer->start = new carbon;
        $newTimer->time_entry_id = $entry->id;
        $newTimer->save();

        $entry->is_active = true;
        $entry->save();

        return response()->json([
            "message" => 'Timer Started'
        ]);
    }
}
