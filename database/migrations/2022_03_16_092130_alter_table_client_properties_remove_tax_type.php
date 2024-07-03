<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableClientPropertiesRemoveTaxType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_properties', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            Schema::table('client_properties', function (Blueprint $table) {
                $table->dropForeign('client_properties_tax_type_id_foreign');
                $table->dropColumn('tax_type_id');
                $table->dropColumn('tax_rate');
                $table->string('billing_address')->nullable()->after('street2');
                $table->string('phone')->nullable()->after('zipcode');
                $table->unsignedBigInteger('manager')->after('title');
            });
            Schema::enableForeignKeyConstraints();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_properties', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            Schema::table('client_properties', function (Blueprint $table) {
                $table->unsignedBigInteger('tax_type_id');
                $table->foreign('tax_type_id')->references('id')->on('tax_types');
                $table->unsignedBigInteger('tax_rate')->nullable()->after('tax_type_id');
                $table->dropColumn('billing_address');
                $table->dropColumn('phone');
                $table->dropColumn('manager');
            });
            Schema::enableForeignKeyConstraints();
        });
    }
}
