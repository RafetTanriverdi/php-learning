<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = auth('api')->login($user);

        return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];

        if (! $token = auth('api')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Email veya şifre hatalı.'],
            ]);
        }

        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(
            auth('api')->user()
        );
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'message' => 'Çıkış yapıldı.',
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(
            auth('api')->refresh()
        );
    }

    protected function respondWithToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
