<?php

namespace App\Jobs;

use App\Models\Api\Member;
use App\Models\Api\CommissionLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RemitQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderid)
    {
        $this->orderid = $orderid;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $commission_no = $this->orderid;//提现单号
        if ($commission_no) {
            $app = app('wechat.payment');
            $temp = CommissionLog::leftJoin('members', 'withdraw_logs.member_id', '=', 'members.id')
                ->where('commission_no', $commission_no)
                ->select('withdraw_logs.*', 'members.openid', 'members.real_name', 'members.phone')
                ->first();//提现具体信息
            if ($temp) {
                $res = $app->transfer->toBalance([
                    'partner_trade_no' => $commission_no, // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
                    'openid'           => $temp->openid,
                    'check_name'       => 'FORCE_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
                    're_user_name'     => $temp->real_name, // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
                    'amount'           => $temp->money * 100, // 企业付款金额，单位为分
                    'desc'             => '佣金提现', //企业付款操作说明信息。必填
                ]);
                if ($res['return_code'] == "SUCCESS" && $res['result_code'] == "SUCCESS") {
                    $temp->update(['status' => 3, 'remark' => $res['err_code_des'], 'remit_time' => time()]);//更新状态
                } else {
                    $money_back = 1;
                    try {
                        Member::where('id', $temp->member_id)->increment('commission', $temp->money);//将提现失败的钱归还到原有账户
                    } catch (\Exception $exception) {
                        $money_back = 2;
                    }
                    $temp->update(['status' => 4, 'remark' => $res['err_code_des'], 'money_back' => $money_back]);//更新状态
                }
            }
        }
    }
}
