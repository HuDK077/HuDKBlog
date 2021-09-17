<?php
#########################Api路由#########################
$api = app('Dingo\Api\Routing\Router');
//$api->version('v1',[
//    'middleware' => checkMiddleware('api'),
//    'namespace' => 'App\Http\Controllers\Api',
//    'prefix' => 'api'],function ($route) {
//    $route->post('auth/login','AuthController@login');
//    $route->post('test','ApiTestController@test');
//});

$api->version('v1', ['namespace' => 'App\Http\Controllers\Api', 'prefix' => 'api','middleware' => 'api.throttle','limit' => 300,'expires' => 1], function ($route) {
    $route->post('auth/login', 'ApiAuthController@login');              #小程序用户获取token
    $route->post('wechat/login', 'ApiWechatController@login');          #小程序登录
    $route->any('file/getImageFile', 'ApiFileController@getImageFile'); #获取图片接口
    $route->any('file/qiniuNotify', 'ApiFileController@qiniuNotify'); #七牛上传回调
    #路由自动注入
    $route->any('{controller}/{func}', [
        'middleware' => checkMiddleware('api'),
        function ($controller, $func) {
            $controller = ucfirst($controller);
            $controller = 'App\\Http\\Controllers\\Api\\Api' . $controller . 'Controller';
            return app()->call($controller . '@' . $func);
        }
    ]);
});
