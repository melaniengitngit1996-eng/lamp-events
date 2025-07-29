<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
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
                'description' => 'Growing the Body of Christ (Each One, Reach One)',
                'slug' => '7382159077',
                'local_church' => 'General',
                'template_id' => 1,
                'theme' => null,
                'close_registration' => false,
                'with_guest_booking_code' => true,
                'booking_code' => 'BINAN2025',
                'guest_booking_limit' => 0,
                'member_booking_limit' => 0,
                'active_guest_slot_id' => 5,
                'active_member_slot_id' => 1,
                'display_disclosure_prompt' => true,
                'fb_group_url' => null,
                'zoom_url' => null,
                'zoom_id' => null,
                'zoom_password' => null,
                'with_booking' => false,
                'banner_file_name' => '2024_awta_banner.png',
                'border_color' => '#316111',
                'form_description_block' => '',
                'created_at' => null,
                'updated_at' => null
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
                'attending_option' => AttendingOption::Hybrid,
                'description' => 'Member (Hybrid)',
                'rate' => 950,
                'can_book_rate' => 475
            ], [
                'event_id' => $event->id,
                'category' => 'Adult',
                'attending_option' => AttendingOption::Online,
                'description' => 'Member (Online)',
                'rate' => 100,
                'can_book_rate' => 100
            ], [
                'event_id' => $event->id,
                'category' => 'Kids',
                'attending_option' => AttendingOption::Hybrid,
                'description' => 'Member age 5 - 8 yrs old (Hybrid)',
                'rate' => 475,
                'can_book_rate' => 237.5
            ], [
                'event_id' => $event->id,
                'category' => 'Kids',
                'attending_option' => AttendingOption::Online,
                'description' => 'Member age 5 - 8 yrs old (Online)',
                'rate' => 50,
                'can_book_rate' => 50
            ], [
                'event_id' => $event->id,
                'category' => PaymentStatus::Free,
                'attending_option' => AttendingOption::Hybrid,
                'description' => 'Visitor & Member 0 - 4 yrs old (Hybrid)',
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
