<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        $superAdministrators = [
            'Melanie Ngitngit',
            'Key Sarmiento Garcia'
        ];

        $administrators = [
            'Alex Cello',
            'Linda Valetin',
            'Dhazle de Vera',
            'Uzzhel Entierro',
            'Ann Jose',
            'Jake Patrick Imperial',
            'John Michael Robles',
            'Bryan Sta Rosa',
            'Marj Frianeza'
        ];

        $canAddSlots = [
            'Alex Cello',
            'Linda Valetin',
            'Melanie Ngitngit',
            'Key Sarmiento Garcia',
            'Ann Jose',
            'Jake Patrick Imperial',
        ];

        foreach ($users as $user) {
            $isAdmin = in_array($user->name, $administrators);
            $isSuperAdmin = in_array($user->name, $superAdministrators);
            $canAddSlot = in_array($user->name, $canAddSlots);

            $user->permissions()->create([
                'can_edit_delegate' => $isAdmin || $isSuperAdmin,
                'can_delete_delegate' => $isAdmin || $isSuperAdmin,
                'can_delete_payment' => $isAdmin || $isSuperAdmin,
                'can_edit_delegate_config' => $isAdmin || $isSuperAdmin,
                'can_export_data' => true,
                'can_view_registrations' => true,
                'can_edit_lookup_data' => $isSuperAdmin,
                'can_add_lookup_data' => $isSuperAdmin,
                'can_add_slots' => $canAddSlot
            ]);
        }
    }
}
