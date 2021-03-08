<?php

namespace App\Http\Controllers\BotMan;

use App\Http\Controllers\Controller;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use App\Conversations\ExampleConversation;
use App\Conversations\AddTaskConversation;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public static function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public static function addTaskConversation(BotMan $bot, $client = false, $user = false)
    {
        $bot->startConversation(new AddTaskConversation($client, $user));
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public static function getTasksForUser(BotMan $bot, $user)
    {

        $message = "OK, here's the tasks for " . $user->first_name . ":\n";

        foreach($user->tasks as $task){

            $message .= "➡️ Task " . $task->id . ': ' . $task->summary . " - " . $task->client->name . " - Priority " . $task->priority . "\n";

        }

        $bot->reply($message);

    }
}
