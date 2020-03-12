<?php
/**
 * MessageController.php
 * For incoming messages
 *
 * @author Adam Berry-Huff <adamberryhuff@gmail.com>
 */
namespace App\Http\Controllers;

use Notification;
use App\Models\User\User;
use App\Notifications\Slack;
use App\Models\User\Contact;
use Illuminate\Http\Request;
use App\Models\Client\Handle;
use App\Models\Message\Medium;
use App\Mail\FeedbackSubmitted;
use App\Models\Message\Mechanism;
use Illuminate\Support\Facades\Mail;
use App\Models\User\Type as UserType;
use App\Models\Message\Inbound as InboundMessage;

/**
 * MessageController.php
 * For incoming messages
 *
 * @author Adam Berry-Huff <adamberryhuff@gmail.com>
 */
class MessageController extends Controller
{
    /**
     * incomingTwilio - parses an incoming twilio message and inserts to log.
     *                  inserting incoming message will trigger a model action
     *                  that will trigger a prospect response if appropriate.
     *
     * ToCountry, ToState, ToCity, ToZip, FromZip, FromState, FromCity, FromCountry, 
     * SmsMessageSid, SmsSid, SmsStatus, NumMedia, Body, To, NumSegments, MessageSid, 
     * AccountSid, From, ApiVersion
     * 
     * @return void
     */
    public function incomingTwilio () {
        $mechanism_id = Mechanism::TWILIO;
        $medium_id    = Medium::SMS;

        $this->validateRequest();

        // get client and handle
        $handle      = $this->getHandle($mechanism_id);
        $client_id   = isset($handle->client_id) ? $handle->client_id : null;
        $handle_id   = isset($handle->id) ? $handle->id : null;

        // get customer and contact
        $contact     = $this->getContact($medium_id, $client_id);
        $user_id     = isset($contact->user_id) ? $contact->user_id : null;
        $contact_id  = isset($contact->id) ? $contact->id : null;

        // log inbound message
        $insert = [
            'message_id'   => $_POST['MessageSid'],
            'client_id'    => $client_id,
            'handle_id'    => $handle_id,
            'user_id'      => $user_id,
            'contact_id'   => $contact_id,
            'medium_id'    => $medium_id,
            'mechanism_id' => $mechanism_id,
            'to'           => $_POST['To'],
            'from'         => $_POST['From'],
            'body'         => $_POST['Body']
        ];

        try {
            InboundMessage::create($insert);
        } catch (\Exception $e) {
            $subject = 'Inbound model save failure!';
            $meta    = ['insert' => $insert];
            $error   = __FILE__ . ' @ ' . __LINE__ . ': ' . $e->getMessage();
            Notification::send(User::first(), new Slack($subject, $error, $meta));
        }
    }

    /**
     * validateRequest - Validates the input data
     *
     * @return void
     */
    protected function validateRequest ()
    {
        // validate request
        if (!isset($_POST['From']) || !isset($_POST['To'])
            || !isset($_POST['Body']) || !isset($_POST['SmsStatus'])
            || !isset($_POST['MessageSid'])
            || !isset($_POST['AccountSid']) 
            || $_POST['AccountSid'] != 'AC454fee4fa86e8000d4b3b2021521daff'
            || $_POST['SmsStatus'] != 'received'
        ) {
            $subject = 'Crawlers on the Twilio endpoint!';
            $error   = __FILE__ . ' @ ' . __LINE__;
            $meta    = ['post' => $_POST];
            Notification::send(User::first(), new Slack($subject, $error, $meta, Slack::WARNING));
            exit;
        }
    }

    /**
     * getHandle - obtains the client handle
     *
     * @param integer $mechanism_id - the sending mechanism ID
     *
     * @return object
     */
    protected function getHandle ($mechanism_id)
    {
        return Handle::where('mechanism_id', $mechanism_id)
            ->where('handle', $_POST['To'])
            ->first();
    }

    /**
     * getContact - Obtains the customer ID from the handle
     *
     * @param integer $medium_id - the ID of the medium
     * @param integer $client_id - the ID of the client
     *
     * @return the customer ID
     */
    protected function getContact ($medium_id, $client_id)
    {
        if ($client_id) {
            $contact = Contact::where(['client_id' => $client_id])
                ->where(['contact' => $_POST['From']])
                ->where(['medium_id' => $medium_id])
                ->first();
            if (empty($contact)) {
                $insert = [
                    'client_id'    => $client_id,
                    'user_type_id' => UserType::TYPE_CUSTOMER
                ];
                try {
                    $customer = User::create($insert);
                } catch (\Exception $e) {
                    $subject = 'Users model save failed!';
                    $error   = __FILE__ . ' @ ' . __LINE__ . ': ' . $e->getMessage();
                    $meta    = ['insert' => $insert];
                    Notification::send(User::first(), new Slack($subject, $error, $meta));
                }
                if (isset($customer->id)) {
                    $insert = [
                        'client_id'   => $client_id,
                        'user_id'     => $customer->id,
                        'medium_id'   => $medium_id,
                        'contact'     => $_POST['From'],
                        'opted_in'    => 0
                    ];
                    try {
                        $contact = Contact::create($insert);
                    } catch (\Exception $e) {
                        $subject = 'Contact model save failed!';
                        $error   = __FILE__ . ' @ ' . __LINE__ . ': ' . $e->getMessage();
                        $meta    = ['insert' => $insert];
                        Notification::send(User::first(), new Slack($subject, $error, $meta));
                    }
                }

            }
        }
        return isset($contact) ? $contact : null;
    }
}
