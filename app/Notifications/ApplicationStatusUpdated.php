<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct($application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusLabel = match ($this->application->status) {
            'accepted' => 'acceptée',
            'rejected' => 'refusée',
            'pending' => 'remise en attente',
            default => 'mise à jour',
        };
        return [
            'application_id' => $this->application->id,
            'title' => 'Votre candidature pour '.$this->application->jobOffer->title.' est '.$statusLabel,
            'message' => 'L\'entreprise a modifié le statut de votre candidature.',
            'url' => route('applications.index'),
        ];
    }
}
