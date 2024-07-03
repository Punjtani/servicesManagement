<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStaffClientProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff', function (Blueprint $table) {
            //
            $table->date('date_of_birth')->nullable()->before('created_at');
            $table->string('phone')->nullable()->before('created_at');
            $table->text('notes')->nullable()->before('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff', function (Blueprint $table) {
            //
            $table->dropColumn('date_of_birth');
            $table->dropColumn('phone');
            $table->dropColumn('notes');
        });
    }
}
