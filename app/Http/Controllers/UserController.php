<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function logout(Request $request)
    {
        $user = auth()->user();
        $user->tokens()->delete();
        return ok('You logged out successfully');
    }
}
