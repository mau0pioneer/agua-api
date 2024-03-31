<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // neighbor_uuid
            $table->uuid('neighbor_uuid');
            $table->foreign('neighbor_uuid')->references('uuid')->on('neighbors');

            // dwelling_uuid
            $table->uuid('dwelling_uuid');
            $table->foreign('dwelling_uuid')->references('uuid')->on('dwellings');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signatures');
    }
}
