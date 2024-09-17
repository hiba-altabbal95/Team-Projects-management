<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Assign the 'user' role to the newly created user
      //  $user->assignRole('user');

        return JWTAuth::fromUser($user);
    }

    public function login(array $credentials)
    {
        
        // Attempt to log in the user with the provided credentials
        if (!JWTAuth::attempt($credentials)) {
            abort(400, 'Email & Password do not match our records.');
        }

        // Retrieve the authenticated user by email
        $user = User::where('email', $credentials['email'])->first();

        return JWTAuth::fromUser($user);


    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }
}
