<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_tracking', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignUuid('time_entry_id')
                ->references('id')
                ->on('time_entries');


            $table->dateTime('start');
            $table->dateTime('end')->nullable();

            $table->boolean('isOverride')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_tracking');
    }
}
