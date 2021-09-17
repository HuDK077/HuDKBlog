<?php
#########################后台路由#########################
$api = app('Dingo\Api\Routing\Router');
//$api->version('v1',[
//    'middleware' => checkMiddleware('admin'),
//    'namespace' => 'App\Http\Controllers\Admin',
//    'prefix' => 'admin'],function ($route) {
//    $route->post('auth/login','AuthController@login');
//    $route->post('test','TestController@test');
//});


$api->version('v1', ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => 'admin'], function ($route) {
    $route->post('auth/authentication', 'AuthController@authentication');               #认证检测
    $route->post('auth/register', 'AuthController@register');                           #注册接口
    $route->post('auth/login', 'AuthController@login');                                 #登录接口
    $route->any('file/getFile', 'FileController@getFile');                              #获取图片接口
    $route->any('errorLog/errorPush', 'ErrorLogController@errorPush');                  #页面错误推送接口
    #路由自动注入
    $route->any('{controller}/{func}', [
        'middleware' => checkMiddleware('admin'),
        function ($controller, $func) {
            $controller = ucfirst($controller);
            $controller = 'App\\Http\\Controllers\\Admin\\' . $controller . 'Controller';
            return app()->call($controller . '@' . $func);
        }
    ]);
});
