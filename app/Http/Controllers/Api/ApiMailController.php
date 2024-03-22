<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MailService;
use Illuminate\Http\Request;
use Mail;

class ApiMailController extends Controller
{
    public function sendEmail(Request $request)
    {
        MailService::sendMail('测试邮件内容', '测试邮件标题', '1369783326@qq.com');
        return apiResponse('2001');
    }
}
