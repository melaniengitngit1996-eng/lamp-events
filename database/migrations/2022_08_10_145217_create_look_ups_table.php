<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLookUpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $member_booking_limit = env('MEMBER_BOOKING_LIMIT');

        Schema::create('look_ups', function (Blueprint $table) use ($member_booking_limit) {
            $table->string('lamp_id')->primary();
            $table->string('old_lamp_card_number')->nullable();
            $table->string('email')->nullable();
            $table->string('firstname');
            $table->string('lastname')->nullable();
            $table->string('fullname');
            $table->string('facebook_name')->nullable();
            $table->string('registration_type');
            $table->string('local_church');
            $table->string('cluster_group')->nullable();
            $table->string('country');
            $table->string('category');
            $table->integer('can_book_days')->default($member_booking_limit);
            $table->char('avail_new_lamp_id')->nullable();
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
        Schema::dropIfExists('look_ups');
    }
}
