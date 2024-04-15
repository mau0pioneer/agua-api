<?php

namespace App\Services;

use Illuminate\Http\Client\Factory as HttpClient;

class SendGridService
{
    protected $apiKey;
    protected $httpClient;

    public function __construct()
    {
        $this->apiKey = env('SENDGRID_API_KEY');
        $this->httpClient = new HttpClient();
    }

    public function sendEmail($to, $subject, $content)
    {
        $url = 'https://api.sendgrid.com/v3/mail/send';

        $data = [
            'personalizations' => [
                [
                    'to' => [
                        ['email' => $to]
                    ]
                ]
            ],
            'from' => ['email' => "marmau.pioneertech@gmail.com"],
            'subject' => $subject,
            'content' => [
                [
                    'type' => 'text/plain',
                    'value' => $content
                ]
            ]
        ];

        $response = $this->httpClient->withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, $data);

        return $response->json();
    }
}
