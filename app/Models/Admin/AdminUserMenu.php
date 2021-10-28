<?php
namespace App\Models\Admin;

use App\Models\BaseModel;

class AdminUserMenu extends BaseModel
{
    protected $guarded = [];
    protected $table = "admin_user_menus";
    public $timestamps = false;//lumen自动管理created_at和updated_at 默认开启
    protected $hidden=['pivot'];

}
