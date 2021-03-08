<?php

namespace App\Listeners;

use App\Jobs\UpdateTask;

class JiraWebhookListener
{

    /**
     * Handle user login events.
     */
    public function onIssueUpdate($event)
    {

        UpdateTask::dispatch($event);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Atlassian\JiraWebhook\Events\Issue\Created',
            'App\Listeners\JiraWebhookListener@onIssueUpdate'
        );

        $events->listen(
            'Atlassian\JiraWebhook\Events\Issue\Updated',
            'App\Listeners\JiraWebhookListener@onIssueUpdate'
        );

        $events->listen(
            'Atlassian\JiraWebhook\Events\Issue\Deleted',
            'App\Listeners\JiraWebhookListener@onIssueUpdate'
        );

        $events->listen(
            'Atlassian\JiraWebhook\Events\Issue\WorklogUpdated',
            'App\Listeners\JiraWebhookListener@onIssueUpdate'
        );

        $events->listen(
            'Atlassian\JiraWebhook\Events\Worklog\Created',
            'App\Listeners\JiraWebhookListener@onIssueUpdate'
        );

        $events->listen(
            'Atlassian\JiraWebhook\Events\Worklog\Deleted',
            'App\Listeners\JiraWebhookListener@onIssueUpdate'
        );

        $events->listen(
            'Atlassian\JiraWebhook\Events\Worklog\Updated',
            'App\Listeners\JiraWebhookListener@onIssueUpdate'
        );
    }
}
