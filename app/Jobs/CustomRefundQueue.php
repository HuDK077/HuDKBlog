<?php

namespace App\Jobs;

use App\Models\Api\CustomOrder;
use App\Models\Api\Member;
use App\Models\Api\CommissionLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CustomRefundQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order_no;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderno)
    {
        $this->order_no = $orderno;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order_no = $this->order_no;//订单号
        if ($order_no) {
            $app = app('wechat.payment');
            //订单信息
            $order = CustomOrder::leftJoin('members', 'custom_orders.member_id', '=', 'members.id')
                ->where('order_no',$order_no)
                ->select('custom_orders.*', 'members.openid', 'members.real_name', 'members.phone')
                ->first();

            if ($order) {
                $res = $app->refund->byTransactionId($order->pay_no,$order->refund_no,$order->total_price,$order->total_price,
                    ['notify_url' => env('APP_URL') . '/api/pay/wechatRefundResult' ]);// 支付结果通知网址，如果不设置则会使用配置里的默认地址
            }
        }
    }
}
