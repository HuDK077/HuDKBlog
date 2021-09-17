<?php

use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_users')->delete();
        
        \DB::table('admin_users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'username' => 'admin',
                'password' => '$2y$10$prNSSNdsyv.BnOffiSdd1efNjHFoWPw3fBIQwdCM5/d0xj6KDOHQO',
                'name' => '超级管理员',
                'avatar' => '74b9698dcb0ea6c97837403c45690ad4',
                'remember_token' => NULL,
                'created_at' => '2020-12-20 20:20:20',
                'updated_at' => '2020-10-15 12:51:21',
            ),
        ));
        
        
    }
}