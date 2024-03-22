<?php
namespace  App\Models\Api;

use App\Models\BaseModel;

class Product extends BaseModel
{
    protected $guarded = [];// 定义不可操作字段
    protected $table = 'product';// 定义数据表名
    //public $timestamps = false;//lumen自动管理created_at和updated_at 默认开启

}
