<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuoteColumnsToJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->enum('type',['job','quote'])->default('job')->after('is_billed');
            $table->enum('quote_status',['draft','awaiting_response','changes_requested','approved','converted','archived'])->default('draft')->after('is_billed');
            $table->boolean('is_quote')->default(0)->after('is_billed');
            $table->integer('created_by')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            //
        });
    }
}
