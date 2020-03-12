<?php
/**
 * ClientController.php
 * Controller for the add client form
 *
 * @author Adam Berry-Huff <adamberryhuff@gmail.com>
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client\Client;

/**
 * ClientController.php
 * Controller for the add client form
 *
 * @author Adam Berry-Huff <adamberryhuff@gmail.com>
 */
class ClientController extends Controller
{
    /** 
     * add - interfaces with the add client form
     *
     * @return view
     */
    public function add ()
    {
        if (empty($_GET['secret']) || $_GET['secret'] != '_Pbv!f!]E6Q:kmUV') {
            exit;
        }

        // add client
        if (!empty($_GET['client_name'])) {
            $client                = new Client;
            $client->name          = isset($_GET['client_name']) ? $_GET['client_name'] : '';
            $client->contact_name  = isset($_GET['contact_name']) ? $_GET['contact_name'] : '';
            $client->contact_email = isset($_GET['contact_email']) ? $_GET['contact_email'] : '';
            $client->contact_phone = isset($_GET['contact_phone']) ? $_GET['contact_phone'] : '';
            $client->cost          = isset($_GET['cost']) ? $_GET['cost'] : '';
            $client->save();
        }

        // purchase twilio number

        // refresh and display

        // add validation

        return view(
            'addclient',
            [
                'disabled' => '',
                'error'    => false
            ]
        );
    }
}
