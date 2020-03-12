<?php

use Illuminate\Database\Seeder;
use App\Models\Message\Medium;

class MediumTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Medium::truncate();

        foreach (Medium::$mediums as $id => $medium) {
            Medium::create([
                'id'     => $id,
                'medium' => $medium
            ]);
        }
    }
}
