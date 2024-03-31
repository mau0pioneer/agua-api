<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCollectorUuidColumnToContributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->uuid('collector_uuid')->nullable()->after('comments');
            $table->foreign('collector_uuid')->references('uuid')->on('collectors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contributions', function (Blueprint $table) {
            $table->dropForeign(['collector_uuid']);
            $table->dropColumn('collector_uuid');
        });
    }
}
