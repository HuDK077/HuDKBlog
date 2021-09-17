<?php
namespace  App\Models\Admin;

use App\Models\BaseModel;

class Permissions extends BaseModel
{
    protected $guarded = [];// 定义不可操作字段
    protected $table = 'admin_permissions';// 定义数据表名
    //public $timestamps = false;//lumen自动管理created_at和updated_at 默认开启

}
