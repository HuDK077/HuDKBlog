<?php

use Illuminate\Database\Seeder;

class AdminRolePermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_role_permissions')->delete();
        
        \DB::table('admin_role_permissions')->insert(array (
            0 => 
            array (
                'role_id' => 3,
                'permission_id' => 4,
                'created_at' => '2020-09-29 13:38:48',
                'updated_at' => '2020-09-29 13:38:48',
            ),
            1 => 
            array (
                'role_id' => 3,
                'permission_id' => 5,
                'created_at' => '2020-09-29 13:38:48',
                'updated_at' => '2020-09-29 13:38:48',
            ),
            2 => 
            array (
                'role_id' => 2,
                'permission_id' => 6,
                'created_at' => '2020-10-09 17:49:26',
                'updated_at' => '2020-10-09 17:49:26',
            ),
            3 => 
            array (
                'role_id' => 1,
                'permission_id' => 1,
                'created_at' => '2020-10-14 14:01:06',
                'updated_at' => '2020-10-14 14:01:06',
            ),
            4 => 
            array (
                'role_id' => 1,
                'permission_id' => 2,
                'created_at' => '2020-10-14 14:01:06',
                'updated_at' => '2020-10-14 14:01:06',
            ),
            5 => 
            array (
                'role_id' => 1,
                'permission_id' => 3,
                'created_at' => '2020-10-14 14:01:06',
                'updated_at' => '2020-10-14 14:01:06',
            ),
            6 => 
            array (
                'role_id' => 1,
                'permission_id' => 4,
                'created_at' => '2020-10-14 14:01:06',
                'updated_at' => '2020-10-14 14:01:06',
            ),
            7 => 
            array (
                'role_id' => 1,
                'permission_id' => 5,
                'created_at' => '2020-10-14 14:01:06',
                'updated_at' => '2020-10-14 14:01:06',
            ),
            8 => 
            array (
                'role_id' => 1,
                'permission_id' => 6,
                'created_at' => '2020-10-14 14:01:06',
                'updated_at' => '2020-10-14 14:01:06',
            ),
        ));
        
        
    }
}