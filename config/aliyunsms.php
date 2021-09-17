<?php


return [
    'access_key'        => env('ALIYUN_SMS_AK'), // accessKey
    'access_secret'     => env('ALIYUN_SMS_AS'), // accessSecret
    'sign_name'         => env('ALIYUN_SMS_SIGN_NAME'), // 签名
    'register_template' => env('REGISTER_TEMPLATE'),//注册
    'reset_password_template' => env('RESET_PASSWORD_TEMPLATE'),//重置密码
    'modify_mobile_template' => env('MODIFY_MOBILE_TEMPLATE'),//修改手机号
    'order_message_template' => env('ORDER_MESSAGE_TEMPLATE'),//预约订单提醒业务
];
