<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->tinyInteger('show_attending_option')->default(0)->after('with_booking');
            $table->string('main_venue')->nullable()->after('banner_file_name');
            $table->text('venue_complete_address')->nullable()->after('main_venue');
            $table->text('venue_map')->nullable()->after('venue_complete_address');
            $table->boolean('has_multiple_venues')->default(0)->after('venue_map');
            $table->string('payment_due_date', 100)->nullable()->after('has_multiple_venues');
            $table->string('event_date', 100)->nullable()->after('payment_due_date');
            $table->string('event_timing', 100)->nullable()->after('event_date');
            $table->string('hybrid_registration_deadline', 100)->nullable()->after('event_timing');
            $table->string('rebooking_deadline', 100)->nullable()->after('hybrid_registration_deadline');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'show_attending_option',
                'main_venue',
                'venue_complete_address',
                'venue_map',
                'has_multiple_venues',
                'payment_due_date',
                'event_date',
                'event_timing',
                'hybrid_registration_deadline',
                'rebooking_deadline',
            ]);
        });
    }
}
