<?php

namespace App\Notifications;

use App\Models\Complaint;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MeetingBookingRequest extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private readonly Complaint $complaint,
        private readonly Carbon $meetingDate,
        private readonly string $calendarEvent
    )
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('A meeting has been booked for Complaint #' . $this->complaint->complaint_number)
            ->line('Meeting Date: ' . $this->meetingDate->format('m/d/Y h:i A'))
            ->action('View Complaint', url(route('complaints.show', $this->complaint)))
            ->attachData($this->calendarEvent, 'invitation.ics', [
                'mime' => 'text/calendar',
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
