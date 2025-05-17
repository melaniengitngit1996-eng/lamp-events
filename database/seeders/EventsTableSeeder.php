<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('events')->insert([
            [
                'id' => 1,
                'name' => 'Annual Worship & Thanksgiving Assembly 2025',
                'description' => null,
                'slug' => '3847934834',
                'local_church' => 'General',
                'template_id' => 1,
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
                'updated_at' => null,
            ],
            [
                'id' => 2,
                'name' => 'LAMP Church 38th Anniversary 2025',
                'description' => null,
                'slug' => '9876545674',
                'local_church' => 'General',
                'template_id' => 1,
                'theme' => null,
                'close_registration' => false,
                'with_guest_booking_code' => false,
                'booking_code' => null,
                'guest_booking_limit' => 0,
                'member_booking_limit' => 0,
                'active_guest_slot_id' => 11,
                'active_member_slot_id' => 9,
                'display_disclosure_prompt' => false,
                'fb_group_url' => null,
                'zoom_url' => null,
                'zoom_id' => null,
                'zoom_password' => null,
                'with_booking' => false,
                'banner_file_name' => '2025_anniversary_banner.png',
                'border_color' => '#DA6209',
                'form_description_block' => '<p class="text-sm pb-0 mb-0">
                    BE BLESSED PHYSICALLY, MATERIALLY, &amp; SPIRITUALLY <br data-v-1d9a9682="">
                    Event Date: June 21–22, 2025 <br data-v-1d9a9682="">
                    Event Place: CCT Tagaytay Retreat And Training Center
                </p>',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 3,
                'name' => 'Family Survival Camp',
                'description' => null,
                'slug' => '6789876543',
                'local_church' => 'Muntinlupa',
                'template_id' => 3,
                'theme' => null,
                'close_registration' => false,
                'with_guest_booking_code' => true,
                'booking_code' => null,
                'guest_booking_limit' => 0,
                'member_booking_limit' => 0,
                'active_guest_slot_id' => null,
                'active_member_slot_id' => null,
                'display_disclosure_prompt' => true,
                'fb_group_url' => null,
                'zoom_url' => null,
                'zoom_id' => null,
                'zoom_password' => null,
                'with_booking' => false,
                'banner_file_name' => null,
                'border_color' => null,
                'form_description_block' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);
    }
}
