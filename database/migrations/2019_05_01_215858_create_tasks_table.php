<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('job_id')->nullable();
            $table->foreign('job_id')->references('id')->on('jobs');

            $table->string('provider')->nullable();
            $table->string('estimate')->nullable();
            $table->string('quote')->nullable();
            $table->string('reference')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->integer('priority')->nullable();
            $table->string('summary')->nullable();
            $table->longText('description')->nullable();
            $table->string('link')->nullable();
            $table->text('meta')->nullable();
            $table->boolean('movable')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function ($table) {
            $table->dropForeign(['job_id']);
        });

        Schema::dropIfExists('tasks');
    }
}
