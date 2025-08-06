<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Enums\AttendingOption;
use App\Enums\PaymentStatus;
use App\Models\Event;
use App\Models\Slots;
use App\Models\Rates;
use App\Models\EventRegistrationCustomField;

class AWTA2025EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $event = Event::create([
                'name' => 'Annual Worship & Thanksgiving Assembly 2025',
                'description' => 'Growing the Body of Christ (Each One, Reach One)',
                'slug' => '7382159074',
                'local_church' => 'General',
                'template_id' => 4,
                'theme' => null,
                'close_registration' => false,
                'with_guest_booking_code' => true,
                'booking_code' => 'test',
                'guest_booking_limit' => 0,
                'member_booking_limit' => 0,
                'active_guest_slot_id' => 5,
                'active_member_slot_id' => 1,
                'display_disclosure_prompt' => true,
                'fb_group_url' => null,
                'zoom_url' => null,
                'zoom_id' => null,
                'zoom_password' => null,
                'with_booking' => true,
                'banner_file_name' => '2024_awta_banner.png',
                'border_color' => '#316111',
                'form_description_block' => '<p class="text-sm">
            BE BLESSED PHYSICALLY, MATERIALLY, & SPIRITUALLY <br/>
            Event Date: December 26-30, 2024 <br/>
            Event Place: Calamba Tent <br/>
            Theme: Growing in the body of Christ
        </p>

        <p class="text-sm mb-0">
            Chosen people of God in the Old Testament gather for a so-called solemn assembly (Leviticus 23:36, Joel 1:14) where "offering made by fire unto the Lord" are given to celebrate God. But with Christ\'s death as ultimate sacrifice for all, today, animal sacrifices are no longer offered. Yet true worshipers of God continue to offer & make fire in the form of praise, worship & thanksgiving. <br/><br/>

            Annually, LAMP Church gathers & invites every one to congregate for one purpose -- offer worship & thanksgiving to the Lord of lords!
        </p>',
                'created_at' => null,
                'updated_at' => null
        ]);

        Slots::insert([
            [
                'event_id' => $event->id,
                'description' => 'Day 1',
                'event_date' => date("Y-m-d", strtotime("12/26/2025")),
                'seat_count' => 600,
                'registration_type' => 'Member',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],

            [
                'event_id' => $event->id,
                'description' => 'Day 2',
                'event_date' => date("Y-m-d", strtotime("12/27/2025")),
                'seat_count' => 600,
                'registration_type' => 'Member',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],
            [
                'event_id' => $event->id,
                'description' => 'Day 3',
                'event_date' => date("Y-m-d", strtotime("12/28/2025")),
                'seat_count' => 600,
                'registration_type' => 'Member',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],
            [
                'event_id' => $event->id,
                'description' => 'Day 4',
                'event_date' => date("Y-m-d", strtotime("12/29/2025")),
                'seat_count' => 600,
                'registration_type' => 'Member',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],
            [
                'event_id' => $event->id,
                'description' => 'Day 1',
                'event_date' => date("Y-m-d", strtotime("12/26/2025")),
                'seat_count' => 175,
                'registration_type' => 'Guest',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],
            [
                'event_id' => $event->id,
                'description' => 'Day 2',
                'event_date' => date("Y-m-d", strtotime("12/27/2025")),
                'seat_count' => 175,
                'registration_type' => 'Guest',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],
            [
                'event_id' => $event->id,
                'description' => 'Day 3',
                'event_date' => date("Y-m-d", strtotime("12/28/2025")),
                'seat_count' => 175,
                'registration_type' => 'Guest',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ],
            [
                'event_id' => $event->id,
                'description' => 'Day 4',
                'event_date' => date("Y-m-d", strtotime("12/29/2025")),
                'seat_count' => 175,
                'registration_type' => 'Guest',
                'updated_at' => NOW(),
                'created_at' => NOW()
            ]
        ]);

        Rates::insert([
            [
                'event_id' => $event->id,
                'category' => 'Adult',
                'attending_option' => AttendingOption::Physical,
                'description' => 'Member (Physical)',
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
                'attending_option' => AttendingOption::Physical,
                'description' => 'Member age 5 - 8 yrs old (Physical)',
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

        EventRegistrationCustomField::insert([
            [
                'event_id' => $event->id,
                'name' => 'reason_for_online_attendance',
                'type' => 'select',
                'options' => 'Churches from Visayas,Distant regional clusters like Mindanao and Leyte,International delegate,Medical Reason',
                'rule_message' => 'Please select your reason',
                'default' => '',
                'label' => 'Reason for attending online',
                'description' => '',
                'export_header' => 'Reason for Online Attendance'
            ],
            [
                'event_id' => $event->id,
                'name' => 'zoom_access_email',
                'type' => 'text',
                'options' => null,
                'rule_message' => 'Please input the email address',
                'default' => '',
                'label' => 'Email Address to Receive Zoom Details',
                'description' => 'Please provide the email address where you would like to receive the zoom link.',
                'export_header' => 'Zoom Access Email'
            ]
        ]);
    }
}
