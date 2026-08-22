<?php

namespace Database\Seeders;

use App\Models\SlotLocalChurch;
use Illuminate\Database\Seeder;

class SlotLocalChurchSeeder extends Seeder
{
    public function run(): void
    {
        $churches = [
            'Bacolod',
            'Binan',
            'Canlubang',
            'Dasmarinas',
            'Granada',
            'Hinigaran',
            'Isabela',
            'Muntinlupa',
            'Pateros',
            'Tarlac',
        ];

        $slots = [61, 62, 63, 64];

        foreach ($slots as $slotId) {
            foreach ($churches as $church) {
                SlotLocalChurch::updateOrCreate(
                    [
                        'slot_id' => $slotId,
                        'local_church' => $church,
                    ],
                    [
                        'seat_count' => 30,
                    ]
                );
            }
        }
    }
}
