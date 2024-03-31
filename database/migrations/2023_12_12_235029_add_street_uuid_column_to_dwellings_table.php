<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStreetUuidColumnToDwellingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dwellings', function (Blueprint $table) {
            $table->uuid('street_uuid');
            $table->foreign('street_uuid')->references('uuid')->on('streets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dwellings', function (Blueprint $table) {
            $table->dropForeign(['street_uuid']);
            $table->dropColumn('street_uuid');
        });
    }
}
