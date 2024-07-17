<?php
// app/Notifications/AdminNotification.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $link;

    public function __construct($message, $link = null)
    {
        $this->message = $message;
        $this->link = $link;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->line($this->message);

        if ($this->link) {
            $mail->action('Voir plus', url($this->link));
        }

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'link' => $this->link,
        ];
    }
}
