<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCanToRolePermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id')->nullable()->change();
            $table->tinyInteger('can_view')->default(0)->nullable();
            $table->tinyInteger('can_create')->default(0)->nullable();
            $table->tinyInteger('can_update')->default(0)->nullable();
            $table->tinyInteger('can_delete')->default(0)->nullable();
            $table->text('module')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id')->change();
            $table->dropColumn('can_view');
            $table->dropColumn('can_create');
            $table->dropColumn('can_update');
            $table->dropColumn('can_delete');
            $table->dropColumn('module');
        });
    }
}
