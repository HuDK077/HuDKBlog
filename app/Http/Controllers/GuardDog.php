<?php

namespace App\Http\Controllers;


#权限看门狗
#GoodDog :)
use App\Models\Admin\Permissions;
use App\Models\Admin\RolePermissions;
use App\Models\Admin\RoleUser;

trait GuardDog
{
    #权限测试
    #通过就返回false
    #没有权限就返回true
    #$permission 权限标识
    protected function GDT($permission){
        $user = auth('admin')->user();
        $role = RoleUser::where('user_id',$user->id)->first();
        if ($role->role_id == 1){
            return false;
        }
        $r_p = RolePermissions::where('role_id',$role->role_id)->pluck('permission_id')->toArray();
        $permissions = Permissions::whereIn('id',$r_p)->pluck('slug')->toArray();
        if (in_array($permission,$permissions)){
            return false;
        }
        return true;     #没有权限被看门狗拦截下来

    }
}
