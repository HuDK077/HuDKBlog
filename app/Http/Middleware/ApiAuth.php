<?php
namespace  App\Http\Middleware;

use Closure;

class ApiAuth
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

        $token = auth('api')->getToken();
        if (!$token) {
            return response()->json(['success' => false,'message' => 'Unauthorized'],401);
        }
        $user = auth('api')->user();
        if (!$user) {
            return response()->json(['success' => false,'message' => 'Unauthorized'],401);
        }
        return $next($request);
    }

}
