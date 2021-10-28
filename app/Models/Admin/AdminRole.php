<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use DateTimeInterface;

class AdminRole extends BaseModel
{
    protected $guarded = [];// 定义不可操作字段
    protected $table = 'admin_roles';// 定义数据表名
    //public $timestamps = false;//lumen自动管理created_at和updated_at 默认开启
    protected $hidden=['pivot'];
    //角色所拥有的菜单
    public function menus(){
        return $this->belongsToMany('App\Models\Admin\AdminMenu','admin_role_menus','role_id','menu_id');
    }

    //角色所拥有的权限
    public function widgets(){
        return $this->belongsToMany('App\Models\Admin\AdminWidget','admin_role_widgets','role_id','widget_id');
    }

}
