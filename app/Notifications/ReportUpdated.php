<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportUpdated extends Notification
{
    use Queueable;

    public function __construct(protected Report $report) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $reporter = $this->report->user;
        $reported = $this->report->reportedUser;

        return [
            'report_id' => $this->report->id, // permet de mettre à jour la notification au lieu d'en créer une nouvelle
            'title'   => 'Signalement modifié',
            'message' => ($reporter?->name ?? 'Un utilisateur') . ' a modifié son signalement contre ' . ($reported?->name ?? 'un utilisateur') . '.',
            'url'     => route('admin.reports.show', $this->report),
        ];
    }
}
