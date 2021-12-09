<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Api\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Type;

class BannerController extends Controller
{
    /**
     * showdoc
     * @catalog 后台/首页轮播管理
     * @title 轮播列表
     * @description  HuDK
     * @method POST
     * @url http://xx.com/admin/banner/bannerList
     * @header token 必选 string 设备token
     * @return  {"error_code": 2001,"data": [{"id": 16,"sort": 2,"type": null,"created_at": "2021-06-16 10:51:13","updated_at": "2021-06-16 10:51:13","banner": "anaiosdoa124134.png","banner_src": "anaiosdoa124134.png"}],"message": "success"}
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
     * @TIME: 10:21
     */
    public function bannerList(Request $request)
    {
        if ($this->GDT('banner.list')) {
            return response('', 403);
        }
        try {
            $banners = Banner::select('id', 'sort', 'created_at', 'updated_at', 'banner',
                DB::raw("CONCAT('" . env('QINIU_DOMAIN_FULL') . "',banner) as banner_src")
            )
                ->orderBy('sort', 'desc')
                ->get();
            return apiResponse(2001, $banners);
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 后台/首页轮播管理
     * @title   轮播添加
     * @description  HuDK
     * @method POST
     * @url http://xx.com/admin/banner/addBanner
     * @header token 必选 string 设备token
     * @param banner 必选 string 轮播图地址
     * @param sort 不必选 int 轮播图排序(默认为1)
     * @return {"error_code": 2001, "data": [], "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2021/6/16
     * @TIME: 10:29
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addBanner(Request $request)
    {
        if ($this->GDT('banner.add')) {
            return response('', 403);
        }
        $this->validate($request, [
            'banner' => 'required',
        ]);
        $data = ['banner' => $request->banner];
        if ($request->sort) {
            $data['sort'] = $request->sort;
        }
        try {
            Banner::create($data);
            return apiResponse(2001, [], '新增成功');
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 后台/首页轮播管理
     * @title   更新轮播
     * @description  HuDK
     * @method POST
     * @url http://xx.com/admin/banner/updateBanner
     * @header token 必选 string 设备token
     * @param id 必选 int 轮播id
     * @param banner 必选 string 轮播图地址
     * @param sort 必选 int 排序
     * @return {"error_code": 2001, "data": [], "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2021/6/16
     * @TIME: 10:50
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateBanner(Request $request)
    {
        if ($this->GDT('banner.update')) {
            return response('', 403);
        }
        $this->validate($request, [
            'id' => 'required',
            'banner' => 'required',
        ]);
        $data = ['banner' => $request->banner];
        if ($request->sort) {
            $data['sort'] = $request->sort;
        }
        try {
            Banner::where('id', $request->id)->update($data);
            return apiResponse(2001);
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 后台/首页轮播管理
     * @title   轮播详情
     * @description  HuDK
     * @method POST
     * @url http://xx.com/admin/banner/bannerDetail
     * @header token 必选 string 设备token
     * @param id 必选 int 轮播id
     * @return {"error_code": 2001,"data": {"id": 13,"banner": "images/market/2/8012/cars/cars4bf9193ae68a162b9d99eabe0bd1a33b.png","sort": 1,"created_at": "2021-06-01 10:27:38","updated_at": "2021-06-01 10:27:38","banner_src": "images/market/2/8012/cars/cars4bf9193ae68a162b9d99eabe0bd1a33b.png"},"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @return_param id int 轮播id
     * @return_param banner string 轮播图地址
     * @return_param sort int 排序
     * @return_param created_at string 创建时间
     * @return_param updated_at string 更新时间
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2021/6/16
     * @TIME: 11:08
     * @throws \Illuminate\Validation\ValidationException
     */
    public function bannerDetail(Request $request)
    {
        if ($this->GDT('banner.detail')) {
            return response('', 403);
        }
        $this->validate($request, [
            'id' => 'required',
        ]);
        try {
            $banner = Banner::where('id', $request->id)
                ->select('*',
                    DB::raw("CONCAT('" . env('QINIU_DOMAIN_FULL') . "',banner) as banner_src")
                )
                ->first();
            if (empty($banner)) {
                return apiResponse(2005, [], "轮播图不存在");
            }
            return apiResponse(2001, $banner);
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 后台/首页轮播管理
     * @title  轮播删除
     * @description  HuDK
     * @method POST
     * @url http://xx.com/admin/banner/deleteBanner
     * @header token 必选 string 设备token
     * @param id 必选 int 轮播id
     * @return {"error_code": 2001, "data": [], "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2021/6/16
     * @TIME: 11:16
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deleteBanner(Request $request)
    {
        if ($this->GDT('banner.delete')) {
            return response('', 403);
        }
        $this->validate($request, [
            'id' => "required",
        ]);
        try {
            Banner::where('id', $request->id)->delete();
            return apiResponse(2001);
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }
}
