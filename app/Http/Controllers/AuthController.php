<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $emailInput = $validated['email'];
        $passwordInput =  $validated['password'];

        $checkUser = Auth::attempt(['email' => $emailInput, 'password' => $passwordInput]);

        if ($checkUser) {
            $user = Auth::user();

            return (new UserResponse($user))->additional(['meta' => [
                'access_token' => $user->createToken('Personal Access Token')->accessToken,
            ]]);
        } else {
            return response()->json(['error'=>'failed to login'], 401);
        }
    }

    /**
     * Logout user.
     */
    public function logout()
    {
        Auth::user()->token()->revoke();
        Auth::user()->token()->delete();
    }
}
