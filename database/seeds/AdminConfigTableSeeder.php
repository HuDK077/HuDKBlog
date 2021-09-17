<?php

use Illuminate\Database\Seeder;

class AdminConfigTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_config')->delete();
        
        \DB::table('admin_config')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'website_keywords',
                'value' => 'zly',
                'description' => '关键字',
                'created_at' => '2017-09-05 23:40:09',
                'updated_at' => '2018-01-19 12:39:57',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'company_address',
                'value' => NULL,
                'description' => '公司地址',
                'created_at' => '2017-09-09 15:17:10',
                'updated_at' => '2017-09-09 15:17:10',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'website_title',
                'value' => '后台管理系统',
                'description' => '网站标题',
                'created_at' => '2017-09-09 15:17:16',
                'updated_at' => '2018-01-19 12:39:57',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'company_telephone',
                'value' => '0510-88888888',
                'description' => '公司电话',
                'created_at' => '2017-09-09 15:17:23',
                'updated_at' => '2018-01-19 12:39:57',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'company_full_name',
                'value' => '无锡智凌云物联网科技有限公司',
                'description' => '公司全称',
                'created_at' => '2017-09-09 15:17:30',
                'updated_at' => '2018-01-19 12:39:57',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'website_icp',
                'value' => 'xxxxxx',
                'description' => 'ICP备案号',
                'created_at' => '2017-09-09 15:17:38',
                'updated_at' => '2018-01-19 12:39:57',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'system_version',
                'value' => '0.5.1',
                'description' => '系统版本',
                'created_at' => '2017-09-09 15:17:45',
                'updated_at' => '2018-01-19 12:39:57',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'company_short_name',
                'value' => '智凌云',
                'description' => '公司简称',
                'created_at' => '2017-09-09 15:18:14',
                'updated_at' => '2018-01-19 12:39:57',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'system_author',
                'value' => 'zly',
                'description' => '系统所属',
                'created_at' => '2017-09-09 15:18:21',
                'updated_at' => '2018-01-19 12:39:57',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'system_author_website',
                'value' => 'http://www.wisdomyun.xin',
                'description' => '网站地址',
                'created_at' => '2017-09-09 15:18:27',
                'updated_at' => '2018-01-19 12:39:57',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'logo',
                'value' => NULL,
                'description' => '站点logo',
                'created_at' => '2020-11-03 10:38:21',
                'updated_at' => '2020-11-03 10:38:21',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'logo_sm',
                'value' => NULL,
                'description' => '站点logo-小',
                'created_at' => '2020-11-03 10:38:27',
                'updated_at' => '2020-11-03 10:38:27',
            ),
        ));
        
        
    }
}