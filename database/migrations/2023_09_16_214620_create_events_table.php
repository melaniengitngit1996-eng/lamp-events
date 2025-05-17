<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug');
            $table->string('local_church');
            $table->integer('template_id')->nullable();
            $table->text('theme')->nullable();
            $table->boolean('close_registration')->default(false);
            $table->boolean('with_guest_booking_code')->default(true);
            $table->string('booking_code', 50)->nullable();
            $table->integer('guest_booking_limit')->default(0);
            $table->integer('member_booking_limit')->default(0);
            $table->integer('active_guest_slot_id')->nullable();
            $table->integer('active_member_slot_id')->nullable();
            $table->boolean('display_disclosure_prompt')->default(true);
            $table->integer('enable_online_checkin')->default(0);
            $table->string('fb_group_url')->nullable();
            $table->text('zoom_url')->nullable();
            $table->string('zoom_id', 20)->nullable();
            $table->string('zoom_password', 50)->nullable();
            $table->boolean('with_booking')->default(false);
            $table->string('banner_file_name')->nullable();
            $table->string('border_color', 50)->nullable();
            $table->text('form_description_block')->nullable();
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
        Schema::dropIfExists('events');
    }
}
