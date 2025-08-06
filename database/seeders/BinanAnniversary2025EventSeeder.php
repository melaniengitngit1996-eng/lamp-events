<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Enums\AttendingOption;
use App\Enums\PaymentStatus;
use App\Models\Event;
use App\Models\Slots;
use App\Models\Rates;
use App\Models\EventRegistrationCustomField;

class BinanAnniversary2025EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $event = Event::create([
            'name' => 'LAMP Binan Anniversary 2025',
            'description' => 'Each One, Reach One',
            'slug' => '7382159077',
            'local_church' => 'General',
            'template_id' => 1,
            'theme' => 'Each One, Reach One',
            'close_registration' => 0,
            'with_guest_booking_code' => 1,
            'booking_code' => 'BINAN2025',
            'guest_booking_limit' => 0,
            'member_booking_limit' => 0,
            'active_guest_slot_id' => 5,
            'active_member_slot_id' => 1,
            'display_disclosure_prompt' => 1,
            'enable_online_checkin' => 0,
            'fb_group_url' => 'https://www.facebook.com/share/g/19ekGXfZn3/',
            'zoom_url' => 'https://us02web.zoom.us/j/83209757467',
            'zoom_id' => '832 0975 7467',
            'zoom_password' => 'lampbinpat',
            'with_booking' => 0,
            'show_attending_option' => 1,
            'banner_file_name' => '2024_awta_banner.png',
            'border_color' => '#316111',
            'form_description_block' => '',
            'main_venue' => 'C3',
            'venue_complete_address' => 'Carmona Community Center (C3)',
            'venue_map' => 'https://maps.app.goo.gl/Nt12qXz125qFLxza6',
            'has_multiple_venues' => 0,
            'payment_due_date' => 'October 12, 2025',
            'event_date' => 'October 19, 2025',
            'event_timing' => '9 AM',
            'hybrid_registration_deadline' => 'September 30, 2025',
            'rebooking_deadline' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Slots::insert([
            [
                'event_id' => $event->id,
                'description' => 'Day 1',
                'event_date' => date("Y-m-d", strtotime("10/19/2025")),
                'seat_count' => 600,
                'registration_type' => 'Member',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],
            [
                'event_id' => $event->id,
                'description' => 'Day 1',
                'event_date' => date("Y-m-d", strtotime("10/19/2025")),
                'seat_count' => 175,
                'registration_type' => 'Guest',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],
        ]);

        Rates::insert([
            [
                'event_id' => $event->id,
                'category' => 'Adult',
                'attending_option' => AttendingOption::Physical,
                'description' => 'Member (Physical)',
                'rate' => 500,
                'can_book_rate' => 250
            ], [
                'event_id' => $event->id,
                'category' => 'Adult',
                'attending_option' => AttendingOption::Online,
                'description' => 'Member (Online)',
                'rate' => 500,
                'can_book_rate' => 250
            ], [
                'event_id' => $event->id,
                'category' => 'Kids',
                'attending_option' => AttendingOption::Physical,
                'description' => 'Member age 5 - 8 yrs old (Physical)',
                'rate' => 250,
                'can_book_rate' => 125
            ], [
                'event_id' => $event->id,
                'category' => 'Kids',
                'attending_option' => AttendingOption::Online,
                'description' => 'Member age 5 - 8 yrs old (Online)',
                'rate' => 250,
                'can_book_rate' => 125
            ], [
                'event_id' => $event->id,
                'category' => PaymentStatus::Free,
                'attending_option' => AttendingOption::Physical,
                'description' => 'Visitor & Member 0 - 4 yrs old (Physical)',
                'rate' => 0,
                'can_book_rate' => 0
            ], [
                'event_id' => $event->id,
                'category' => PaymentStatus::Free,
                'attending_option' => AttendingOption::Online,
                'description' => 'Visitor & Member 0 - 4 yrs old (Online)',
                'rate' => 0,
                'can_book_rate' => 0
            ]
        ]);
    }
}
