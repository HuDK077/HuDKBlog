<?php
/**
 * 说明
 * @author dkhu
 * @date 2023/5/18 10:04
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateProduct;
use App\Models\Api\Product;
use Illuminate\Http\Request;

/**
 * rabbitmq 使用
 * @author dkhu
 * @date 2023/5/18 10:04
 */
class ApiRabbitMQController extends Controller
{
    protected string $productKey;


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


            $select = 'id,name,long_name,shop_id,created_at';
            $info = Product::query()->selectRaw($select)->where('id', $id)->first();
            $info->job = $info->name . '-' . time();
            $productJob = new UpdateProduct($info);
            $this->dispatch($productJob);
            return apiResponse('2001');
        } catch (\Exception $e) {
            return apiResponse('2005', $this->failed($e->getMessage() . $e->getLine()));
        }
    }
}
