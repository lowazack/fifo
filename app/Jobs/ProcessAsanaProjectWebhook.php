<?php

namespace App\Jobs;

use \Spatie\WebhookClient\ProcessWebhookJob as SpatieProcessWebhookJob;
use \Spatie\WebhookClient\Models\WebhookCall;
use \Illuminate\Support\Facades\Log;
use Asana\Client as AsanaClient;

use App\Models\Client;
use App\Models\Job;

class ProcessAsanaProjectWebhook extends SpatieProcessWebhookJob
{

    public function handle()
    {

    	if(!isset($this->webhookCall->payload['events'])){

    		return true;

    	}

        foreach($this->webhookCall->payload['events'] as $event){

	        if($event['action'] == 'added' || $event['action'] == 'undeleted'){

		        $data = array (
		            'filters' => 
		            array (
		              array (
		                'action' => 'added',
		                'resource_type' => 'task',
		              ),
		              array (
		                'action' => 'changed',
		                'resource_type' => 'task',
		              ),
		              array (
		                'action' => 'deleted',
		                'resource_type' => 'task',
		              ),
		              array (
		                'action' => 'removed',
		                'resource_type' => 'task',
		              ),
		              array (
		                'action' => 'undeleted',
		                'resource_type' => 'task',
		              ),
		            ),
		            'resource' => $event['resource']['gid'],
		            'target' => \URL::to('/api/webhook/task'),
		        );

		        try {

					$asana_client = AsanaClient::accessToken(env('ASANA_TOKEN'));
					$webhook = $asana_client->webhooks->createWebhook($data);

					// \Log::info('PROJECT WEBHOOK ADDED');
					// \Log::info(print_r($webhook, true));

					$asana_project = $asana_client->projects->getProject($event['resource']['gid']);

					// \Log::info('PROJECT DATA');
					// \Log::info(print_r($asana_project, true));

					$client = Client::updateOrCreate(
						['name' => $asana_project->team->name],
						['name' => $asana_project->team->name]
					)->jobs()->withTrashed()->updateOrCreate(
						['provider' => 'asana', 'reference' => $asana_project->gid],
						[
							'name' => $asana_project->name,
							'provider' => 'asana',
							'reference' => $asana_project->gid,
							'deleted_at' => null
						]
					);

		        } catch (\Asana\Errors\AsanaError $e) {

		          	\Log::info($e->response->raw_body);

		        }

		    }else if($event['action'] == 'changed'){

		    	try {

		    		$asana_client = AsanaClient::accessToken(env('ASANA_TOKEN'));

		    		$updated = $asana_client->webhooks->getWebhooks(['workspace' => '1161660961298782']);

		    		$update_flag = false;

		    		$target_url = \URL::to('/api/webhook/task');

		       		foreach($updated as $update){

		        		if($update->resource->resource_type == $event['resource']['resource_type'] && $update->resource->gid == $event['resource']['gid'] && $update->target == $target_url){

		        			$update_flag = true;

		        			break;

		        		}

		        	}

		        	if(!$update_flag){

		        		$data = array (
				            'filters' => 
				            array (
				              array (
				                'action' => 'added',
				                'resource_type' => 'task',
				              ),
				              array (
				                'action' => 'changed',
				                'resource_type' => 'task',
				              ),
				              array (
				                'action' => 'deleted',
				                'resource_type' => 'task',
				              ),
				              array (
				                'action' => 'removed',
				                'resource_type' => 'task',
				              ),
				              array (
				                'action' => 'undeleted',
				                'resource_type' => 'task',
				              ),
				            ),
				            'resource' => $event['resource']['gid'],
				            'target' => $target_url,
				        );

						$webhook = $asana_client->webhooks->createWebhook($data);

		        	}

					$asana_project = $asana_client->projects->getProject($event['resource']['gid']);

					$client = Client::updateOrCreate(
						['name' => $asana_project->team->name],
						['name' => $asana_project->team->name]
					)->jobs()->updateOrCreate(
						['provider' => 'asana', 'reference' => $asana_project->gid],
						[
							'name' => $asana_project->name,
							'provider' => 'asana',
							'reference' => $asana_project->gid
						]
					);

			    } catch (\Asana\Errors\AsanaError $e) {

		          	\Log::info($e->response->raw_body);

		        }

		    }else if($event['action'] == 'deleted' || $event['action'] == 'removed'){

		        $asana_client = AsanaClient::accessToken(env('ASANA_TOKEN'));
		        
		        $deleted = $asana_client->webhooks->getWebhooks(['workspace' => '1161660961298782']);

		        foreach($deleted as $delete){

		        	if($delete->resource->resource_type == $event['resource']['resource_type'] && $delete->resource->gid == $event['resource']['gid']){

			        	try {

			        		$asana_client->webhooks->deleteWebhook($delete->gid);

							// \Log::info('PROJECT WEBHOOK DELETED');
		     				// \Log::info(print_r($delete, true));


			        	} catch (\Asana\Errors\AsanaError $e) {

				          	\Log::info($e->response->raw_body);

				        }

				    }

		        }
		    }
		}

		return true;

    }
}