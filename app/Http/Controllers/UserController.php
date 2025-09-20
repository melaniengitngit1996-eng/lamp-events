<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * list all users
     *
     * @param  String $slug
     */
    public function index(Request $request)
    {
        $users = User::with('eventPermission', 'eventPermission.event', 'permissions');


        if ($request->search) {
            $users = $users->Where('email', 'LIKE', "%$request->search%");
        } else {
            $users = $users->where('name', 'LIKE', "%$request->search%");
        }

        $users = $users->paginate(10);

        // mask email
        $users->getCollection()->transform(function ($user) {
            $user->masked_email = $this->maskEmail($user->email);
            return $user;
        });

        return $users;
    }

    private function maskEmail($email)
    {
        if (!$email) return "";

        [$user, $domain] = explode('@', $email);
        $visible = substr($user, 0, 2);
        $masked = str_repeat('*', max(strlen($user) - 2, 0));

        return $visible . $masked . '@' . $domain;
    }

    public function update($id, Request $request) {
        $user = User::find($id);

        if ($user) {
            // -------- Event Permissions --------
            // Delete old permissions
            $user->eventPermission()->delete();

            // Create new permissions for each event ID
            $permissions = collect($request->events)->map(function ($eventId) {
                return [
                    'event_id' => $eventId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            // Insert new records
            $user->eventPermission()->createMany($permissions);

            // ----------- Permissions -----------
            $permission_config = config('permissions');

            $ids = collect($permission_config)->pluck('id');
            
            $access = [];

            foreach ($ids as $id) {
                $access[$id] = in_array($id, $request->permissions);
            }

            $user->permissions()->update($access);

            if ($request->password) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            }
        }

        return $user;
    }

    public function store(Request $request) {
        // -------- Create User --------
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : null,
        ]);
    
        if ($user) {
            // -------- Event Permissions --------
            $permissions = collect($request->events)->map(function ($eventId) {
                return [
                    'event_id' => $eventId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();
    
            $user->eventPermission()->createMany($permissions);
    
            // ----------- Permissions -----------
            $permission_config = config('permissions');
    
            $ids = collect($permission_config)->pluck('id');
    
            $access = [];
            foreach ($ids as $id) {
                $access[$id] = in_array($id, $request->permissions ?? []);
            }
    
            $user->permissions()->create($access);
        }
    
        return $user;
    }    

    /**
     * Delete user
     *
     * @param $id
     */
    public function destroy($id)
    {
        $user = User::find($id);

        return $user->delete();
    }
}
