<?php
/**
 * 说明
 * @author dkhu
 * @date 2023/5/18 10:04
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateProduct;
use App\Models\Api\MQ;
use App\Services\RabbitMQService;
use Illuminate\Http\Request;
use Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * rabbitmq 使用
 * @author dkhu
 * @date 2023/5/18 10:04
 */
class ApiRabbitMQController extends Controller
{

    private $productKey;
    /**
     * 推送消息到mq中
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pushRabbitmq(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);
        try {
            $id = $request->input('id');
            $info = MQ::query()->find($id);
            UpdateProduct::dispatch($info);
            return apiResponse('2001');
        } catch (\Exception $e) {
            return $this->failed($e->getMessage() . $e->getLine());
        }
    }
}
