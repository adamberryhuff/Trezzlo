<?php

namespace App;

use App\Notifications\Slack;
use Twilio\Rest\Client;
use Notification;
use App\Models\User\User;
use Mail;

class Helpers 
{  
    public static function mail ($address, $view) 
    {
        // check for debug
        if (env('APP_DEBUG') === true) {
            $address = env('DEBUG_EMAIL');
        }

        // send mail, push failures to slack
        try {
            Mail::to($address)->send($view);   
        } catch (\Exception $e) {
            $subject = 'SES Send Failure!';
            $error   = __FILE__ . ' @ ' . __LINE__ . ': ' . $e->getMessage();
            $meta    = [
                'address'    => $address, 
                'submission' => $view
            ];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
        }
    }

    public static function sms ($from, $to, $body)
    {
        // check for debug
        if (env('APP_DEBUG') === true) {
            $to = env('DEBUG_PHONE');
        }
        
        $sid         = env('TWILIO_SID'); 
        $token       = env('TWILIO_TOKEN'); 
        $client      = new Client($sid, $token);
        try {
            $message = $client->messages->create(
                $to,
                [
                    'from' => $from,
                    'body' => $body
                ]
            );
        } catch (\Exception $e) {
            $subject = 'Twilio Send Failure!';
            $error   = __FILE__ . ' @ ' . __LINE__ . ': ' . $e->getMessage();
            $meta    = [
                'from' => $from, 
                'to'   => $to,
                'body' => $body
            ];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
            return null;
        }
        return $message->sid;
    }
}
?>