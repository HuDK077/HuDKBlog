<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Api\Member;
use Illuminate\Http\Request;
use Exception;

class MemberController extends Controller
{
    /**
     * 获取所有用户接口
     * showdoc
     * @catalog 后台/用户管理
     * @title 获取所有用户接口
     * @description 获取所有用户接口
     * @method POST
     * @url http://xx.com/admin/member/getAllMember
     * @header token 必选 string 设备token
     * @param limit 非必选 int 分页
     * @param search 非必选 string 搜索数据
     * @return {"current_page": 1,"data": [{"id": 1,"openid": "owtNA5SrDdC83SDSFj7Z5AVjVvgA","unionid": null,"nickname": "¿","avatar": "https://thirdwx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJfOHA0NhmFPTXK4liatWvW81lr2F152CFTwTNZJNFGaichssFYL4PhCsHRNeQytQIOKdEVKCa9t6uQ/132","gender": 0,"province": "Jiangsu","city": "Wuxi","phone": null,"created_at": "2020-09-23 15:13:05","updated_at": "2020-09-24 10:06:24"}],"first_page_url": "http://qiyuan.cc/admin/member/getAllMember?page=1","from": 1,"last_page": 1,"last_page_url": "http://qiyuan.cc/admin/member/getAllMember?page=1","next_page_url": null,"path": "http://qiyuan.cc/admin/member/getAllMember","per_page": 15,"prev_page_url": null,"to": 1,"total": 1}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/28
     * @TIME: 9:54 上午
     */
    public function getAllMember(Request $request)
    {
        if ($this->GDT('member')){
            return response('',403);
        }
        $limit = $request->limit ? $request->limit : 15; #分页数据
        $members = Member::where(function ($query) use ($request) {
            $search = $request->search;
            if ($search) {
                $query->where('nickname', 'like', '%' . $search . '%')
                    ->orwhere('phone',$search);
            }
        })->orderBy('id', 'desc')->paginate($limit);
        return apiResponse(2001,$members);
    }

    /**
     * 更新用户信息
     * showdoc
     * @catalog 后台/用户管理
     * @title 更新用户信息
     * @description 更新用户信息
     * @method POST
     * @url http://xx.com/admin/member/updateMember
     * @header token 必选 string 设备token
     * @param id 必选 int 用户ID
     * @param real_name 非必选 string 用户姓名
     * @param phone 非必选 string 用户手机号
     * @param type 非必选 int 用户类型  1 普通用户 2 销售员 3送水员
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/28
     * @TIME: 1:43 下午
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateMember(Request $request){
        if ($this->GDT('member')){
            return response('',403);
        }
        $this->validate($request,[
            'id' => 'required'
        ]);
        try{
            $data = ['real_name' => $request->real_name,'phone' => $request->phone,'id' => $request->id,'type' => $request->type];
            Member::where('id',$request->id)->update($data);
            return apiResponse(2001);
        }catch (Exception $exception){
            return apiResponse(2005,[],$exception->getMessage());
        }
    }
}
