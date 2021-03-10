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
            $activeEntry->save();
        }

        $entry = new TimeEntry($request->all());
        $entry->user_id = $request->user()->id;

        $entry->save();
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

        $entry->is_active = false;
        $entry->save();

        $newEntry = new TimeEntry();
        $newEntry->fill([
            'activity_id' => $entry->activity_id,
            'task_id' => $entry->task_id,
            'user_id' => $entry->user_id,
            'start' => new carbon,
            'description' => $entry->description,
            'is_active' => true
        ]);
        $newEntry->save();

        return response()->json([
            "message" => 'Timer Started'
        ]);
    }
}
