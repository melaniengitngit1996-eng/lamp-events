<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * list all users
     *
     * @param  String $slug
     */
    public function index(Request $request)
    {
        $users = User::with('eventPermission', 'eventPermission.event');


        if ($request->search) {
            $users = $users->Where('email', 'LIKE', "%$request->search%");
        } else {
            $users = $users->where('name', 'LIKE', "%$request->search%");
        }

        $users = $users->paginate(10);

        // mask email
        $users->getCollection()->transform(function ($user) {
            $user->email = $this->maskEmail($user->email);
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
}
