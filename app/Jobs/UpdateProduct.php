<?php

namespace App\Jobs;

use App\Services\RabbitmqService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class UpdateProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $productKey;

    /**
     * UpdateProduct constructor.
     * @param $data
     * @throws \Exception
     */
    public function __construct($data)
    {
        $this->productKey = "product::info::{$data->id}";
        $config = [
            'queue' => env('RABBITMQ_QUEUE'),
            'exchange' => env('RABBITMQ_EXCHANGE'),
        ];
        //服务生产者
        RabbitmqService::push($config['queue'], $config['exchange'], 'pus_product', $data);
    }

    /**
     * 服务消费者会走到这里，把消息消费掉
     * @throws \Exception
     */
    public function handle()
    {
        RabbitmqService::pop(env('RABBITMQ_QUEUE'), function ($message) {
            $key = $this->productKey . ':' . date('Y-m-d H:i:s');
            $input = serialize(json_decode($message, true));
            $product = app('redis')->set($key, $input);
            if ($product) {
                Log::info("消息消费成功");
                return true;
            } else {
                Log::info("消息消费失败");
                return false;
            }
        });
    }

    /**
     * 异常扑获
     * @param \Exception $exception
     */
    public function failed(\Exception $exception)
    {
        Log::info($exception->getMessage());
    }
}

