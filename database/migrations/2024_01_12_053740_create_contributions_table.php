<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->float('amount');
            // foreign key to folio
            $table->string('folio', 20)->unique();

            $table->text('comments')->nullable();

            $table->uuid('neighbor_uuid')->nullable();
            $table->foreign('neighbor_uuid')->references('uuid')->on('neighbors');
            $table->uuid('dwelling_uuid')->nullable();
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
        Schema::dropIfExists('contributions');
    }
}
