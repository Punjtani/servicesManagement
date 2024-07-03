<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalAddressToClientPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_properties', function (Blueprint $table) {
            $table->string('billing_address_2')->nullable()->after('country');
            $table->string('billing_city')->nullable()->after('billing_address_2');
            $table->string('billing_state')->nullable()->after('billing_city');
            $table->string('billing_zipcode')->nullable()->after('billing_state');
            $table->string('billing_country')->nullable()->after('billing_zipcode');
            $table->boolean('is_same_address')->default(0)->after('billing_country');
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
            $table->dropColumn('billing_address_2');
            $table->dropColumn('billing_city');
            $table->dropColumn('billing_state');
            $table->dropColumn('billing_zipcode');
            $table->dropColumn('billing_country');
        });
    }
}
