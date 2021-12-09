<?php
namespace  App\Models\Admin;

use App\Models\BaseModel;

class SystemData extends BaseModel
{
    protected $guarded = [];// 定义不可操作字段
    protected $table = 'system_data';// 定义数据表名
    //public $timestamps = false;//lumen自动管理created_at和updated_at 默认开启

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = $value ? serialize($value) : '';
    }

    public function getValueAttribute($value)
    {
        return $value ? unserialize($value) : '';
    }
}
