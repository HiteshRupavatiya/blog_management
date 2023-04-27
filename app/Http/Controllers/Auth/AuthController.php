<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|min:3|max:30',
            'email'                 => 'required|email|unique:users,email|max:40',
            'password'              => 'required|min:5|max:20',
            'password_confirmation' => 'required|same:password'
        ]);

        $user = User::create($request->only(
            [
                'name',
                'email'
            ]
        ) + [
            'email_verified_at' => now(),
            'password'          => Hash::make($request->password),
            'remember_token'    => Str::random(10)
        ]);

        return ok('User registered successfully', $user);
    }

    public function login(Request $request)
    {
        $user = $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if (!auth()->attempt($user)) {
            return error('Invalid user credentials', type: 'notfound');
        }

        $token = auth()->user()->createToken('Api token')->plainTextToken;

        return ok('Logged in successfully', $token);
    }
}
