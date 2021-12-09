<?php


namespace App\Models\Admin;


use App\Models\Api\Craftsman;
use App\Models\BaseModel;

class GrabLog extends  BaseModel
{
    protected $guarded = [];// 定义不可操作字段

    public function craftsman()
    {
        return $this->hasOne(Craftsman::class,'id','craftsman_id');
    }
}