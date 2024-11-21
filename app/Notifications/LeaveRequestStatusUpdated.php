<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LeaveRequestStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(private LeaveRequest $leaveRequest)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Mise à jour de votre demande de congé')
            ->line('Le statut de votre demande de congé a été mis à jour.')
            ->line('Nouveau statut: ' . ucfirst($this->leaveRequest->status));

        if ($this->leaveRequest->hr_comment) {
            $message->line('Commentaire RH: ' . $this->leaveRequest->hr_comment);
        }

        return $message->action('Voir les détails', url('/dashboard/leave-requests/' . $this->leaveRequest->id));
    }

    public function toArray($notifiable): array
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'status' => $this->leaveRequest->status,
            'hr_comment' => $this->leaveRequest->hr_comment
        ];
    }
}