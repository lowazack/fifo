<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_entries', function (Blueprint $table) {

            $table->uuid('id');

            $table->foreignUuid('task_id')->nullable();
            $table->foreign('task_id')->references('id')->on('tasks');

            $table->foreignUuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->timestamp('start');
            $table->timestamp('end')->nullable();
            $table->string('description', 250);

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
        Schema::dropIfExists('time_entries');
    }
}
