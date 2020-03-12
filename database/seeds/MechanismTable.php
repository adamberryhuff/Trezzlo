<?php

use Illuminate\Database\Seeder;
use App\Models\Message\Mechanism;

class MechanismTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mechanism::truncate();

        foreach (Mechanism::$mechanisms as $id => $mechanism) {
            Mechanism::create([
                'id'        => $id,
                'medium_id' => $mechanism['medium'],
                'mechanism' => $mechanism['name']
            ]);
        }
    }
}
