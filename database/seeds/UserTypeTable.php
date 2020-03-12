<?php

use Illuminate\Database\Seeder;
use App\Models\User\Type;

class UserTypeTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::truncate();

        foreach (Type::$types as $id => $type) {
            Type::create([
                'id'   => $id,
                'type' => $type
            ]);
        }
    }
}
