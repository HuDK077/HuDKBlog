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

class GrabOrderVirtualQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $time;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($time)
    {
        $this->time = $time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order_no = 'CU-' . get_order_no($this->time) . mt_rand(0);
        $add_info = [
            'member_id' => 0,
            'order_no' => $order_no,
            //'parent_id' => $parent_id,
            'craft_type' => 1,//1全手工2现代半手工
            'custom_type' => 1,//1个人2团队3活动
            'craftsman_id' => 0,//工艺师
            'assign_type' => 1,//来源
            'volume' => 200,//体积
            'budget' => 200,//预算
            'pot_id' => 0,//壶样ID
            'number' => 20,//数量
            'sculpture' => [],//陶刻
            'construction_period' => 3,//工期
            'mud_id' => 0,//泥料
            'total_price' => 0,//总价
            'province' => '',//省
            'city' => '',//市
            'area' => '',//区
            'address' => '',//详细地址
            'name' => '',//姓名
            'phone' => '',//手机号
            'address_id' => 0,//地址ID
            'system_commission' => 0,//平台抽成
            'status' => 3,
            'assign_at' => now()->format('Y-m-d H:i:s')
        ];
        //创建订单
        $order = CustomOrder::create($add_info);
    }
}
