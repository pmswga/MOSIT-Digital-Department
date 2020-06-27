<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListInstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table(\App\Core\Config\ListDatabaseTable::TABLE_LIST_INSTITUTE)->count() === 0) {
            DB::table(\App\Core\Config\ListDatabaseTable::TABLE_LIST_INSTITUTE)->insert([
                [
                    'idInstitute' => 1,
                    'caption' => 'ИТ'
                ],
            ]);
        }
    }
}