<?php

use Illuminate\Database\Seeder;

class AdminMenuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_menu')->delete();
        
        \DB::table('admin_menu')->insert(array (
            0 => 
            array (
                'id' => 1,
                'parent_id' => 0,
                'title' => '系统设置',
                'slug' => 'setting',
                'sort' => 6,
                'created_at' => '2020-09-27 14:43:39',
                'updated_at' => '2020-10-19 16:22:19',
            ),
            1 => 
            array (
                'id' => 2,
                'parent_id' => 1,
                'title' => '用户管理',
                'slug' => 'setting.user',
                'sort' => 5,
                'created_at' => '2020-09-27 14:45:37',
                'updated_at' => '2020-10-19 16:22:19',
            ),
            2 => 
            array (
                'id' => 3,
                'parent_id' => 1,
                'title' => '角色管理',
                'slug' => 'setting.role',
                'sort' => 3,
                'created_at' => '2020-09-27 14:46:45',
                'updated_at' => '2020-10-19 16:22:19',
            ),
            3 => 
            array (
                'id' => 4,
                'parent_id' => 1,
                'title' => '环境设置',
                'slug' => 'setting.option',
                'sort' => 2,
                'created_at' => '2020-09-27 15:01:02',
                'updated_at' => '2020-10-19 16:22:19',
            ),
            4 => 
            array (
                'id' => 5,
                'parent_id' => 1,
                'title' => '页面管理',
                'slug' => 'setting.menu',
                'sort' => 0,
                'created_at' => '2020-09-27 15:05:16',
                'updated_at' => '2020-10-19 16:22:19',
            ),
            5 => 
            array (
                'id' => 6,
                'parent_id' => 1,
                'title' => '权限管理',
                'slug' => 'setting.permission',
                'sort' => 1,
                'created_at' => '2020-09-27 15:14:07',
                'updated_at' => '2020-10-19 16:22:19',
            ),
            6 => 
            array (
                'id' => 7,
                'parent_id' => 1,
                'title' => '用户编辑',
                'slug' => 'setting.user:id',
                'sort' => 4,
                'created_at' => '2020-09-28 15:00:05',
                'updated_at' => '2020-10-19 16:22:19',
            ),
            7 => 
            array (
                'id' => 8,
                'parent_id' => 0,
                'title' => '会员管理',
                'slug' => 'member',
                'sort' => 29,
                'created_at' => '2020-10-09 10:30:50',
                'updated_at' => '2020-10-19 16:22:21',
            ),
        ));
        
        
    }
}