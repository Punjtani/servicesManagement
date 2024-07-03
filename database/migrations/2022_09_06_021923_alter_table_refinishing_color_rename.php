<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableRefinishingColorRename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refinishing_color', function (Blueprint $table) {
            Schema::rename('refinishing_color', 'refinish_primer_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refinishing_color', function (Blueprint $table) {
            Schema::rename('refinish_primer_color', 'refinishing_color');
        });
    }
}
