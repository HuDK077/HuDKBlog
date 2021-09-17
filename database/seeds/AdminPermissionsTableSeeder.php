<?php

use Illuminate\Database\Seeder;

class AdminPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_permissions')->delete();
        
        \DB::table('admin_permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '超级权限',
                'slug' => 'suppppper',
                'created_at' => '2020-09-25 09:25:00',
                'updated_at' => '2020-09-25 09:25:00',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '权限管理',
                'slug' => 'permission',
                'created_at' => '2020-09-25 09:25:00',
                'updated_at' => '2020-09-25 09:25:00',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '菜单管理',
                'slug' => 'menu',
                'created_at' => '2020-09-25 09:25:00',
                'updated_at' => '2020-09-25 09:25:00',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '系统配置',
                'slug' => 'config',
                'created_at' => '2020-09-25 10:33:56',
                'updated_at' => '2020-09-25 10:33:56',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '角色管理',
                'slug' => 'role',
                'created_at' => '2020-09-25 10:36:29',
                'updated_at' => '2020-09-25 10:36:29',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => '后台用户管理',
                'slug' => 'user',
                'created_at' => '2020-09-27 15:36:09',
                'updated_at' => '2020-09-27 15:36:09',
            ),
        ));
        
        
    }
}