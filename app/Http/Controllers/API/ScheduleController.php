<?php

namespace App\Http\Controllers\API;

use App\Models\Task;
use App\Models\User;

class ScheduleController extends APIController
{

    public function get()
    {
        
    	$incomplete_tasks = Task::whereNull('completed_at')->get()->sortByDesc('weight')->values()->all();
    	//$complete_tasks = Task::whereNotNull('completed_at')->get();
    	$users = User::all();

    	return response()->json($incomplete_tasks, 200, [], JSON_PRETTY_PRINT);

    }

}
