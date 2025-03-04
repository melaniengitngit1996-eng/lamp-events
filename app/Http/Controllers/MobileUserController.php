<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MobileUser;

class MobileUserController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $user = MobileUser::where('username', $request->userName)->first();

        if ($user) {
            return response()->json(['error' => 'The username already exist.'], 500);
        }

        return MobileUser::create([
            'name' => $request->name,
            'username' => $request->userName,
            'local_church' => $request->localChurch
        ]);
    }
}
