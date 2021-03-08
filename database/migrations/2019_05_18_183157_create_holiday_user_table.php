<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHolidayUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holiday_user', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('holiday_id');
            $table->unsignedBigInteger('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        /*Schema::table('holiday_user', function ($table) {
            $table->dropForeign(['holiday_id']);
            $table->dropForeign(['user_id']);
        });*/

        Schema::dropIfExists('holiday_user');
    }
}
