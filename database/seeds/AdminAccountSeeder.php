<?php

use Illuminate\Database\Seeder;

class AdminAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creating admin account if not exists
        factory('App\User')->create([
            'name' => 'Admin Account',
            'email' => 'admin@admin.com',
            'is_admin' => '1',
        ]);
    }
}
