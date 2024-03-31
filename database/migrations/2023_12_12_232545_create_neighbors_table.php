<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNeighborsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neighbors', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('firstname');
            $table->string('lastname')->nullable();
            $table->string('phone_number', 12)->nullable()->unique();
            $table->string('prefix')->nullable();
            $table->string('alias')->nullable();
            $table->enum('attitude', [
                'against',
                'distrustful',
                'favor',
                'supportive',
            ])->nullable();
            $table->text('comments')->nullable();
            $table->boolean('signature')->default(false);
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
        Schema::dropIfExists('neighbors');
    }
}
