<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Harvest;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class UpdateHarvest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 200;

    protected $task;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function getHarvestProjects()
    {

        $projects = [];

        $page = 1;

        while ($page) {
            $harvest_projects = Harvest::project()->get(false, null, null, $page, 100);

            foreach ($harvest_projects->projects as $harvest_project) {
                if (isset($harvest_project->code) && !empty($harvest_project->code)) {
                    $projects[$harvest_project->code] = json_decode(json_encode($harvest_project), true);
                }
            }

            if (count($harvest_projects->projects) < 100) {
                $page = false;
            } else {
                $page ++;
            }
        }

        return $projects;
    }

    public function getHarvestTasks()
    {

        $tasks = [];

        $page = 1;

        while ($page) {
            $harvest_tasks = Harvest::task()->get(false, null, $page, 100);

            foreach ($harvest_tasks->tasks as $harvest_task) {
                $tasks[$harvest_task->name] = json_decode(json_encode($harvest_task), true);
            }

            if (count($harvest_tasks->tasks) < 100) {
                $page = false;
            } else {
                $page ++;
            }
        }

        return $tasks;
    }

    public function getHarvestClients()
    {

        $clients = [];

        $page = 1;

        while ($page) {
            $harvest_clients = Harvest::client()->get(false, null, $page, 100);

            foreach ($harvest_clients->clients as $harvest_client) {
                $clients[$harvest_client->name] = json_decode(json_encode($harvest_client), true);
            }

            if (count($harvest_clients->clients) < 100) {
                $page = false;
            } else {
                $page ++;
            }
        }

        return $clients;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        sleep(1);

        $projects = $this->getHarvestProjects();
        $tasks = $this->getHarvestTasks();
        $clients = $this->getHarvestClients();

        if($this->task->provider == 'jira'){

            $jiraapi = new \Atlassian\JiraRest\Requests\Issue\IssueRequest;
            $jira = $jiraapi->get($this->task->reference);
            $jira = json_decode($jira->getBody(), true);

            $active = ((!isset($jira['fields']['resolution']['name']) || $jira['fields']['resolution']['name'] == 'Unresolved') && $jira['fields']['status']['name'] != 'Backlog');

            if (isset($jira['fields']['customfield_11277']) && $jira['fields']['customfield_11277']['value'] == 'Yes') { // If this is supported by a support agreement, don't create a separate Harvest entry
                //\Log::warning('Reference ' . $this->task->reference . ' is covered by support agreement, skipping.');
                return true;
            }

            //\Log::warning('Reference ' . $this->task->reference);
            //\Log::warning(print_r($jira, true));

            // Check if harvest entry exists already
            if (isset($projects[$this->task->reference])) {
                try {
                    //Update the project with any asdditional changes from JIRA
                    Harvest::project()->update(
                        $projects[$this->task->reference]['id'], // $projectId
                        $projects[$this->task->reference]['client']['id'], // $clientId
                        $this->task->summary, // $name
                        true, // $isBillable
                        'Tasks', // $billBy
                        'task', // budgetBy
                        $this->task->reference, // $code
                        $active, // $isActive
                        false, // $isFixedFee
                        95, // $hourlyRate
                        $this->task->estimate, // $budget
                        true, // $notifyWhenOverBudget
                        75, // $overBudgetNotificationPercentage
                        true // $showBudgetToAll
                    );
                } catch (GuzzleHttp\Exception\ClientException $exception) {
                    $harvest_project = Harvest::project()->create(
                        $harvest_client, // $clientId
                        $this->task->summary . ' DUPLICATE ' . rand(1, 276522), // $name
                        true, // $isBillable
                        'Tasks', // $billBy
                        'task', // budgetBy
                        $this->task->estimate, // $code
                        $active, // $isActive
                        false, // $isFixedFee
                        95, // $hourlyRate
                        $this->task->estimate, // $budget
                        true, // $notifyWhenOverBudget
                        75, // $overBudgetNotificationPercentage
                        true // $showBudgetToAll
                    );
                }

                // Now grab the task budgets / assignments
                $task_assignments = Harvest::projectTaskAssignment()->get($projects[$this->task->reference]['id'], false, null, 1, 100);
                foreach ($task_assignments->task_assignments as $assignment) {
                    // We only want to change development and testing times, leaving other manual inputs in tact
                    if ($assignment->task->id == $tasks['Development']['id']) {
                        // Recalculate the development budget
                        Harvest::projectTaskAssignment()->update($projects[$this->task->reference]['id'], $assignment->id, true, true, 95, ($this->task->estimate/100*85));
                    } elseif ($assignment->task->id == $tasks['Testing']['id']) {
                        // Recalculate the testing budget
                        Harvest::projectTaskAssignment()->update($projects[$this->task->reference]['id'], $assignment->id, true, true, 95, ($this->task->estimate/100*15));
                    }
                }
            } else {
                $clients = $this->getJiraClients();

                if (isset($clients[$jira['fields']['project']['name']])) {
                    $harvest_client = $clients[$jira['fields']['project']['name']]['id'];
                } else {
                    $harvest_client_created = Harvest::client()->create($jira['fields']['project']['name']);
                    $harvest_client = $harvest_client_created->id;
                }

                try {
                    $harvest_project = Harvest::project()->create(
                        $harvest_client, // $clientId
                        $this->task->summary, // $name
                        true, // $isBillable
                        'Tasks', // $billBy
                        'task', // budgetBy
                        $this->task->reference, // $code
                        $active, // $isActive
                        false, // $isFixedFee
                        95, // $hourlyRate
                        $this->task->estimate, // $budget
                        true, // $notifyWhenOverBudget
                        75, // $overBudgetNotificationPercentage
                        true // $showBudgetToAll
                    );
                } catch (GuzzleHttp\Exception\ClientException $exception) {
                    $harvest_project = Harvest::project()->create(
                        $harvest_client, // $clientId
                        $this->task->summary . ' DUPLICATE ' . rand(1, 276522), // $name
                        true, // $isBillable
                        'Tasks', // $billBy
                        'task', // budgetBy
                        $this->task->reference, // $code
                        $active, // $isActive
                        false, // $isFixedFee
                        95, // $hourlyRate
                        $this->task->estimate, // $budget
                        true, // $notifyWhenOverBudget
                        75, // $overBudgetNotificationPercentage
                        true // $showBudgetToAll
                    );
                }

                Harvest::projectTaskAssignment()->create($harvest_project->id, $tasks['Development']['id'], true, true, 95, ($this->task->estimate/100*85));
                Harvest::projectTaskAssignment()->create($harvest_project->id, $tasks['Testing']['id'], true, true, 95, ($this->task->estimate/100*15));
            }
        }
    }
}
