<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class Slack extends Notification
{
    protected $title;
    protected $exception;
    protected $meta;
    protected $chat;

    const ERROR   = 'error';
    const WARNING = 'warning';
    
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $error, $meta, $level='error')
    {
        $this->title     = $title . "\n";
        $this->exception = $error;
        foreach ($meta as $label => $e) {
            $this->meta .= $label . ": ";
            if (is_object($e) && method_exists($e, 'toArray')) {
                $this->meta .= '```' . json_encode($e->toArray()) . '```' . "\n";
            } else if (is_array($e) || is_object($e)) {
                $this->meta .= '```' . json_encode($e) . '```' . "\n";
            } else {
                $this->meta .=  $e . "\n";
            }
        }

        if ($level == self::WARNING) {
            $this->chat = '#warning_log';
        } else {
            $this->chat = '#error_log';
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
                    ->from('Laravel')
                    ->to($this->chat)
                    ->content($this->title)
                    ->attachment(function ($attachment) {
                        $attachment->title($this->exception)
                                   ->content($this->meta);
                    });
    }
    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
