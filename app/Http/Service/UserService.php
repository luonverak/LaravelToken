<?php

namespace App\Http\Service;

use Illuminate\Http\Request;
use App\Models\User;

class UserService
{
    public function register(Request $request)
    {
        try {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => $request->password
            ]);
            return $user;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function login(Request $request)
    {
        try {
            $user = User::where("email", $request->email)->first();
            return $user;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getUser()
    {
        return auth()->user();
    }
    public function logout()
    {
        try {
            $cookie = cookie()->forget("jwt");
            request()->user()->currentAccessToken()->delete();
            return $cookie;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
