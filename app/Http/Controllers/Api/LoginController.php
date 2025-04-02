<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileUser;

class LoginController extends Controller
{
    public function index(Request $request) {
        if (!$request->username) {
            return response()->json(['error' => 'Username is required.'], 403);
        }

        $user = MobileUser::where('username', $request->username)->first();

        if (empty($user)) {
            return response()->json([
                'status' => 'not found',
                'details' => []
            ], 403);
        }

        return response()->json([
            'status' => 'found',
            'details' => $user
        ]);
    }
}
