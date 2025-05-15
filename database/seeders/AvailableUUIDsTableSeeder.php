<?php

namespace Database\Seeders;

use App\Models\AvailableUuid;
use App\Models\LookUp;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AvailableUUIDsTableSeeder extends Seeder
{
    public function run()
    {
        AvailableUuid::create([
            'year' => '2025',
            'prefix' => 'LAMP',
            'start' => 1,
            'end' => 10000,
            'cursor' => 1831
        ]);
    }
}
