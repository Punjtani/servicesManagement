<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropExtraTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('client_bills');
        Schema::dropIfExists('client_service_appartment_pricing');
        Schema::dropIfExists('client_service_pricings');
        Schema::dropIfExists('client_suppliers');
        Schema::dropIfExists('failed_jobs');
        // Schema::dropIfExists('requests');
        // Schema::dropIfExists('request_attacthments');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
