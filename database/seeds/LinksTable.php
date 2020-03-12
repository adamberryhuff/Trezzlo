<?php

use Illuminate\Database\Seeder;
use App\Models\Link\Link;

class LinksTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Link::truncate();

        Link::create(
            [
                'name'        => 'Feedback Link',
                'redirect'    => 'localhost'
            ]
        );
        Link::create(
            [
                'client_id'   => 1,
                'name'        => 'Review Link',
                'redirect'    => 'http://www.google.com'
            ]
        );
    }
}
