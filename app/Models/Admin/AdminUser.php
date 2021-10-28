<?php
/**
 * Created by PhpStorm.
 * User: dingzhipeng
 * Date: 2020/4/21
 * Time: 3:22 PM
 */

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class AdminUser extends BaseModel implements AuthorizableContract, AuthenticatableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    protected $table = 'admin_users';
    protected $guarded = [];
    protected $hidden = ['pivot'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    //用户所拥有的角色
    public function roles()
    {
        return $this->belongsToMany('App\Models\Admin\AdminRole', 'admin_role_users', 'user_id', 'role_id');
    }

    //用户所拥有的额外权限
    public function widgets()
    {
        return $this->belongsToMany('App\Models\Admin\AdminWidget', 'admin_user_widgets', 'user_id', 'widget_id');
    }

    //用户所拥有的额外菜单
    public function menus()
    {
        return $this->belongsToMany('App\Models\Admin\AdminMenu', 'admin_user_menus', 'user_id', 'menu_id');
    }

    //用户所拥有的角色下的菜单
    public function role_menus()
    {
        return $this->belongsToMany('App\Models\Admin\AdminRole', 'admin_role_users', 'user_id', 'role_id')
            ->leftJoin('admin_role_menus', 'admin_role_menus.role_id', '=', 'admin_role_users.role_id');
    }

    public function role_widgets()
    {
        return $this->belongsToMany('App\Models\Admin\AdminRole', 'admin_role_users', 'user_id', 'role_id')
            ->leftJoin('admin_role_widgets', 'admin_role_widgets.role_id', '=', 'admin_role_users.role_id');
    }
}
