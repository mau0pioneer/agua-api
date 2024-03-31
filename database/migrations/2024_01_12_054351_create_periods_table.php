<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            //month
            $table->string('month');
            //year
            $table->string('year');
            //status enum (pending, paid, overdue)  
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            //amount float
            $table->float('amount');

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
        Schema::dropIfExists('periods');
    }
}
