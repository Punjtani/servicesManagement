<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableJobsChangeJobStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE jobs MODIFY job_status ENUM('scheduled','not scheduled','completed') NOT NULL");
        // Schema::table('jobs', function (Blueprint $table) {
        //     $table->enum('job_status',['scheduled','not scheduled','completed'])->change();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE jobs MODIFY job_status ENUM('scheduled','not scheduled') NOT NULL");
        // Schema::table('jobs', function (Blueprint $table) {
        //     $table->enum('job_status',['scheduled','not scheduled'])->change();
        // });
    }
}
