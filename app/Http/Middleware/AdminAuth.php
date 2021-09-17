<?php
namespace  App\Http\Middleware;

use App\Models\Admin\RoleUser;
use Closure;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = auth('admin')->getToken();
        if (!$token) {
            return response()->json(['success' => false,'message' => 'Unauthorized'],401);
        }
        $user = auth('admin')->user();
        if (!$user) {
            return response()->json(['success' => false,'message' => 'Unauthorized'],401);
        }
        // 如果不是管理员访问商户ID与本账号账号ID不同返回403
        $role = RoleUser::where('user_id',$user->id)->first();
        if ($role->role_id != 1){
            if($user->store_id != $request->store_id){
                return response()->json(['success' => false,'message' => 'Unauthorized'],403);
            }
        }
        return $next($request);
    }

}
