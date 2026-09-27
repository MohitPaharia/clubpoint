<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Support\Result;
use Illuminate\Support\Facades\Auth;

class LoginService 
{    
    public function attempt(array $credentials) : Result 
    {
        return Result::success($credentials)
            ->bind([$this, 'validateCredentials'])
            ->bind([$this, 'findUser'])
            ->bind([$this, 'verifyPassword'])
            ->bind([$this, 'createSession']);
    }

    public function validateCredentials(array $credentials) : Result
    { 
        $validator = Validator::make($credentials, [
            'email'    => 'required|email|max:255',
            'password' => 'required|min:8|max:255',
        ]);
    
        return $validator->fails()
            ? Result::failure($validator->errors()->toArray())
            : Result::success($validator->validated());
    }

    public function findUser(array $credentials) : Result 
    {
        $user = User::where('email', $credentials['email'])->first();
        return $user
            ? Result::success(['user' => $user, 'password' => $credentials['password']])
            : Result::failure('Invalid Credentials.');
    }

    public function verifyPassword(array $data) : Result 
    {
        return (Hash::check($data['password'], $data['user']->password)) 
            ? Result::success($data['user'])
            : Result::failure('Invalid Credentials.');
    }

    public function createSession(User $user) : Result 
    {
        Auth::login($user);        
        return Result::success($user);
    }

}
