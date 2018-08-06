<?php

use Illuminate\Database\Seeder;
use App\Model\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Model\User')->create()->each(function(User $u) {
            $u->order()->save(factory('App\Model\Order')->make());
        });
    }
}
