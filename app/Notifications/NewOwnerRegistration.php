<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOwnerRegistration extends Notification implements ShouldQueue
{
    use Queueable;

    protected $newOwner;

    public function __construct(User $newOwner)
    {
        $this->newOwner = $newOwner;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $categories = $this->newOwner->categories->pluck('name')->implode(', ');
        
        return (new MailMessage)
            ->subject('New Pet Owner Registration Requires Approval')
            ->greeting('Hello ' . $notifiable->complete_name)
            ->line('A new pet owner has registered and requires your approval.')
            ->line('Owner Details:')
            ->line("Name: {$this->newOwner->complete_name}")
            ->line("Email: {$this->newOwner->email}")
            ->line("Contact: {$this->newOwner->contact_no}")
            ->action('Review Registration', url('/admin/owners/pending'));
    }
} 