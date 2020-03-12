<?php

use Illuminate\Database\Seeder;
use App\Models\UserLog\Event;

class CustomerLogEventsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Event::truncate();

        foreach (Event::$customer_events as $id => $event) {
            Event::create([
                'id'          => $id,
                'event'       => $event['event'],
                'description' => $event['description']
            ]);
        }
    }
}
