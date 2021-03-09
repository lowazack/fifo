<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('jira')->nullable()->default('');
            $table->string('googlecalendar')->nullable()->default('');
            $table->string('phone')->nullable();
            $table->string('harvest')->nullable();
            $table->string('slack')->nullable();
            $table->smallInteger('warns')->default(0);
            $table->text('availability')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE users ADD FULLTEXT search_index (first_name, last_name, email)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
