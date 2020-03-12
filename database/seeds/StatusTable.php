<?php

use Illuminate\Database\Seeder;
use App\Models\Status\Status;

class StatusTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::truncate();

        foreach (Status::$statuses as $id => $status) {
            Status::create([
                'id'     => $id,
                'status' => $status
            ]);
        }
    }
}
