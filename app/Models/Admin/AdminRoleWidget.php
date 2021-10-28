<?php

namespace App\Models\Admin;

use App\Models\BaseModel;

class AdminRoleWidget extends BaseModel
{
    protected $table = "admin_role_widgets";
    protected $guarded = [];
    public $timestamps = false;//lumen自动管理created_at和updated_at 默认开启
}
