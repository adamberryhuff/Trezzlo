<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StatusTable::class);
        $this->call(MechanismTable::class);
        $this->call(MediumTable::class);
        $this->call(CustomerLogEventsTable::class);
        $this->call(BusinessesTable::class);
        $this->call(LinksTable::class);
        $this->call(UserTypeTable::class);
    }
}
