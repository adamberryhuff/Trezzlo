<?php
/**
 * FeedbackController.php
 * Controller for the client customer feedback form
 *
 * @author Adam Berry-Huff <adamberryhuff@gmail.com>
 */
namespace App\Http\Controllers;

use Validator;
use App\Helpers;
use Notification;
use App\Models\User\User;
use App\Models\User\Contact;
use App\Notifications\Slack;
use Illuminate\Http\Request;
use App\Models\User\Feedback;
use App\Models\Message\Medium;
use App\Models\Link\Instance as LinkInstance;

/**
 * FeedbackController
 * Controller for the client customer feedback form
 *
 * @author Adam Berry-Huff <adamberryhuff@gmail.com>
 */
class FeedbackController extends Controller
{
    // objects
    protected $customer;
    protected $feedback;
    protected $client;
    protected $contacts;

    // fields
    protected $first_name = '';
    protected $last_name  = '';
    protected $dfeedback  = '';
    protected $phone      = '';
    protected $email      = '';

    /**
     * feedback - function that holds the main flow of the form. handles submission and render
     *
     * @param Request $request - The HTTP request
     * @param string  $data    - The Signed Request string
     *
     * @return view
     */
    public function feedback (Request $request, $data)
    {  
        $errors = [];
        
        // validate link
        $link = $this->validateUrl($data);

        // validate client and customer
        $this->validateClient($link);
        $this->validateCustomer($link);
        $this->validateCustomerContacts();
        $this->validateFeedback($link->customer);

        // set form attributes
        $this->first_name = isset($_GET['first_name']) ? $_GET['first_name'] : $this->first_name;
        $this->last_name  = isset($_GET['last_name']) ? $_GET['last_name'] : $this->last_name;
        $this->phone      = isset($_GET['phone']) ? $_GET['phone'] : $this->phone;
        $this->email      = isset($_GET['email']) ? $_GET['email'] : $this->email;
        $this->dfeedback  = isset($_GET['comments']) ? $_GET['comments'] : $this->dfeedback;

        // validate, save, and notify
        if (isset($_GET['submit'])) {
            $errors = $this->validateRequest();
            if (empty($errors)) {
                $errors = $this->saveRecords($errors);
            }
        }

        $disabled = '';
        if (empty($errors)) {
            $link->fresh();
            $disabled = !empty($this->dfeedback) ? 'disabled' : '';
        }

        // render view
        return view(
            'feedback',
            [
                'client'     => $this->client->name,
                'feedback'   => $this->dfeedback,
                'first_name' => $this->first_name,
                'last_name'  => $this->last_name,
                'phone'      => $this->phone,
                'email'      => $this->email,
                'errors'     => $errors,
                'disabled'   => $disabled
            ]
        );
    }

    /**
     * validateUrl - validates the signed request
     *
     * @param string $sr - the signed request string
     * 
     * @return $link model
     */
    protected function validateUrl($sr)
    {
        $link = LinkInstance::where('url_id', $sr)->orderBy('id', 'desc')->first();
        if (empty($link)) {
            $subject = 'Crawlers on the feedback page!';
            $error   = __FILE__ . ' @ ' . __LINE__;
            $meta    = ['sr' => $sr];
            Notification::send(User::first(), new Slack($subject, $error, $meta, Slack::WARNING));
            exit;
        }
        return $link;
    }

    /**
     * validateClient - ensures that the link belongs to a validate client
     *
     * @param object $link - The link model
     *
     * @return void
     */
    protected function validateClient ($link)
    {
        if (empty($link->client)) {
            $subject = 'Feedback link not associated with client!';
            $error   = __FILE__ . ' @ ' . __LINE__;
            $meta    = ['url' => $link];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
            exit;
        }
        $this->client = $link->client;
    }

    /**
     * validateCustomer - ensures that the link belongs to a validate customer
     *
     * @param object $link - The link model
     *
     * @return void
     */
    protected function validateCustomer ($link)
    {
        if (empty($link->user)) {
            $subject = 'Feedback link not associated with customer!';
            $error   = __FILE__ . ' @ ' . __LINE__;
            $meta    = ['url' => $link];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
            exit;
        }
        $this->customer   = $link->user;
        $this->first_name = $this->customer->first_name;
        $this->last_name  = $this->customer->last_name;
    }

    /**
     * validateCustomerContacts - grabs all customer contacts
     *
     * @param void
     */
    protected function validateCustomerContacts ()
    {
        $this->contacts = [Medium::SMS => [], Medium::EMAIL => []];
        foreach ($this->customer->contacts as $contact) {
            $this->contacts[$contact->medium_id][] = $contact;
            
            // set display number and email to the most recent ones we have received
            if ($contact->medium_id == Medium::SMS) {
                $this->phone = $contact->contact;
            }
            if ($contact->medium_id == Medium::EMAIL) {
                $this->email = $contact->contact;
            }
        }
    }

    /**
     * validateFeedback - grabs customer feedback
     *
     * @return void
     */
    protected function validateFeedback ()
    {
        if (isset($this->customer->feedback->feedback)) {
            $this->feedback = $this->customer->feedback;
        }
        $this->dfeedback = isset($this->feedback->feedback) ? $this->feedback->feedback : '';
    }

    /**
     * validateRequest - validates the form values
     *
     * @param Request $request - the input request
     *
     * @return request errors
     */
    protected function validateRequest ()
    {
        $data = [
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'comments'   => $this->dfeedback
        ];
        $validator = Validator::make(
            $data, 
            [
                'email'      => 'nullable|email',
                'first_name' => 'max:120',
                'last_name'  => 'max:120',
                'phone'      => 'nullable|min:5',
                'comments'   => 'required|min:10'
            ]
        );
        return $validator->errors()->all();
    }

    /**
     * saveRecord - saves the given model
     *
     * @param object $link   - The feedback link model
     * @param array  $errors - The array of userfacing errors
     *
     * @return $errors
     */
    protected function saveRecords ($errors)
    {
        // save customer first and last name
        $customer = $this->customer;
        try {
            $customer->first_name = $this->first_name;
            $customer->last_name  = $this->last_name;
            $customer->save();
        } catch (\Exception $e) {
            $subject = 'Customers model save failure!';
            $error   = __FILE__ . ' @ ' . __LINE__ . ': ' . $e->getMessage();
            $meta    = ['submission' => $customer];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
            $errors[] = 'An unexpected error occured! Support has been notified.';            
        }

        // save phone
        $phone = new Contact;
        foreach ($this->contacts[Medium::SMS] as $contact) {
            $phone = strtolower($contact->contact) == strtolower($this->phone) ? $contact : $phone;
        }
        try {
            $phone->user_id   = $this->customer->id;
            $phone->client_id = $this->client->id;
            $phone->medium_id = Medium::SMS;
            $phone->contact   = $this->phone;
            if (!empty($this->phone)) {
                $phone->save();
            }
        } catch (\Exception $e) {
            $subject = 'Contact model save failure!';
            $error   = __FILE__ . ' @ ' . __LINE__ . ': ' . $e->getMessage();
            $meta    = ['submission' => $phone];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
            $errors[] = 'An unexpected error occured! Support has been notified.';
        }

        // save email
        $email = new Contact;
        foreach ($this->contacts[Medium::EMAIL] as $contact) {
            $email = strtolower($contact->contact) == strtolower($this->email) ? $contact : $email;
        }
        try {
            $email->user_id   = $this->customer->id;
            $email->client_id = $this->client->id;
            $email->medium_id = Medium::EMAIL;
            $email->contact   = $this->email;
            if (!empty($this->email)) {
                $email->save();
            }
        } catch (\Exception $e) {
            $subject = 'Contact model save failure!';
            $error   = __FILE__ . ' @ ' . __LINE__ . ': ' . $e->getMessage();
            $meta    = ['submission' => $email];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
            $errors[] = 'An unexpected error occured! Support has been notified.';
        }

        // save feedback
        $feedback = is_object($this->feedback) ? $this->feedback : new Feedback;
        try {
            $feedback->client_id = $this->client->id;
            $feedback->user_id   = $this->customer->id;
            $feedback->feedback  = $this->dfeedback;
            $feedback->save();
        } catch (\Exception $e) {
            $subject = 'Feedback model save failure!';
            $error   = __FILE__ . ' @ ' . __LINE__ . ': ' . $e->getMessage();
            $meta    = ['submission' => $feedback];
            Notification::send(User::first(), new Slack($subject, $error, $meta));
            $errors[] = 'An unexpected error occured! Support has been notified.';
        }

        return $errors;
    }
}
