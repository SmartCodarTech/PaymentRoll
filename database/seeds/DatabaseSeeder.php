<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersTableSeeder::class);
         $this->call(EmployeesTableSeeder::class);
         $this->call(DebitTableSeeder::class);
         $this->call(CreditTableSeeder::class);
         $this->call(DivisionTableSeeder::class);
         $this->call(PremuimTableSeeder::class);
         $this->call(DepartmentTableSeeder::class);
         $this->call(PenaltyTableSeeder::class);
         $this->call(ReportTableSeeder::class);
    }
}
