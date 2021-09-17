<?php

namespace App\Jobs;

use App\Models\Api\Order;
use App\Models\Api\OrderAfterSale;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OrderRefundQueue implements ShouldQueue
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
        $order_no = $this->orderid;//订单号
        if ($order_no) {
            $app = app('wechat.payment');
            //订单信息
            $order = Order::leftJoin('members', 'orders.member_id', '=', 'members.id')
                ->leftJoin('order_after_sales', 'orders.refund_no', '=', 'order_after_sales.refund_no')
                ->where('order_no',$order_no)
                ->select('orders.*','order_after_sales.price as refund_price','members.openid', 'members.real_name', 'members.phone')
                ->first();

            if ($order) {
                $res = $app->refund->byTransactionId($order->pay_no,$order->refund_no,$order->price_total,$order->refund_price,
                    ['notify_url' => env('APP_URL') . '/api/pay/wechatRefundResult' ]);// 支付结果通知网址，如果不设置则会使用配置里的默认地址
//                if ($res['return_code'] == "SUCCESS" && $res['result_code'] == "SUCCESS") {
//                    OrderAfterSale::where('refund_no',$order->refund_no)->update(['status' => 4, 'refund_price' => $order->refund_price, 'refund_at' => time()]);//更新状态
//                }
            }
        }
    }
}
