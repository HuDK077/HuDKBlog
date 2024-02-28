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
    $route->post('auth/register', 'ApiAuthController@register');              #注册
    $route->post('auth/resetPassword', 'ApiAuthController@resetPassword');              #注册
    $route->post('wechat/login', 'ApiWechatController@login');          #小程序登录
    $route->post('wechat/register', 'ApiWechatController@register');          #小程序登录
    $route->any('file/getImageFile', 'ApiFileController@getImageFile'); #获取图片接口
    $route->any('file/qiniuNotify', 'ApiFileController@qiniuNotify'); #七牛上传回调
    $route->any('test/test', 'ApiTestController@test'); #测试
    $route->any('sms/sendVerificationCode', 'ApiSmsController@sendVerificationCode'); # 验证码发送
    $route->any('mail/sendEmail', 'ApiMailController@sendEmail'); # 邮件发送
    $route->any('rabbitMQ/pushRabbitmq', 'ApiRabbitMQController@pushRabbitmq'); # 测试队列
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
