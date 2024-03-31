<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDwellingNeighborsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dwelling_neighbors', function (Blueprint $table) {
            $table->id();

            $table->uuid('neighbor_uuid');
            $table->uuid('dwelling_uuid');

            $table->enum('condition', ['owner', 'renter', 'guest'])->default('owner');
            $table->timestamps();

            $table->foreign('neighbor_uuid')->references('uuid')->on('neighbors');
            $table->foreign('dwelling_uuid')->references('uuid')->on('dwellings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dwelling_neighbors');
    }
}
