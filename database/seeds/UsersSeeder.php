<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    private $usersNumber = 30;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\User::class, $this->usersNumber)->create();
    }
}
