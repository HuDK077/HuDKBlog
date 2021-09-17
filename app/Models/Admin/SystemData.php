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
        $this->attributes['value'] = serialize($value);
    }

    public function getValueAttribute($value)
    {
        $key = $this->attributes['key'];
        $info =  unserialize($value);
        if ($key == 'activity_banner') {
            $info['banner_src'] = env('QINIU_DOMAIN_FULL').$info['banner'];
        }
        if ($key == 'active_banner') {
            $info['active_other'] = env('QINIU_DOMAIN_FULL').$info['banner'];
        }
        if ($key == 'coupon_banner') {
            $info['coupon_other'] = env('QINIU_DOMAIN_FULL').$info['banner'];
        }
        return $info;
    }
}
