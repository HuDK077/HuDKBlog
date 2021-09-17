<?php

use Illuminate\Database\Seeder;

class AdminRolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_roles')->delete();
        
        \DB::table('admin_roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '超级管理员',
                'slug' => 'admin',
                'created_at' => '2020-09-24 11:27:49',
                'updated_at' => '2020-09-24 11:27:49',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '临时账号',
                'slug' => 'temporary',
                'created_at' => '2020-09-24 11:27:49',
                'updated_at' => '2020-09-24 11:27:49',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '运营',
                'slug' => 'Operation',
                'created_at' => '2020-09-28 14:03:42',
                'updated_at' => '2020-09-28 14:03:42',
            ),
        ));
        
        
    }
}