<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150);
            $table->string('address_line_1', 200)->nullable();
            $table->string('address_line_2', 200)->nullable();
            $table->string('address_town', 200)->nullable();
            $table->string('address_county', 200)->nullable();
            $table->string('address_postcode', 200)->nullable();
            $table->string('address_country', 200)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('email', 250)->nullable();
            $table->boolean('vat_exempt')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE clients ADD FULLTEXT search_index (name, email)');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
