<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessDwellingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_dwellings', function (Blueprint $table) {
            $table->id();
            $table->uuid('dwelling_uuid');
            $table->foreign('dwelling_uuid')->references('uuid')->on('dwellings');
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
        Schema::dropIfExists('access_dwellings');
    }
}
