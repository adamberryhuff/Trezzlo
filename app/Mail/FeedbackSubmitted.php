<?php

namespace App\Mail;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\User\Contact;
use App\Models\User\User;
use App\Models\Message\Medium;

class FeedbackSubmitted extends Mailable
{
    use Queueable, SerializesModels;
    public $first_name;
    public $last_name;
    public $feedback;
    public $email;
    public $phone;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($record)
    {   
        // get feedback
        $this->feedback = $record->feedback;

        // get first and last name
        $customer = User::find($record->user_id);
        $this->first_name = $customer->first_name;
        $this->last_name  = $customer->last_name;

        // get email and phone
        $contacts = Contact::where(['user_id' => $record->user_id])->get();
        foreach ($contacts as $contact) {
            if ($contact->medium_id == Medium::SMS) {
                $this->phone = $contact->contact;
            }
            if ($contact->medium_id == Medium::EMAIL) {
                $this->email = $contact->contact;
            }
        }

        $this->email      = empty($this->email) ? 'Not Provided' : $this->email;
        $this->phone      = empty($this->phone) ? 'Not Provided' : $this->phone;
        $this->first_name = empty($this->first_name) ? 'Not Provided' : $this->first_name;
        $this->last_name  = empty($this->last_name) ? 'Not Provided' : $this->last_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('SYSTEM_EMAIL'), env('MAIL_FROM_NAME'))
                    ->view('emails.feedback');
    }
}
