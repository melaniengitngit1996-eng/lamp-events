<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'can_view_registrations',
        'can_edit_delegate',
        'can_delete_delegate',
        'can_delete_payment',
        'can_edit_delegate_config',
        'can_edit_lookup_data',
        'can_add_lookup_data',
        'can_add_slots',
        'can_export_data',
        'can_manage_users'
    ];

    protected $casts = [
        'can_view_registrations' => 'boolean',
        'can_edit_delegate' => 'boolean',
        'can_delete_delegate' => 'boolean',
        'can_delete_payment' => 'boolean',
        'can_edit_delegate_config' => 'boolean',
        'can_edit_lookup_data' => 'boolean',
        'can_add_lookup_data' => 'boolean',
        'can_add_slots' => 'boolean',
        'can_export_data' => 'boolean',
        'can_manage_users' => 'boolean',
    ];
}
