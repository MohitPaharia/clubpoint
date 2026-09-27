<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class MailtrapService
{
    protected $client;
    protected $token;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://send.api.mailtrap.io/api/',
            'headers' => [
                'Authorization' => 'Bearer ' . env('MAILTRAP_API_TOKEN'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function sendVerificationMail(string $to, string $url)
    {
        try {
            $body = [
                "from" => [
                    "email" => env("MAILTRAP_SENDER"),
                    "name"  => "Your App"
                ],
                "to" => [
                    ["email" => $to]
                ],
                "subject" => "Verify Your Email",
                "html" => "<p>Click the link to verify your email:</p><a href='{$url}'>Verify Email</a>",
            ];

            $response = $this->client->post("send", ["json" => $body]);

            return json_decode($response->getBody(), true);

        } catch (Exception $e) {
            throw $e;
        }
    }
}
