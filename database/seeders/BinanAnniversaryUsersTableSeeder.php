<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class BinanAnniversaryUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['name' => 'Fernalyn Hernandez', 'email' => 'fernalynhernandez08@gmail.com', 'password' => Hash::make('test123')],
            ['name' => 'Mitz Mamaspas', 'email' => 'mamaspasmitz@gmail.com', 'password' => Hash::make('test123')],
            ['name' => 'Elaine Mae Gatus', 'email' => 'elainemaegatus@gmail.com', 'password' => Hash::make('test123')],
            ['name' => 'Angelica Mamaspas', 'email' => 'maangelicamamaspas@gmail.com', 'password' => Hash::make('test123')],
            ['name' => 'Shekinah Garcia', 'email' => 'kinahgarcia03@gmail.com', 'password' => Hash::make('test123')],
            ['name' => 'JK Fria', 'email' => 'jkfria@gmail.com', 'password' => Hash::make('test123')],
            ['name' => 'Katheryn Parayno', 'email' => 'ktrn.parayno@gmail.com', 'password' => Hash::make('test123')],
            ['name' => 'Christine Beverly Dela Cruz', 'email' => 'Christ.lhyn.27@gmail.com', 'password' => Hash::make('test123')],
            ['name' => 'Pearl Evon', 'email' => 'cevonpearl@gmail.com ', 'password' => Hash::make('test123')],
            ['name' => 'Beverly Birot', 'email' => 'beverly_castano@yahoo.com', 'password' => Hash::make('test123')],
            ['name' => 'Michaella Rodel', 'email' => 'micaellarodel2000@gmail.com', 'password' => Hash::make('test123')],
        ];


        foreach ($users as $userData) {
            $user = User::create($userData);

            // attach eventPermission (event_id = 4)
            $user->eventPermission()->create([
                'event_id' => 4,
            ]);

            // attach permissions
            $user->permissions()->create([
                'can_edit_delegate' => 0,
                'can_delete_delegate' => 0,
                'can_delete_payment' => 0,
                'can_export_registrations' => 1,
                'can_view_registrations' => 1,
                'can_edit_delegate_config' => 0,
                'can_edit_lookup_data' => 0,
                'can_add_lookup_data' => 0,
                'can_add_slots' => 0,
            ]);
        }
    }
}
