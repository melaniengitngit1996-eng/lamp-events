<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventRegistrationCustomField;

class EventRegistrationCustomFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EventRegistrationCustomField::insert([
            [
                'event_id' => 2,
                'name' => 'participation_option',
                'type' => 'select',
                'options' => 'CCT,Online,HQ',
                'rule_message' => 'Please select an option',
                'default' => '',
                'label' => 'How will you attend the anniversary?',
                'export_header' => 'Participation Option'
            ],
            [
                'event_id' => 2,
                'name' => 'with_accommodation',
                'type' => 'select',
                'options' => 'yes,no',
                'rule_message' => 'Please select an option',
                'default' => 'no',
                'label' => 'with accommodation?',
                'export_header' => 'With Accommodation'
            ]
        ]);
    }
}
