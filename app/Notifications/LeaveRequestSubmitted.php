<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LeaveRequestSubmitted extends Notification
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
        return (new MailMessage)
            ->subject('Nouvelle demande de congé')
            ->line('Une nouvelle demande de congé a été soumise par ' . $this->leaveRequest->user->name)
            ->line('Type de congé: ' . $this->leaveRequest->type)
            ->line('Du: ' . $this->leaveRequest->start_date->format('d/m/Y'))
            ->line('Au: ' . $this->leaveRequest->end_date->format('d/m/Y'))
            ->action('Voir la demande', url('/dashboard/leave-requests/' . $this->leaveRequest->id));
    }

    public function toArray($notifiable): array
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'user_name' => $this->leaveRequest->user->name,
            'type' => $this->leaveRequest->type,
            'start_date' => $this->leaveRequest->start_date->format('Y-m-d'),
            'end_date' => $this->leaveRequest->end_date->format('Y-m-d')
        ];
    }
}