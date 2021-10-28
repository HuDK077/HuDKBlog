<?php
namespace App\Models\Admin;

use App\Models\BaseModel;

class AdminWidget extends BaseModel
{
    protected $table = "admin_widgets";
    protected $guarded = [];
    //public $timestamps = false;//lumen自动管理created_at和updated_at 默认开启
    protected $hidden=['pivot'];
}
