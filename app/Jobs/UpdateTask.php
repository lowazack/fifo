<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Log;
use App\Models\Task;

class UpdateTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 200;

    protected $event;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        sleep(1);

        if (!array_key_exists('issue', $this->event->data)) {
            error_log(print_r($this->event->data, true));
        }

        $jiraapi = new \Atlassian\JiraRest\Requests\Issue\IssueRequest;
        $jira = $jiraapi->get($this->event->data['issue']['key']);
        $jira = json_decode($jira->getBody(), true);

        if ($jira['fields']['project']['key'] == 'MANY') {
            return false;
        }

        $find = [
            'provider' => 'jira',
            'reference' => $jira['key']
        ];

        $priority = [
            'Blocker' => '5',
            'Critical' => '4',
            'Major' => '3',
            'Minor' => '2',
            'Trivial' => '1',
        ];

        $data = [
            'provider' => 'jira',
            'reference' => $jira['key'],
            'estimate' => (isset($jira['fields']['timetracking']['originalEstimateSeconds']) ? ceil(($jira['fields']['timetracking']['originalEstimateSeconds']/60)/60) : 0 ),
            'deadline' => new \DateTime(),
            'priority' => $priority['Major'],
            'summary' => $jira['fields']['summary'],
            'link' => 'https://londonsoftware.atlassian.net/browse/' . $jira['key'],
            'meta' => [],
            'movable' => '1'
        ];

        Task::updateOrCreate($find, $data);
    }
}
