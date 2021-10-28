<?php

namespace App\Http\Controllers;


#权限看门狗
#GoodDog :)
use App\Models\Admin\AdminUser;
use App\Models\Admin\Permissions;
use App\Models\Admin\RolePermissions;
use App\Models\Admin\AdminRoleUser;

trait GuardDog
{
    #权限测试
    #通过就返回false
    #没有权限就返回true
    #$permission 权限标识
    protected function GDT($permission){
        $user = auth('admin')->user();
        $role = AdminRoleUser::where('user_id',$user->id)->first();
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

    #权限测试_V2
    #通过就返回false
    #没有权限就返回true
    #$permission 权限标识
    protected function GDT_V2($permission){
        $member = auth('admin')->user();
        $user = AdminUser::find($member->id)->with('roles.widgets', "widgets")->first()->toArray();
        foreach ($user['roles'] as &$role) {
            if ($role['widgets']) {
                $user['widgets'] = array_merge($user["widgets"], $role["widgets"]);
            }
        }
        $widgets = array_keys(array_flip(array_column($user['widgets'],'slug')));
        if (in_array($permission,$widgets)){
            return false;
        }
        return true;     #没有权限被看门狗拦截下来
    }
}
