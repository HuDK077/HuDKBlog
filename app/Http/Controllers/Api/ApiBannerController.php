<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiBannerController extends Controller
{
    /**
     * showdoc
     * @catalog 前台/首页轮播
     * @title 轮播图列表
     * @description  HuDK
     * @method POST
     * @url http://xx.com/api/banner/bannerList
     * @header token 必选 string 设备token
     * @return {"error_code": 2001,"data": [{"id": 14,"sort": 9,"created_at": "2021-06-01 10:37:45","updated_at": "2021-06-01 10:37:45","banner": "images/market/2/2021/cars/cars4bf9193ae68a11a33b.jpg","banner_src": "images/market/2/2021/cars/cars4bf9193ae68a11a33b.jpg"},{"id": 15,"sort": 8,"created_at": "2021-06-01 10:38:20","updated_at": "2021-06-01 10:38:20","banner": "images/market/asodahodhaor444.jpg","banner_src": "images/market/asodahodhaor444.jpg"},{"id": 13,"sort": 1,"created_at": "2021-06-01 10:27:38","updated_at": "2021-06-01 10:27:38","banner": "images/market/2/8012/cars/cars4bf9193ae68a162b9d99eabe0bd1a33b.png","banner_src": "images/market/2/8012/cars/cars4bf9193ae68a162b9d99eabe0bd1a33b.png"}],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @return_param id int 轮播id
     * @return_param banner_src string 轮播图片地址
     * @return_param sort int 排序
     * @return_param created_at string 创建时间
     * @return_param updated_at string 更新时间
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2021/6/16
     * @TIME: 10:56
     * @throws \Illuminate\Validation\ValidationException
     */
    public function bannerList(Request $request)
    {
        try {
            $banners = Banner::select('id','sort','created_at','updated_at','banner',
                DB::raw("CONCAT('" . env('QINIU_DOMAIN_FULL') . "',banner) as banner_src")
            )
                ->orderBy('sort', 'desc')
                ->get();
            return apiResponse(2001,$banners);
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }
}
