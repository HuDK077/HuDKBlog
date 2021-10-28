<?php
namespace  App\Http\Middleware;

use App\Models\Admin\AdminUser;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Tymon\JWTAuth\Exceptions\JWTException;

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
//        $token = auth('admin')->getToken();
//        if (!$token) {
//            return response()->json(['success' => false,'message' => 'Unauthorized'],401);
//        }
//        $user = auth('admin')->user();
//        if (!$user) {
//            return response()->json(['success' => false,'message' => 'Unauthorized'],401);
//        }
//        return $next($request);
        try {
            auth('admin')->authenticate();
            return $next($request);
            #region 权限判断
            $member = auth('admin')->user();
            $path = $request->getPathInfo();
            $user = AdminUser::find($member->id)->with('roles.widgets', "widgets")->first()->toArray();
            foreach ($user['roles'] as &$role) {
                if ($role['widgets']) {
                    $user['widgets'] = array_merge($user["widgets"], $role["widgets"]);
                }
            }
            $widgets = array_keys(array_flip(array_column($user['widgets'],'uri')));
            if (!in_array($path,$widgets)){
                return response("无权访问$path",403);#没有权限被看门狗拦截下来
            }
            #endregion 权限判断
            return $next($request);
        } catch (AuthenticationException $exception) {
            // 此处捕获到了 token 过期所抛出的 TokenExpiredException 异常，我们在这里需要做的是刷新该用户的 token 并将它添加到响应头中
            try {
                // 刷新用户的 token
                $token = auth('admin')->refresh();
                list($s1, $s2) = explode(' ', microtime());
                $timestamp = (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
                $response = $this->setAuthenticationHeader($next($request), $token);
                $response->headers->set('Access-Control-Expose-Headers', 'Authorization,microtime');
                $response->headers->set('microtime', $timestamp);
                return $response;
                // 使用一次性登录以保证此次请求的成功  已设置JWT_BLACKLIST_GRACE_PERIOD,此处用不到
//                Auth::guard('api')->onceUsingId(auth('api')->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray()['sub']);
//                auth('api')->onceUsingId(auth('api')->manager()->getPayloadFactory()->buildClaimsCollection()->toPlainArray()['sub']);
            } catch (JWTException $exception) {
                // 如果捕获到此异常，即代表 refresh 也过期了，用户无法刷新令牌，需要重新登录。
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
        }
    }

}
