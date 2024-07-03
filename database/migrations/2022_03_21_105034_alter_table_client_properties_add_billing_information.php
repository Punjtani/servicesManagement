<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableClientPropertiesAddBillingInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('client_properties', function (Blueprint $table) {
            $table->boolean('is_separate_billing')->after('is_PO_required')->default('0');
            $table->integer('net_payment_term')->nullable()->after('is_PO_required');
            $table->integer('max_invoice_amount')->nullable()->after('is_PO_required');
            $table->unsignedBigInteger('tax_type_id')->after('is_PO_required');;
            $table->foreign('tax_type_id')->references('id')->on('tax_types');
            $table->unsignedBigInteger('tax_rate')->nullable()->after('tax_type_id');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('client_properties', function (Blueprint $table) {
            $table->dropColumn('is_separate_billing');
            $table->dropColumn('net_payment_term');
            $table->dropColumn('max_invoice_amount');
            $table->dropForeign('client_properties_tax_type_id_foreign');
            $table->dropColumn('tax_type_id');
            $table->dropColumn('tax_rate');
        });
        Schema::enableForeignKeyConstraints();
    }
}
