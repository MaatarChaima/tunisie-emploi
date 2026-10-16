<?php

namespace App\Notifications;

use App\Models\JobOffer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobOffer extends Notification
{
    use Queueable;

    protected $jobOffer;

    /**
     * Create a new notification instance.
     */
    public function __construct($jobOffer)
    {
        $this->jobOffer = $jobOffer;
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
        // Vérification de sécurité pour éviter les erreurs null
        if (!$this->jobOffer) {
            return [
                'title' => 'Notification d\'offre',
                'message' => 'Une offre d\'emploi a été publiée.',
            ];
        }

        return [
            'job_offer_id' => $this->jobOffer->id,
            'title' => 'Nouvelle offre d\'emploi : ' . $this->jobOffer->title,
            'message' => 'Une nouvelle offre d\'emploi qui pourrait vous intéresser a été publiée.',
            'url' => route('job-offers.show', $this->jobOffer->id),
        ];
    }
}
