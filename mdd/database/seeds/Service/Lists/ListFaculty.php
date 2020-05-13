<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListFaculty extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('list_faculty')->insert([
            [
                'idFaculty' => 1,
                'idInstitute' => 1,
                'caption' => 'МОСИТ'
            ],
        ]);
    }
}
