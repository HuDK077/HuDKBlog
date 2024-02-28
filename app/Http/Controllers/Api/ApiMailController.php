<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Mail;

class ApiMailController extends Controller
{
    public function sendEmail(Request $request)
    {
        if($request->choose==1){
            $text = '可爱的name同意啦，'.date("Y年m月d日 H:i:s") . '这是一个充满幸福而特别时间，一定要记住哦！';
        }else{
            $text = 'name的选择是："否"';
        }
        Mail::send('emails.test',['name' => $text],function($message){
            $to = '1369783326@qq.com';
            $message->to($to)->subject('雅如小姐姐的选择邮件');
        });
        return Mail::failures();
    }
}
