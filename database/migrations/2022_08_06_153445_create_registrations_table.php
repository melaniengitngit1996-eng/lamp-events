<?php

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id');
            $table->string('uuid');
            $table->string('email')->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('fullname');
            $table->string('facebook_name')->nullable();
            $table->string('registration_type');
            $table->string('local_church');
            $table->string('cluster_group')->nullable();
            $table->string('country');
            $table->string('category');
            $table->string('attending_option');
            $table->decimal('rate', 9, 3)->default(0.000);
            $table->string('payment_status')->default(PaymentStatus::Unsettled);
            $table->string('booking_status')->default(BookingStatus::Pending);
            $table->string('with_awta_card')->default('none');
            $table->decimal('can_book_rate', 9, 3)->default(0.000);
            $table->integer('can_book_days')->default(2);
            $table->integer('rebooking_limit')->default(3);
            $table->boolean('is_booking_bypassed')->default(false);
            $table->date('visitor_to_member')->nullable();
            $table->date('is_received_hg')->nullable();
            $table->text('medical_assistance_needed')->nullable();
            $table->text('notes');
            $table->text('activities');
            $table->text('booking_activities');
            $table->timestamp('booked_date')->nullable();
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
        Schema::dropIfExists('registrations');
    }
}
