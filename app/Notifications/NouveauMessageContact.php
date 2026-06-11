<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouveauMessageContact extends Notification
{
    public $contact;

    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => '📩 Nouveau message de contact',
            'nom' => $this->contact->nom,
            'email' => $this->contact->email,
            'id' => $this->contact->id,
        ];
    }
}