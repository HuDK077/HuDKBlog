<?php
/**
 * Created by PhpStorm.
 * User: dingzhipeng
 * Date: 2020/4/21
 * Time: 1:46 PM
 */


if (!function_exists('random')) {
    /**
     * @param $length
     * @param bool $numeric
     * @param bool $topper
     * @return string
     * @author: cracker
     * @DATE: 2020/3/17
     * @TIME: 14:07
     * @NAME: random
     * @annotations:生成随机字符串
     */
    function random($length, $numeric = FALSE, $topper = FALSE)
    {
        $seed = base_convert(md5(microtime() . $_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        if ($numeric) {
            $hash = '';
        } else {
            $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
            $length--;
        }
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++) {
            $hash .= $seed[mt_rand(0, $max)];
        }
        //全部转大写
        if ($topper) {
            $hash = strtoupper($hash);
        }
        return $hash;
    }
}
if (!function_exists('randomStoreId')) {
    /**
     * @return string
     * @author: HuDK
     * @DATE: 2021/05/18
     * @TIME: 16:07
     * @NAME: randomStoreId
     * @annotations:生成商户号
     */
    function randomStoreId()
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
            case 2:return '销售员';
            case 3:return '送水员';
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
            case "text":
                if (in_array($ext, ['pem', 'key'])) {
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


if (!function_exists("allowExtCert")) {
    function allowExtCert($file, $type = [])
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
            case "text":
                if (in_array($ext, ['pem', 'key'])) {
                    return false;
                }
                return true;
            default:
                return true;
        }
    }
}
