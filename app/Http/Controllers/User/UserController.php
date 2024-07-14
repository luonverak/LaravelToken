<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Service\UserService;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private $userService;
    public function __construct()
    {
        $this->userService = new UserService();
    }
    public function register(Request $request)
    {
        try {
            if (!$request->has("name") || !$request->has("email") || !$request->has("password")) {
                return response()->json([
                    "status" => "failed",
                    "msg" => "0.1 Something went wrong"
                ]);
            }
            $user = $this->userService->register($request);
            if (!$user) {
                return response()->json([
                    "status" => "failed",
                    "msg" => "0.2 Something went wrong"
                ]);
            }
            return response()->json([
                "status" => "success",
                "msg" => "Success"
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function login(Request $request)
    {
        try {
            if (!$request->has("email") || !$request->has("password")) {
                return response()->json([
                    "status" => "failed",
                    "msg" => "0.1 Something went wrong"
                ]);
            }
            $user = $this->userService->login($request);
            if (!$user) {
                return response()->json([
                    "status" => "failed",
                    "msg" => "0.2 Something went wrong"
                ]);
            }
            if (Hash::check($request->password, $user->getAuthPassword())) {
                // Delete old token
                if ($user->tokens()->exists()) {
                    $user->tokens()->delete();
                }
                // Create new token
                $token = $user->createToken("USER_TOKEN")->plainTextToken;
                $cookie = cookie("jwt", $token, 3);
                return response()->json([
                    "status" => "success",
                    "msg" => "Login success",
                    "user" => $user,
                    "token" => $token
                ])->withCookie($cookie);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function getUser()
    {
        return $this->userService->getUser();
    }
    public function logout()
    {
        try {
            $cookie = $this->userService->logout();
            return response()->json([
                "status" => "success",
                "msg" => "Logout success"
            ])->withCookie($cookie);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
