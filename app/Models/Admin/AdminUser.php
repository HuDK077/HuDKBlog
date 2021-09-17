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
class AdminUser extends BaseModel implements AuthorizableContract,AuthenticatableContract,JWTSubject
{
    use Authenticatable,Authorizable;
    protected $table = 'admin_users';
    protected $guarded = [];
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
}
