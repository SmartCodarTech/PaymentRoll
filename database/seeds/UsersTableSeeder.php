<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
    {
         $user = factory(App\User::class)->create([
             'username' => Str::random(10)'Administrator',
             'email' =>Str::random(10) 'eliknana45@gmail.com',
             'password' => bcrypt('policeman'),
             'lastname' => Str::random(10)'Elikem',
             'firstname' => Str::random(10)'Nanayaw'

         ]);
    }
}
