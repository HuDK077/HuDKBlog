<?php

namespace App\Console\Commands;

use App\Jobs\GrabOrderVirtualQueue;
use App\Models\Api\Order;
use Illuminate\Console\Command;

class GrabOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'GrabOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '添加虚拟抢单';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $order_total = mt_rand(3,5);//随时订单数
        $orders = range(0, $order_total - 1);//订单
        //推入队列
        foreach ($orders as $key => &$order) {
            $minutes = mt_rand(1,55) + $key;
            $order['time'] = now()->addMinutes($minutes);
            GrabOrderVirtualQueue::dispatch($minutes)->delay($order['time']);
        }
        info('__添加虚拟抢单__',$orders);
    }
}
