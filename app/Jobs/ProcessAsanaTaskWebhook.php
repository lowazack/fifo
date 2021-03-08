<?php

namespace App\Jobs;

use \Spatie\WebhookClient\ProcessWebhookJob as SpatieProcessWebhookJob;
use \Spatie\WebhookClient\Models\WebhookCall;
use \Illuminate\Support\Facades\Log;
use Asana\Client as AsanaClient;

use App\Models\Client;
use App\Models\User;
use App\Models\Job;
use App\Models\Task;


class ProcessAsanaTaskWebhook extends SpatieProcessWebhookJob
{

    public function handle()
    {

		if(!isset($this->webhookCall->payload['events'])){

    		return true;

    	}

        foreach($this->webhookCall->payload['events'] as $event){

	        if($event['action'] == 'added' || $event['action'] == 'undeleted' || $event['action'] == 'changed'){

		        try {

					$asana_client = AsanaClient::accessToken(
						env('ASANA_TOKEN'),
						array('headers' => array('asana-enable' => 'new_user_task_lists'))
					);

					$asana_task = $asana_client->tasks->getTask($event['resource']['gid']);

					$task = Task::withTrashed()->withCompleted()->updateOrCreate(
						['provider' => 'asana', 'reference' => $asana_task->gid],
						[
							'summary' => $asana_task->name,
							'link' => $asana_task->permalink_url,
							'deleted_at' => null
						]
					);

					$task->job()->dissociate();

					if(isset($asana_task->projects) && is_array($asana_task->projects)){

						foreach($asana_task->projects as $project){

							$job = Job::firstOrCreate(
					        	['provider' => 'asana', 'reference' => $project->gid],
					        	['name' => $project->name]
					        );

					        $task->job()->associate($job);

						}

					}

					if(isset($asana_task->assignee) && is_object($asana_task->assignee) && isset($asana_task->assignee->gid)){

						$user = User::whereAsana($asana_task->assignee->gid)->first();

						$task->user()->associate($user);

					}

					if(isset($asana_task->due_on)){

						if(isset($asana_task->due_at)){

							$task->deadline = $asana_task->due_on . ' ' . $asana_task->due_at;

						}else{

							$task->deadline = $asana_task->due_on . ' 12:00:00';

						}
						
					}

					if(isset($asana_task->custom_fields) && is_array($asana_task->custom_fields)){

						foreach($asana_task->custom_fields as $custom_field){

							if($custom_field->name == 'Priority' && $custom_field->enum_value != null){

								$task->priority = str_replace(['High', 'Medium', 'Low'], ['4', '3', '2'], $custom_field->enum_value->name);

							}

							if($custom_field->name == 'Estimate'){

								$task->estimate = $custom_field->number_value;

							}

						}

					}

					if(isset($asana_task->completed) && $asana_task->completed == true){
				
						$date = new \DateTime($asana_task->completed_at);

						$task->completed_at = $date->format('Y-m-d H:i:s');

					}

					$task->save();


		        } catch (\Asana\Errors\AsanaError $e) {

		          	\Log::info($e->response->raw_body);

		        }

		    }else if($event['action'] == 'deleted' || $event['action'] == 'removed'){

		    	try {

			        $task = Task::withTrashed()->withCompleted()->where([
			        	['provider','=','asana'],
			        	['reference','=', $event['resource']['gid']]
			        ])->delete();

			    } catch (\Asana\Errors\AsanaError $e) {

		          	\Log::info($e->response->raw_body);

		        }
		    }
		}

		return true;

    }
}