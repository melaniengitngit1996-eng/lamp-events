<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->boolean('can_edit_delegate')->default(false);
            $table->boolean('can_delete_delegate')->default(false);
            $table->boolean('can_delete_payment')->default(false);
            $table->boolean('can_export_data')->default(false);
            $table->boolean('can_view_registrations')->default(false);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('can_edit_delegate_config')->default(false);
            $table->boolean('can_edit_lookup_data')->default(false);
            $table->boolean('can_add_lookup_data')->default(false);
            $table->boolean('can_add_slots')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
