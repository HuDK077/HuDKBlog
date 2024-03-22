<?php
/**
 * 说明
 * @author dkhu
 * @date 2024/2/28 15:55
 */

namespace App\Services;

use Illuminate\Support\Facades\Mail;

/**
 * 方法说明
 * @author dkhu
 * @date 2024/2/28 15:55
 */
class MailService
{
    public static function sendMail(string $text, string $title, string $to): bool
    {
        Mail::send('emails.test', ['name' => $text], function ($message) use ($to, $title) {
            $message->to($to)->subject($title);
        });
        return true;
    }
}
