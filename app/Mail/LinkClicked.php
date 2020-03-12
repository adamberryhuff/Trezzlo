<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\User\Contact;
use App\Models\Message\Medium;

class LinkClicked extends Mailable
{
    use Queueable, SerializesModels;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $url;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($model)
    {
        // first and last name
        $this->first_name = !empty($model->customer->first_name) ? $model->customer->first_name : 'Not Provided';
        $this->last_name  = !empty($model->customer->last_name) ? $model->customer->last_name : 'Not Provided';

        // get email and phone
        $contacts = Contact::where(['user_id' => $model->user_id])->get();
        foreach ($contacts as $contact) {
            if ($contact->medium_id == Medium::SMS) {
                $this->phone = $contact->contact;
            }
            if ($contact->medium_id == Medium::EMAIL) {
                $this->email = $contact->contact;
            }
        }
        $this->phone = !empty($this->phone) ? $this->phone : 'Not Provided';
        $this->email = !empty($this->email) ? $this->email : 'Not Provided';

        // get URL
        $this->url = !empty($model->link->redirect) ? $model->link->redirect : 'Not Provided';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('SYSTEM_EMAIL'), env('MAIL_FROM_NAME'))
            ->view('emails.link');
    }
}
