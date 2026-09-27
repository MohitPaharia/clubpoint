<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Location;
use App\Support\Result;
// use App\Mail\EmailVerificationMail;
use Log;
use App\Services\MailtrapService;

class RegistrationService {

    private const TOKEN_VALIDITY_MINUTES = 10;

    public function generateEmailVerificationUrl(array $data): Result {
        return Result::success($data)
            ->bind([$this, 'validateCredentials'])
            ->bind([$this, 'storeData'])
            ->bind([$this, 'generateVerificationUrl'])
            ->bind([$this, 'sendEmailVerificationMail']);
    }

    public function verifyEmail(array $data): Result {
        return Result::success($data)
            ->bind([$this, 'validateToken'])
            ->bind([$this, 'registerUser']);
    }

    public function validateCredentials(array $data): Result 
    {
        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:30',
            'phone_number' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        return $validator->fails() 
            ? Result::failure($validator->errors()->toArray())
            : Result::success($validator->validated());
    }

    public function storeData(array $data): Result 
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'phone_number'      => $data['phone_number'],
            'address'    => $data['address'],
            'country_id' => $data['country'],
            'state_id'   => $data['state'],
            'city_id'    => $data['city'],
            'password'   => Hash::make($data['password']),
        ]);
        $data['user'] = $user;

        return Result::success($data);
    }

    public function generateVerificationUrl(array $data): Result 
    {
        $user = $data['user'];

        if (Cache::has("email_verification_token:{$user->id}")) {
            return Result::failure('An email verification link has recently been sent. Please check your email or try again later.');
        }

        $token = Str::random(60);
        Cache::put("email_verification_token:{$user->id}", $token, now()->addMinutes(self::TOKEN_VALIDITY_MINUTES));

        $data['token'] = $token;

        return Result::success($data);
    }

    public function sendEmailVerificationMail(array $data): Result 
    {
        $url = route('email.verify', [ 
            'token' => $data['token'],
            'email' => $data['user']->email,
        ]);

        try {
            $service = new MailtrapService();
            $service->sendVerificationMail($data['user']->email, $url);

            return Result::success('Email sent');
        } catch (Exception $e) {
            Log::error("MAILTRAP ERROR: " . $e->getMessage());
            return Result::failure('Could not send email');
        }
    }

    public function validateToken(array $data): Result
    {
        $data['user'] = User::where('email', $data['email'])->first();
        if (!$data['user']) {
            return Result::failure('User not found.');
        }

        $cacheKey = "email_verification_token:{$data['user']->id}";

        $cachedToken = Cache::get($cacheKey);

        if(!$cachedToken) {
            return Result::failure('The email verification link has expired. Please request a new one.');
        }

        if($data['token'] != $cachedToken) {
            return Result::failure('Invalid token.');
        }

        Cache::forget($cacheKey);

        return Result::success($data);
    }

    public function registerUser(array $data): Result 
    {
        $data['user']->email_verified_at = now();
        $data['user']->save();
        return Result::success($data);
    }
}
