<?php
namespace  App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Config;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;

class ConfigController extends Controller
{

    /**
     * showdoc
     * @catalog 后台/系统设置
     * @title  获取系统配置(对象)
     * @description HuDK
     * @method POST
     * @url http://xx.com/admin/config/getConfigArray
     * @header token 必选 string 设备token
     * @return {"error_code":2001,"data":{"website_keywords":"zly","company_address":null,"website_title":"\u540e\u53f0\u7ba1\u7406\u7cfb\u7edf","company_telephone":"0510-88888888","company_full_name":"\u65e0\u9521\u667a\u51cc\u4e91\u7269\u8054\u7f51\u79d1\u6280\u6709\u9650\u516c\u53f8","website_icp":"xxxxxx","system_version":"0.5.1","company_short_name":"\u667a\u51cc\u4e91","system_author":"zly","system_author_website":"http:\/\/www.wisdomyun.xin","logo":null,"logo_sm":null},"message":"success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param data array 配置
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 10:33 上午
     */
    public function getConfigArray() {
        $config = Config::select('name','value','description')->get();
        $data = [];
        foreach ($config as $item){
            $data[$item->name] = $item->value;
        }
        return apiResponse(2001,$data);
    }

    /**
     * showdoc
     * @catalog 后台/系统设置
     * @title  获取系统配置接口_v1.0
     * @description HuDK
     * @method POST
     * @url http://xx.com/admin/config/getConfig
     * @header token 必选 string 设备token
     * @return{"error_code": 2001,"data": [{"name": "website_keywords","value": "网站名"},{"name": "company_address","value": "江苏无锡"},{"name": "website_title","value": "标题"},{"name": "company_telephone","value": "0510-88888888"},{"name": "company_full_name","value": "公司全名"},{"name": "website_icp","value": "null"},{"name": "system_version","value": "1.0.0"},{"name": "page_size","value": "10"},{"name": "company_short_name","value": "公司短称"},{"name": "system_author","value": "智凌云"},{"name": "system_author_website","value": "www.xxx.com"}],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param data array 配置
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 10:33 上午
     */
    public function getConfig() {
        $config = Config::select('name','value','description')->get();
        return apiResponse(2001,$config);
    }

    /**
     * showdoc
     * @catalog 后台/系统设置
     * @title 更新系统配置接口_v1.0
     * @description HuDK
     * @method POST
     * @url http://xx.com/admin/config/updateConfig
     * @header token 必选 string 设备token
     * @param data 必选 array 配置
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param name int 用户名称
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 10:51 上午
     */
    public function updateConfig(Request $request){
        if (self::GDT('config')){
            return response('',403);
        }
        $where = '';
        $data = $request->all();
        foreach ($data as $item) {
            $where .= " when '{$item['name']}' then '{$item['value']}' ";
            Log::info($where);
        }
        $sql = "update admin_config set value= case name {$where} END";
        try{
            DB::update($sql);
            return apiResponse(2001);
        }catch (\Exception $e){
            return apiResponse(2005);
        }
    }

    /**
     * showdoc
     * @catalog 后台/系统设置
     * @title 添加系统配置接口_v1.0
     * @description HuDK
     * @method POST
     * @url http://xx.com/api//addConfig
     * @header token 必选 string 设备token
     * @param name 必选 string 配置标志名
     * @param value 必选 string 配置值
     * @param description 必选 string 配置描述
     * @return {"error_code": 2001,"data": [],"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/24
     * @TIME: 11:05 上午
     */
    public function addConfig(Request $request){
        if (self::GDT('config')){
            return response('',403);
        }
        try{
            $this->validate($request,[
                "name" => "required|unique:admin_config",
                "description" => "required|unique:admin_config",
                "value" => "required"
            ]);
            $data = [ 'name' => $request->name, 'value' => $request->value, 'description' => $request->description ];
            Config::create($data);
            return apiResponse(2001);
        }catch (\Exception $exception){
            return apiResponse(2008,[],$exception->getMessage());
        }
    }

}
