<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserTableSeeder::class,
            PermissionsTableSeeder::class,
            EventsTableSeeder::class,
            SlotsTableSeeder::class,
            RatesTableSeeder::class,
            AvailableUUIDsTableSeeder::class,
            LookUpTableSeeder::class
        ]);
    }
}
