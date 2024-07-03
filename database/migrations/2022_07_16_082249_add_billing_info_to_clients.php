<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBillingInfoToClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->boolean('is_separate_billing')->after('is_PO_required')->default('0');
            $table->integer('net_payment_term')->nullable()->after('is_PO_required');
            $table->integer('max_invoice_amount')->nullable()->after('is_PO_required');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('is_separate_billing');
            $table->dropColumn('net_payment_term');
            $table->dropColumn('max_invoice_amount');
        });
    }
}
