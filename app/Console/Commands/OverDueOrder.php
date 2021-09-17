<?php

namespace App\Console\Commands;

use App\Models\Api\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OverDueOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overdueOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '订单超时未支付';

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
     */
    public function handle()
    {
        DB::transaction(function () {
            $past = date('Y-m-d H:i:s', strtotime('-15 minute'));
            $overdueOrderIds = Order::where('status', 1)
                ->where('created_at', '<=', $past)
                ->pluck('id');
            if (!empty($overdueOrderIds)) {
                try {
                    Order::wherein('id', $overdueOrderIds)->update([
                        'status' => 4,
                        'cancel_at' => now()->format('Y-m-d H:i:s'),
                        'reason' => '支付超时取消'
                    ]);

                } catch (\Exception $exception) {
                    throw new \Exception($exception->getMessage());
                }
            }
        }, 3);
    }
}
