<?php

namespace App\Services;

use Twilio\Rest\Client;

class NotificationService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('app.twilio_sid', env('TWILIO_SID')),
            config('app.twilio_token', env('TWILIO_TOKEN'))
        );
    }

    public function sendSMS($to, $message)
    {
        return $this->twilio->messages->create($to, [
            'from' => env('TWILIO_FROM'),
            'body' => $message
        ]);
    }

    public function sendWhatsApp($to, $message)
    {
        return $this->twilio->messages->create('whatsapp:' . $to, [
            'from' => env('TWILIO_WHATSAPP'),
            'body' => $message
        ]);
    }
}
