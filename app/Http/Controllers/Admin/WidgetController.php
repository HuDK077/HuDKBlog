<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminWidget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WidgetController extends Controller
{
    /**
     * showdoc
     * @catalog 后台/控件管理
     * @title 添加控件
     * @description  方官喜
     * @method POST
     * @url http://xx.com/admin/widget/addWidget
     * @header token 必选 string 设备token
     * @param menu_id 必选 int 菜单id
     * @param name 必选 string 控件名称
     * @param slug 必选 string 标识
     * @param uri 不必选 string 方法链接
     * @return {"error_code": 2001, "data": [], "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2021/10/21
     * @TIME: 下午2:39
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addWidget(Request $request)
    {
        if ($this->GDT('widget')) {
            return response('', 403);
        }
        $this->validate($request, [
            'menu_id' => 'required',
            'name' => 'required',
            'slug' => 'required',
        ]);
        $data = [
            'menu_id' => $request->menu_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'uri' => $request->uri,
        ];
        try {
            return DB::transaction(function () use ($request, $data) {
                try {
                    AdminWidget::create($data);
                    return apiResponse(2001);
                } catch (\Exception $exception) {
                    throw new \Exception($exception->getMessage());
                }
            });
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 后台/控件管理
     * @title 修改控件
     * @description  方官喜
     * @method POST
     * @url http://xx.com/admin/widget/updateWidget
     * @header token 必选 string 设备token
     * @param id 必选 int 控件id
     * @param menu_id 必选 int 菜单id
     * @param name 必选 string 控件名称
     * @param slug 必选 string 标识
     * @param uri 不必选 string 方法链接
     * @return {"error_code": 2001, "data": [], "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2021/10/21
     * @TIME: 下午4:47
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function updateWidget(Request $request)
    {
        if ($this->GDT('widget')) {
            return response('', 403);
        }
        $this->validate($request, [
            'id' => 'required',
            'menu_id' => 'required',
            'name' => 'required',
            'slug' => 'required',
        ]);
        $data = [
            'menu_id' => $request->menu_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'uri' => $request->uri,
        ];
        try {
            return DB::transaction(function () use ($request, $data) {
                try {
                    AdminWidget::find($request->id)->update($data);
                    return apiResponse(2001);
                } catch (\Exception $exception) {
                    throw new \Exception($exception->getMessage());
                }
            });
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 后台/控件管理
     * @title 删除控件
     * @description  方官喜
     * @method POST
     * @url http://xx.com/admin/widget/deleteWidget
     * @header token 必选 string 设备token
     * @param ids 必选 array 控件id(支持批量删除)
     * @return {"error_code": 2001, "data": [], "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2021/10/21
     * @TIME: 下午4:55
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function deleteWidget(Request $request)
    {
        if ($this->GDT('widget')) {
            return response('', 403);
        }
        $this->validate($request, [
            'ids' => 'required|array'
        ]);
        try {
            return DB::transaction(function () use ($request) {
                try {
                    AdminWidget::destroy($request->ids);
                    return apiResponse(2001);
                } catch (\Exception $exception) {
                    throw new \Exception($exception->getMessage());
                }
            });
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 后台/控件管理
     * @title 获取单个控件
     * @description  方官喜
     * @method POST
     * @url http://xx.com/admin/widget/getWidget
     * @header token 必选 string 设备token
     * @param id 必选 int 控件id
     * @return {"error_code": 2001, "data": { "id": 2, "menu_id": 2, "name": "添加用户", "slug": "user.add", "uri": "/admin/user/addUser", "created_at": "2021-10-19 10:25:52", "updated_at": "2021-10-19 10:25:52" }, "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @return_param id int 控件id
     * @return_param menu_id int 菜单id
     * @return_param name string 控件名称
     * @return_param slug string 标识
     * @return_param uri string 方法链接
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2021/10/21
     * @TIME: 下午5:01
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function getWidget(Request $request)
    {
        if ($this->GDT('widget')) {
            return response('', 403);
        }
        $this->validate($request, [
            'id' => 'required'
        ]);
        try {
            return DB::transaction(function () use ($request) {
                try {
                    $widget = AdminWidget::find($request->id);
                    return apiResponse(2001, $widget);
                } catch (\Exception $exception) {
                    throw new \Exception($exception->getMessage());
                }
            });
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }

    /**
     * showdoc
     * @catalog 后台/控件管理
     * @title 获取控件列表
     * @description  方官喜
     * @method POST
     * @url http://xx.com/admin/widget/listWidget
     * @header token 必选 string 设备token
     * @param menu_id 不必选 int 菜单id
     * @return {"error_code": 2001, "data": [ { "id": 2, "menu_id": 2, "name": "添加用户", "slug": "user.add", "uri": "/admin/user/addUser", "created_at": "2021-10-19 10:25:52", "updated_at": "2021-10-19 10:25:52" }, { "id": 3, "menu_id": 2, "name": "编辑用户", "slug": "user.edit", "uri": "/admin/user/editUser", "created_at": "2021-10-19 10:25:52", "updated_at": "2021-10-19 10:25:52" }, { "id": 4, "menu_id": 2, "name": "更新用户", "slug": "user.update", "uri": "/admin/user/updateUser", "created_at": "2021-10-19 10:25:52", "updated_at": "2021-10-19 10:25:52" }, { "id": 5, "menu_id": 2, "name": "删除用户", "slug": "user.delete", "uri": "/admin/user/deleteUser", "created_at": "2021-10-19 10:25:52", "updated_at": "2021-10-19 10:25:52" }, { "id": 6, "menu_id": 2, "name": "获取用户", "slug": "user.get", "uri": "/admin/user/getUser", "created_at": "2021-10-19 10:25:52", "updated_at": "2021-10-19 10:25:52" } ], "message": "success" }
     * @return_param error_code int 返回码
     * @return_param message string 返回说明,2001成功
     * @return_param id int 控件id
     * @return_param menu_id int 菜单id
     * @return_param name string 控件名称
     * @return_param slug string 标识
     * @return_param uri string 方法链接
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2021/10/21
     * @TIME: 下午5:13
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */
    public function listWidget(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                try {
                    $list = AdminWidget::where(function ($query) use ($request) {
                        if ($request->menu_id) {
                            $query->where('menu_id', $request->menu_id);
                        }
                    })->get();
                    return apiResponse(2001, $list);
                } catch (\Exception $exception) {
                    throw new \Exception($exception->getMessage());
                }
            });
        } catch (\Exception $exception) {
            return apiResponse(2005, [], $exception->getMessage());
        }
    }
}
