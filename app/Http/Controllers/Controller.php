<?php

namespace App\Http\Controllers;

use App\Models\Api\Store;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers, GuardDog;

    public $api_user;
    public $admin_user;
    protected $coupon_token = '';

    /*public function __construct()
    {
        //$this->api_user = auth('api')->user();
        //$this->admin_user = auth('admin')->user();
    }*/

    //获取优惠券平台token
    protected function getCouponToken($store_id)
    {
        /*if (auth('api')->user()) {
            $api_user = auth('api')->user();
            $store_id = $api_user->store_id;
        } else {
            $store_id = request('store_id');
        }*/
        $key = $store_id . '_coupon_token';
        if (cache()->has($key)) {
            $this->coupon_token = cache($key);
        } else {
            $store = Store::find($store_id);
            $result = $this->coupon_post('/third/auth/login',['phone' => $store->phone ?? ''],false);
            if ($result && $result['success']) {
                $this->coupon_token = $result['token'];
                cache([$key => $this->coupon_token], now()->addHour());
            } else {
                return apiResponse(2005, $result, $result['message'] ?? '获取Token失败');
            }
        }
    }

    protected function coupon_post($api,$data=[],$token=true)
    {
        $domain = config('api.coupon_url');
        if ($token) {
            $post_url = $domain .'/third/' . $api;
            /*if (isset($result['data']) && $result['data']) {
                $url = config('api.domain');
                foreach ($result['data'] as $key => $value) {
                    if (in_array($key,['first_page_url', 'last_page_url', 'next_page_url', 'prev_page_url', 'path'])
                        && is_string($value) && $value) {
                        $result['data'][$key] = Str::replaceFirst('coupon.wisdomyun.cn:82', $url, $value);
                    }
                }
            }*/
            return Http::withToken($this->coupon_token)->post($post_url, $data)->json();
        } else {
            return Http::post($domain . $api, $data)->json();
        }
    }
}
