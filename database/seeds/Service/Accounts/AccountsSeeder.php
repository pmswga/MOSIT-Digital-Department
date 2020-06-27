<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table(\App\Core\Config\ListDatabaseTable::TABLE_ACCOUNTS)->count() === 0) {
            $accounts = [];
            foreach (DataSeeder::$employees as $employee) {
                $accounts[] = [
                    'idAccount' => $employee['idEmployee'],
                    'idAccountType' => $employee['idEmployeePost'],
                    'email' => $employee['personalEmail'],
                    'email_verified_at' => NULL,
                    'password' => \Illuminate\Support\Facades\Hash::make('qwerty'),
                    'remember_token' => NULL
                ];
            }

            DB::table(\App\Core\Config\ListDatabaseTable::TABLE_ACCOUNTS)->insert($accounts);
        }
    }
}