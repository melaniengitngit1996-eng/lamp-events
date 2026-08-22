<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventPermission;
use App\Models\EventRegistrationCustomField;
use App\Models\EventVenue;
use App\Models\Rates;
use App\Models\Slot;
use App\Models\Slots;
use Illuminate\Database\Seeder;

class AnnualWorshipThanksgivingAssembly2026Seeder extends Seeder
{
    public function run(): void
    {
        $event = Event::create(
            [
                'name' => 'Annual Worship & Thanksgiving Assembly 2026',
                'description' => 'Growing the Body of Christ (Each One, Reach One)',
                'slug' => '1226292026',
                'local_church' => 'General',
                'template_id' => 4,
                'theme' => null,

                'close_registration' => false,
                'with_guest_booking_code' => true,
                'booking_code' => 'test',
                'guest_booking_limit' => 2,
                'member_booking_limit' => 2,

                'active_guest_slot_id' => 25,
                'active_member_slot_id' => 21,

                'display_disclosure_prompt' => false,
                'enable_online_checkin' => true,
                'online_checkin_end_time' => '21:00',
                'online_checkin_start_time' => '01:00',

                'fb_group_url' => 'https://www.facebook.com/groups/501471520759720',
                'zoom_url' => 'https://us02web.zoom.us/j/82911554835?pwd=VWNKYXhwOHdzaDVXbVkrNHc0VFdXdz09',
                'zoom_id' => '829 1155 4835',
                'zoom_password' => 'Vision10k',

                'with_booking' => false,
                'show_attending_option' => true,

                'banner_file_name' => '2025_awta_banner.jpeg',
                'border_color' => '#cb4417',

                'form_description_block' => <<<'HTML'
<p class="text-sm">
    BE BLESSED PHYSICALLY, MATERIALLY, & SPIRITUALLY <br/>
    Event Date: December 26-29, 2026 <br/>
    Event Place: Calamba Tent <br/>
    Theme: Growing the Body of Christ (Each One, Reach One)
</p>

<p class="text-sm mb-0">
    Chosen people of God in the Old Testament gather for a so-called solemn assembly (Leviticus 23:36, Joel 1:14) where
    "offering made by fire unto the Lord" are given to celebrate God. But with Christ's death as ultimate sacrifice for all,
    today, animal sacrifices are no longer offered. Yet true worshipers of God continue to offer & make fire in the form of
    praise, worship & thanksgiving. <br/><br/>

    Annually, LAMP Church gathers & invites every one to congregate for one purpose -- offer worship & thanksgiving to the Lord of lords!
</p>
HTML,

                'main_venue' => 'Calamba Tent',
                'venue_complete_address' => 'Calamba Tent, CMC Avenue, Crossing, Calamba City',
                'venue_map' => 'https://goo.gl/maps/avYUt5rPss9HDtDo7',
                'has_multiple_venues' => true,

                'payment_due_date' => 'November 30, 2026',
                'event_date' => 'December 26–29, 2026',
                'event_timing' => '4 PM',
                'hybrid_registration_deadline' => 'November 30, 2026',
                'rebooking_deadline' => 'December 25, 2026',

                'enable_id_issuance' => true,
                'available_id_set' => 2,
                'enable_zoom_registration' => true,

                'is_maintenance' => false,

                'attending_options' => [
                    [
                        'label' => 'Physical',
                        'disable_if' => false,
                    ],
                    [
                        'label' => 'Online',
                        'disable_if' => false,
                    ],
                ],

                'is_event_completed' => false,
                'allowed_for_attendance_default' => 'Physical',
                'ticket_file_name' => 'awta_ticket',

                'created_at' => null,
                'updated_at' => null,
            ]
        );

        // Event Venues
        EventVenue::insert([
            [
                'event_id' => $event->id,
                'venue' => 'Calamba Tent',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'event_id' => $event->id,
                'venue' => 'Local Church',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'event_id' => $event->id,
                'venue' => 'Cluster Based Satelite',
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);

        // Custom Fields
        EventRegistrationCustomField::create([
            'event_id' => $event->id,
            'name' => 'venue',
            'type' => 'select',
            'options' => 'Calamba Tent,Local Church,Cluster Based Satelite',
            'rule_message' => 'Please select the venue',
            'visibility_rule' => "ruleForm.attendingOption === 'Physical'",
            'mandatory_rule' => "ruleForm.attendingOption === 'Physical'",
            'default' => null,
            'label' => 'Venue',
            'description' => null,
            'export_header' => 'Venue',
            'created_at' => null,
            'updated_at' => null,
        ]);

        EventRegistrationCustomField::create([
            'event_id' => $event->id,
            'name' => 'reason_for_online_attendance',
            'type' => 'select',
            'options' => 'Churches from Visayas,Distant regional clusters like Mindanao and Leyte,International delegate,Medical Reason',
            'rule_message' => 'Please select your reason',
            'visibility_rule' => "ruleForm.attendingOption === 'Online' && ruleForm.registrationType === 'Member'",
            'mandatory_rule' => "ruleForm.attendingOption === 'Online' && ruleForm.registrationType === 'Member'",
            'default' => null,
            'label' => 'Reason for attending online',
            'description' => null,
            'export_header' => 'Reason for Online Attendance',
            'created_at' => null,
            'updated_at' => null,
        ]);

        // User Permissions
        $userIds = array_merge(range(1, 51), [65]);

        foreach ($userIds as $userId) {
            EventPermission::create([
                'user_id' => $userId,
                'event_id' => $event->id,
                'created_at' => '2026-08-15 14:56:07',
                'updated_at' => '2026-08-15 14:56:07',
            ]);
        }

        // Member slots
        $memberDay1 = Slots::create([
            'event_id' => $event->id,
            'description' => 'Day 1',
            'event_date' => '2026-12-26',
            'seat_count' => 800,
            'registration_type' => 'Member',
            'activities' => null,
            'ticket_color' => '#006600',
        ]);

        Slots::create([
            'event_id' => $event->id,
            'description' => 'Day 2',
            'event_date' => '2026-12-27',
            'seat_count' => 800,
            'registration_type' => 'Member',
            'activities' => null,
            'ticket_color' => null,
        ]);

        Slots::create([
            'event_id' => $event->id,
            'description' => 'Day 3',
            'event_date' => '2026-12-28',
            'seat_count' => 800,
            'registration_type' => 'Member',
            'activities' => null,
            'ticket_color' => null,
        ]);

        Slots::create([
            'event_id' => $event->id,
            'description' => 'Day 4',
            'event_date' => '2026-12-29',
            'seat_count' => 800,
            'registration_type' => 'Member',
            'activities' => null,
            'ticket_color' => null,
        ]);

        // Guest slots
        $guestDay1 = Slots::create([
            'event_id' => $event->id,
            'description' => 'Day 1',
            'event_date' => '2026-12-26',
            'seat_count' => 300,
            'registration_type' => 'Guest',
            'activities' => null,
            'ticket_color' => '#006600',
        ]);

        Slots::create([
            'event_id' => $event->id,
            'description' => 'Day 2',
            'event_date' => '2026-12-27',
            'seat_count' => 300,
            'registration_type' => 'Guest',
            'activities' => null,
            'ticket_color' => null,
        ]);

        Slots::create([
            'event_id' => $event->id,
            'description' => 'Day 3',
            'event_date' => '2026-12-28',
            'seat_count' => 300,
            'registration_type' => 'Guest',
            'activities' => null,
            'ticket_color' => null,
        ]);

        Slots::create([
            'event_id' => $event->id,
            'description' => 'Day 4',
            'event_date' => '2026-12-29',
            'seat_count' => 300,
            'registration_type' => 'Guest',
            'activities' => null,
            'ticket_color' => null,
        ]);

        // Set active slots to Day 1
        $event->update([
            'active_member_slot_id' => $memberDay1->id,
            'active_guest_slot_id' => $guestDay1->id,
        ]);

        // Rates
        $rates = [
            // Online
            [
                'category' => 'Adult',
                'attending_option' => 'Online',
                'registration_type' => 'Member',
                'venue' => 'Online',
                'description' => 'Member (Online)',
                'rate' => 100.00,
                'can_book_rate' => 100.00,
            ],
            [
                'category' => 'Infant',
                'attending_option' => 'Online',
                'registration_type' => 'Member',
                'venue' => 'Online',
                'description' => 'Member age 0 - 4 yrs old (Online)',
                'rate' => 0.00,
                'can_book_rate' => 0.00,
            ],
            [
                'category' => 'Kids',
                'attending_option' => 'Online',
                'registration_type' => 'Member',
                'venue' => 'Online',
                'description' => 'Member age 5 - 8 yrs old (Online)',
                'rate' => 50.00,
                'can_book_rate' => 50.00,
            ],
            [
                'category' => 'Adult',
                'attending_option' => 'Online',
                'registration_type' => 'Guest',
                'venue' => 'Online',
                'description' => 'Visitor (Online)',
                'rate' => 0.00,
                'can_book_rate' => 0.00,
            ],

            // Calamba Tent
            [
                'category' => 'Adult',
                'attending_option' => 'Physical',
                'registration_type' => 'Member',
                'venue' => 'Calamba Tent',
                'description' => 'Member (Calamba Tent)',
                'rate' => 900.00,
                'can_book_rate' => 900.00,
            ],
            [
                'category' => 'Infant',
                'attending_option' => 'Physical',
                'registration_type' => 'Member',
                'venue' => 'Calamba Tent',
                'description' => 'Member age 0 - 4 yrs old (Calamba Tent)',
                'rate' => 0.00,
                'can_book_rate' => 0.00,
            ],
            [
                'category' => 'Kids',
                'attending_option' => 'Physical',
                'registration_type' => 'Member',
                'venue' => 'Calamba Tent',
                'description' => 'Member age 5 - 8 yrs old (Calamba Tent)',
                'rate' => 450.00,
                'can_book_rate' => 450.00,
            ],
            [
                'category' => 'Adult',
                'attending_option' => 'Physical',
                'registration_type' => 'Guest',
                'venue' => 'Calamba Tent',
                'description' => 'Visitor (Calamba Tent)',
                'rate' => 0.00,
                'can_book_rate' => 0.00,
            ],

            // Cluster Based Satellite
            [
                'category' => 'Adult',
                'attending_option' => 'Physical',
                'registration_type' => 'Member',
                'venue' => 'Cluster Based Satellite',
                'description' => 'Member (Local Church)',
                'rate' => 100.00,
                'can_book_rate' => 100.00,
            ],
            [
                'category' => 'Infant',
                'attending_option' => 'Physical',
                'registration_type' => 'Member',
                'venue' => 'Cluster Based Satellite',
                'description' => 'Member age 0 - 4 yrs old (Local Church)',
                'rate' => 0.00,
                'can_book_rate' => 0.00,
            ],
            [
                'category' => 'Kids',
                'attending_option' => 'Physical',
                'registration_type' => 'Member',
                'venue' => 'Cluster Based Satellite',
                'description' => 'Member age 5 - 8 yrs old (Local Church)',
                'rate' => 50.00,
                'can_book_rate' => 50.00,
            ],
            [
                'category' => 'Adult',
                'attending_option' => 'Physical',
                'registration_type' => 'Guest',
                'venue' => 'Cluster Based Satellite',
                'description' => 'Visitor (Local Church)',
                'rate' => 0.00,
                'can_book_rate' => 0.00,
            ],
        ];

        foreach ($rates as $rate) {
            Rates::create([
                'event_id' => $event->id,
                ...$rate,
            ]);
        }
    }
}
