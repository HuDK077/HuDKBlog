<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;

class ApiMailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $name = '我发的第一份邮件';
        Mail::send('emails.test',['name' => $name],function($message){
            $to = '1369783326@qq.com';
            $message->to($to)->subject('邮件测试');
        });
        return Mail::failures();
    }
}
