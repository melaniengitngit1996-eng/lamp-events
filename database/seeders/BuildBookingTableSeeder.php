<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Registration;
use Illuminate\Database\Seeder;

class BuildBookingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $registrations = Registration::all();

        foreach ($registrations as $registration) {
            Booking::create([
                'event_id' => 2,
                'registration_id' => $registration->id,
                'slot_id' => 9,
                'local_church' => $registration->local_church,
                'status' => BookingStatus::Confirmed
            ]);

            Booking::create([
                'event_id' => 2,
                'registration_id' => $registration->id,
                'slot_id' => 10,
                'local_church' => $registration->local_church,
                'status' => BookingStatus::Confirmed
            ]);
        }
    }
}
