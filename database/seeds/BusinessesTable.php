<?php

use App\Models\User\User;
use App\Models\User\Type;
use App\Models\User\Contact;
use App\Models\Client\Client;
use App\Models\Status\Status;
use App\Models\Client\Handle;
use App\Models\Message\Medium;
use Illuminate\Database\Seeder;
use App\Models\Message\Mechanism;

class BusinessesTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        Client::truncate();
        Handle::truncate();
        Contact::truncate();

        // add client
        $client = Client::create([
            'name'          => 'Test Client',
            'cost'          => 250,
            'status_id'     => Status::ACTIVE,
        ]);

        // add client admin
        $user = User::create([
            'user_type_id' => Type::TYPE_ADMIN,
            'client_id'    => $client->id,
            'first_name'   => 'Adam',
            'last_name'    => 'Berry-Huff'
        ]);

        // add client admin contact
        Contact::create([
            'user_id'   => $user->id,
            'client_id' => $client->id,
            'medium_id' => Medium::SMS,
            'contact'   => '+13603183610'
        ]);

        Contact::create([
            'user_id'   => $user->id,
            'client_id' => $client->id,
            'medium_id' => Medium::EMAIL,
            'contact'   => 'adamberryhuff@gmail.com'
        ]);

        // add handle
        Handle::create([
            'client_id'    => $client->id,
            'mechanism_id' => Mechanism::TWILIO,
            'handle'       => env('TWILIO_NUMBER'),
            'status_id'    => Status::ACTIVE
        ]);
    }
}
