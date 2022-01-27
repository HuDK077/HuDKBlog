<?php
/**
 * Created by PhpStorm.
 * User: HuDK
 * Date: 2020/4/21
 * Time: 1:46 PM
 */

if (!function_exists("assoc_unique")) {
    /*
     * 二维数组根据某个key值去重
     */
    function assoc_unique($arr, $key)
    {
        $tmp_arr = array();
        foreach ($arr as $k => $v) {
            if (in_array($v[$key], $tmp_arr)) {//搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
                unset($arr[$k]);
            } else {
                array_push($tmp_arr, $v[$key]);
            }
        }
        sort($arr); //sort函数对数组进行排序
        return $arr;
    }
}

if (!function_exists("list_to_tree")) {
    //数组转树
    function list_to_tree($menus, $id = 0)
    {
        $tree = array();
        if (is_array($menus)) {
            foreach ($menus as $menu) {
                if ($menu['parent_id'] == $id) {
                    $child = list_to_tree($menus, $menu['id']);
                    if ($child) {
                        $menu['children'] = $child;
                    }
                    array_push($tree, $menu);
                }
            }
        }
        return $tree;
    }
}

if (!function_exists('randomCertificateId')) {
    /**
     * @return string
     * @author: HuDK
     * @DATE: 2021/05/18
     * @TIME: 16:07
     * @NAME: randomStoreId
     * @annotations:生成商户号
     */
    function randomCertificateId()
    {
        $store_id_main = date('YmdHis') . rand(10000000,99999999);
        $store_id_len = strlen($store_id_main);
        $store_id_sum = 0;
        for($i=0; $i<$store_id_len; $i++){
            $store_id_sum += (int)(substr($store_id_main,$i,1));
        }
        $osn = str_pad((100 - $store_id_sum % 100) % 100,2,'0',STR_PAD_LEFT).$store_id_main;
        return $osn;
    }
}

if (!function_exists('memberType')){
    /*
     * 获取会员类型
     */
    function memberType($type){
        switch ($type){
            case 1:return '普通用户';
            case 2:return '设计师';
            case 3:return '工艺师';
            default:return $type;
        }
    }
}

if (!function_exists('checkMiddleware')) {
    /**
     * 检查中间件 本地模式和生产模式
     * @param $app_env
     * @return array
     * @author: cracker
     * @DATE: 2020/5/21
     * @TIME: 10:29
     * @NAME: checkMiddleware
     */
    function checkMiddleware($route){
        if (env('APP_ENV') == 'local') {
            return [];
        }else{
            if ($route == 'api') {
                return ['apiAuth'];
            }else{
                return ['adminAuth'];
            }
        }
    }
}

/**
 * 判断来源 H5/小程序
 */
if (!function_exists('get_req_header_type')) {
    function get_req_header_type()
    {
        if (array_key_exists('Req-Sources', getallheaders())) {
            return getallheaders()['Req-Sources'];
        }
        return null;
    }
}


if (!function_exists('apiResponse')) {
    /**
     * 公共返回
     *
     * 2001 success
     *
     * 2002 数据异常
     *
     * 2003 数据类型不匹配
     *
     * 2004 缺少必要参数
     *
     * 2005 error
     *
     * 2006 资源不存在
     *
     * @param $error_code
     * @param array $data
     * @param string $message
     * @return array
     * @author: cracker
     * @DATE: 2020/5/10
     * @TIME: 00:05
     * @NAME: apiResponse
     */
    function apiResponse($error_code, $data = [], $message = ""){
        switch ($error_code) {
            case 2001:return ['error_code' => $error_code, 'data' => $data, 'message' => 'success'];
            case 2002:return ['error_code' => $error_code, 'data' => $data, 'message' => '数据异常'];
            case 2003:return ['error_code' => $error_code, 'data' => $data, 'message' => '数据类型不匹配'];
            case 2004:return ['error_code' => $error_code, 'data' => $data, 'message' => '缺少必要参数'];
            case 2005:return ['error_code' => $error_code, 'data' => $data, 'message' => $message ? $message : 'error'];
            case 2006:return ['error_code' => $error_code, 'data' => $data, 'message' => '资源不存在'];
            default:return ['error_code' => $error_code, 'data' => $data, 'message' => $message];
        }
    }
}


if (! function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        return Arr::get($array, $key, $default);
    }
}

if (!function_exists("allowExt")) {
    //允许上传文件类型判断
    //docx  application/vnd.openxmlformats-officedocument.wordprocessingml.document
    //xlsx  application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
    //pdf   application/pdf
    //jpeg  image/jpeg
    //jpg  image/jpg
    //gif  image/gif
    //png   image/png
    //icns  image/vnd.microsoft.icon
    function allowExt($file, $type = [])
    {
        $ext = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $ext = strtolower($ext);
        $type_file = substr($mimeType, 0, strpos($mimeType, "/"));
        if (count($type) != 0) {
            if (!in_array($type_file, $type)) {
                return true;
            }
        }
        switch ($type_file) {
            case "application":
                if (in_array($ext, ['pdf', 'xlsx', 'docx'])) {
                    return false;
                }
                return true;
            case "video":
                if (in_array($ext, ['rmvb', 'rm', 'flv', 'mp4', '3gp', 'wmv', 'avi'])) {
                    return false;
                }
                return true;
            case "image":
                if (in_array($ext, ['jpg', 'png', 'gif', 'jpeg'])) {
                    return false;
                }
                return true;
            default:
                return true;
        }
    }
}

if (!function_exists('GetRandStr')) {
    /**
     * 获取随机长度字符串
     *
     * @param ArrayAccess|array $array
     * @return array
     */
    function GetRandStr($length)
    {
        //字符组合
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str) - 1;
        $randstr = '';
        for ($i = 0; $i < $length; $i++) {
            $num = mt_rand(0, $len);
            $randstr .= $str[$num];
        }
        return $randstr;
    }
}

if (!function_exists('get_order_no')) {
    /**
     * 获取随机长度字符串
     *
     * @param $member_id
     * @return string
     */
    function get_order_no($member_id)
    {
        return date("ymdHis") . $member_id . (int)(explode(' ', microtime())[0] * 1000000);
    }
}

if (!function_exists('get_code')) {
    /*函数名称:get_code()
    *作用:取得随机字符串
    * 参数:
    1、(int)$length = 32 #随机字符长度
    2、(int)$mode = 0    #随机字符类型，
    0为大小写英文和数字,1为数字,2为小写字母,3为大写字母,
    4为大小写字母,5为大写字母和数字,6为小写字母和数字
    *返回:取得的字符串
    */
    function get_rand_code($length=16,$mode=0)//获取随机验证码函数
    {
        switch ($mode)
        {
            case '1':
                $str='123456789';
                break;
            case '2':
                $str='abcdefghijklmnopqrstuvwxyz';
                break;
            case '3':
                $str='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case '4':
                $str='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
                break;
            case '5':
                $str='ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
                break;
            case '6':
                $str='abcdefghijklmnopqrstuvwxyz1234567890';
                break;
            default:
                $str='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
                break;
        }
        $checkstr='';
        $len=strlen($str)-1;
        for ($i=0;$i<$length;$i++)
        {
            //$num=rand(0,$len);//产生一个0到$len之间的随机数
            $num=mt_rand(0,$len);//产生一个0到$len之间的随机数
            $checkstr.=$str[$num];
        }
        return $checkstr;
    }
}
