<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountRightsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table(\App\Core\Config\ListDatabaseTable::TABLE_ACCOUNT_RIGHTS)->count() === 0) {
            $accountRights = [];

            foreach (DataSeeder::$teachers as $teacher) {
                if ($teacher['idTeacher']) {
                    $accountRights[] = [
                        'idAccount' => $teacher['idTeacher'],
                        'idSubSystem' => \App\Core\Constants\ListSubSystemConstants::IPS,
                        'isAccess' => True,
                        'isViewAny' => True,
                        'isView' => True,
                        'isCreate' => True,
                        'isUpdate' => True,
                        'isDelete' => False
                    ];
                }
            }

            foreach (DataSeeder::$employees as $employee) {

                if (
                    $employee['idEmployeePost'] == \App\Core\Constants\ListEmployeePostConstants::TEACHER or
                    $employee['idEmployeePost'] == \App\Core\Constants\ListEmployeePostConstants::OPERATOR
                ) {
                    $accountRights[] = [
                        'idAccount' => $employee['idEmployee'],
                        'idSubSystem' => \App\Core\Constants\ListSubSystemConstants::Tickets,
                        'isAccess' => True,
                        'isViewAny' => True,
                        'isView' => True,
                        'isCreate' => False,
                        'isUpdate' => False,
                        'isDelete' => False
                    ];

                    $accountRights[] = [
                        'idAccount' => $employee['idEmployee'],
                        'idSubSystem' => \App\Core\Constants\ListSubSystemConstants::Storage,
                        'isAccess' => True,
                        'isViewAny' => True,
                        'isView' => True,
                        'isCreate' => True,
                        'isUpdate' => True,
                        'isDelete' => True
                    ];
                } else {
                    $accountRights[] = [
                        'idAccount' => $employee['idEmployee'],
                        'idSubSystem' => \App\Core\Constants\ListSubSystemConstants::Tickets,
                        'isAccess' => True,
                        'isViewAny' => True,
                        'isView' => True,
                        'isCreate' => True,
                        'isUpdate' => True,
                        'isDelete' => True
                    ];

                    $accountRights[] = [
                        'idAccount' => $employee['idEmployee'],
                        'idSubSystem' => \App\Core\Constants\ListSubSystemConstants::Storage,
                        'isAccess' => True,
                        'isViewAny' => True,
                        'isView' => True,
                        'isCreate' => True,
                        'isUpdate' => True,
                        'isDelete' => True
                    ];
                }

            }

            $accountRights[] = [
                'idAccount' => DataSeeder::$employees[0]['idEmployee'], /// Доступ для Евгении Михайловой
                'idSubSystem' => \App\Core\Constants\ListSubSystemConstants::IPS,
                'isAccess' => True,
                'isViewAny' => True,
                'isView' => True,
                'isCreate' => True,
                'isUpdate' => True,
                'isDelete' => True
            ];

            DB::table(\App\Core\Config\ListDatabaseTable::TABLE_ACCOUNT_RIGHTS)->insert($accountRights);
        }
    }
}