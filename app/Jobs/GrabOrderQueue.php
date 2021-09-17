<?php

namespace App\Jobs;

use App\Models\Admin\GrabLog;
use App\Models\Api\CustomOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Redis;

class GrabOrderQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderid, $craftsman_id)
    {
        $this->orderid = $orderid;
        $this->craftsman_id = $craftsman_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order_no = $this->orderid;//订单号
        $craftsman_id = $this->craftsman_id;//工艺师
        //订单信息
        $order = CustomOrder::find($order_no);
        if ($order && $craftsman_id && $order->status == 2 && !$order->craftsman_id) {
            //每个订单限制一个
            Redis::funnel($order_no)->limit(1)->then(function () use ($order, $craftsman_id) {
                $order->craftsman_id = $craftsman_id;
                $order->status = 3;
                $order->assign_type = 1;
                $order->assign_at = now()->format('Y-m-d H:i:s');
                $order->save();

                //抢单日志
                GrabLog::create([
                    'member_id' => $order->member_id,
                    'craftsman_id' => $craftsman_id,
                    'order_no' => $order->order_no
                ]);
            }, function () {
                // 无法获得锁...
                return $this->fail(new \Exception('抢单失败'));
            });
        }
    }
}
