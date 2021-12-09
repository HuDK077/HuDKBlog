<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiTestController extends Controller
{
    public function test(Request $request){
//        try {
            $params = $request->all();
            $params['id']=rand(1,999999);
            $params['mq']='Queue';
            $params['request_time']=date('Y-m-d H:i:s');
            $this->dispatch(new Queue($params));
            Log::info("\n\n\n".'-----'.$params['id']);
            return apiResponse('200',$params['id']);

//        } catch (\Exception $e) {
//            return apiResponse('500',[],$e->getMessage());
//        }
    }
}
