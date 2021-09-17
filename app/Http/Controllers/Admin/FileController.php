<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use Illuminate\Http\Request;
use Qiniu\Auth;

class FileController extends Controller
{
    /**
     * showdoc
     * @catalog 后台/文件管理
     * @title  图片上传接口
     * @description 图片上传接口
     * @method POST
     * @url http://xx.com/api/file/uploadImage
     * @header token 必选 string 设备token
     * @param file 必选 文件 文件
     * @return
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param name string 用户名称
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 4:14 下午
     */
    public function uploadImage(Request $request)
    {
        return 11;
        $file = $request->file('file');
        // 判断是否上传成功
        if (!$file->isValid()) {
            return ['status' => 0, 'message' => '文件上传失败'];
        }
        $file_Id = md5_file($file);
        $temp_file = File::where('file_id', $file_Id)->first();
        $res = [];
        if (!$temp_file) {
            $client_file_name = $file->getClientOriginalName();//获取用户设置的文件名称
            // 获取文件扩展名
            $mimeType = $file->getMimeType();
            $fileSize = filesize($file);
            $ext = $file->getClientOriginalExtension();
            $ext = strtolower($ext);    #转小写
            // 判断文件类型是否允许
            if (!in_array($ext, ['jpg', 'png', 'gif', 'jpeg'])) {
                return ['status' => 0, 'message' => '文件类型不允许'];
            }
            // 为避免一个文件夹中的文件过多和文件名重复,所以需要设置上传文件夹和文件名
            $path = '/uploads/admin/' . date('Y_m_d');
            $this->setFilePath(storage_path() . $path);
            $fileName = $file_Id . '_' . $client_file_name;
            // 上传文件并判断
            if ($file->move(storage_path() . $path, $fileName)) {
                $data = [
                    'file_id' => $file_Id,
                    'mime_type' => $mimeType,
                    'size' => $fileSize,
                    'file_name' => $fileName,
                    'client_file_name' => $client_file_name,
                    'file_path' => $path . '/' . $fileName,
                    'disk' => env('UPLOAD_DISK', 'local'),
                ];
                File::create($data);
                $res['file_id'] = $file_Id;
            } else {
                return apiResponse(2005, [], '上传失败');
            }
        }
        $res['file_id'] = $file_Id;
        return apiResponse(2001, $res);
    }

    /**
     * 检查文件路径
     * @param $path
     * @return string
     * @author: cracker
     * @DATE: 2020/5/8
     * @TIME: 17:49
     * @NAME: setFilePath
     */
    public function setFilePath($path)
    {
        // 修正路径和文件后缀名
        $path = rtrim($path, '/') . '/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }


    /**
     * showdoc
     * @catalog 后台/文件管理
     * @title 获取文件接口
     * @description 获取文件接口
     * @method POST
     * @url http://xx.com/api/file/getFile
     * @header token 必选 string 设备token
     * @param file_id 必选 string 文件ID
     * @return{"error_code": 2001,"data": {"file": "流文件"},"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param image base64 图片base64编码
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/28
     * @TIME: 8:59 上午
     */
    public function getFile(Request $request)
    {
        $file_id = $request->file_id;
        if (!$file_id) {
            return apiResponse(2004);
        }
        $file = File::where('file_id', $file_id)->first();
        if (!$file) {
            return apiResponse(2006);
        }
        $file_disk = $file->disk;
        switch ($file_disk) {
            case 'local'://存在本地storage
                $temp_path = storage_path() . $file->file_path;
                $pictureData = fread(fopen($temp_path, "r"), $file->size);
//                $base64Img = 'data:' . $file->mime_type . ';base64,' .chunk_split(base64_encode($pictureData));
//                $data = ['image' => $base64Img];
//                $res_data = ['Content-Type' => $file->mime_type];
                return response($pictureData, 200)->header('Content-Type', $file->mime_type);
                break;
            default:
                $data = ['file' => $file->file_path];
                return apiResponse(2001, $data);
                break;
        }
    }

    /**
     * showdoc
     * @catalog 后台/文件管理
     * @title  文件上传接口
     * @description 文件上传接口
     * @method POST
     * @url http://xx.com/api/file/uploadFile
     * @header token 必选 string 设备token
     * @param file 必选 文件 文件
     * @return
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param name string 用户名称
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 4:14 下午
     */
    public function uploadFile(Request $request)
    {
        $file = $request->file('file');
        $type = [];
        if (is_array($request->type)) {
            $type = $request->type;
        }
        // 判断是否上传成功
        if (empty($file) || !$file->isValid()) {
            return ['status' => 0, 'message' => '文件上传失败'];
        }
        $path = '/uploads/admin/' . date('Y_m_d');
        if ($request->path_api) {
            $path = $request->path_api;
        }
        if (allowExt($file, $type)) {
            return ['status' => 0, 'message' => '文件类型不允许'];
        }
        $disk = env('FILE_UPLOAD_DISK', 'local');
        $file_Id = md5_file($file);
        $temp_file = File::where('file_id', $file_Id)->first();
        $res['file_id'] = $file_Id;
        $client_file_name = $file->getClientOriginalName();//获取用户设置的文件名称
        $fileName = $file_Id . '_' . $client_file_name;
        if (!$temp_file) {
            // 获取文件扩展名
            $mimeType = $file->getMimeType();
            $fileSize = filesize($file);
            // 为避免一个文件夹中的文件过多和文件名重复,所以需要设置上传文件夹和文件名
            $this->setFilePath(storage_path() . $path);
            $file_real_path = $file->getRealPath();
            $data = [
                'file_id' => $file_Id,
                'mime_type' => $mimeType,
                'size' => $fileSize,
                'file_name' => $fileName,
                'client_file_name' => $client_file_name,
                'file_path' => $path . '/' . $fileName,
                'disk' => $disk,
            ];
            // 上传文件并判断
            switch ($disk) {
                case "local": //本地
                    if ($file->move(storage_path() . $path, $fileName)) {
                        File::create($data);
                        $res['img'] = env('APP_URL') . '/admin/file/getFile?file_id=' . $file_Id;
                    } else {
                        return apiResponse(2005, [], '上传失败');
                    }
                    break;
                case "qiniu": //七牛cdn
                    $qiniu = \Storage::disk('qiniu');
                    $file_path = 'website/' . $fileName;
                    $file_exists = $qiniu->exists($file_path);
                    $data['file_path'] = $file_path;
                    if (!$file_exists) {
                        $bool = $qiniu->put($file_path, file_get_contents($file->getRealPath()));
                        if ($bool) {
                            File::create($data);
                            $res['img'] = $qiniu->url(['path' => $file_path, 'domainType' => 'default']);
                        } else {
                            return apiResponse(2005, [], '上传失败');
                        }
                    }
                    $res['file_id'] = $file_path;//cdn使用file_path
                    break;
            }
        } else {
            $disk = $temp_file->disk;
            switch ($disk) {
                case "local":
                    $res['img'] = env('APP_URL') . '/admin/file/getFile?file_id=' . $file_Id;
                    break;
                case "qiniu":
                    $qiniu = \Storage::disk('qiniu');
                    $res['img'] = $qiniu->url(['path' => $temp_file->file_path, 'domainType' => 'default']);
                    $res['file_id'] = $temp_file->file_path;
                    break;
            }
        }
        return apiResponse(2001, $res);
    }

    /**
     * showdoc
     * @catalog 后台/文件管理
     * @title 获取上传配置
     * @description 获取文件接口
     * @method POST
     * @url http://xx.com/admin/file/getUploadConfig
     * @header token 必选 string 设备token
     * @return {"error_code": 2001,"data": {"region": "us-east-1","upToken": "1yNoWp3xlH9czhwbJIDVjVtvNM25463j-6SzdATp:s_wFsPMotq-DQxXlPu511Q8gqV0=:eyJzY29wZSI6Indpc2RvbXl1bmRlbW8iLCJkZWFkbGluZSI6MTYyMTg0MDgyMX0="},"message": "success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param data array data
     * @return_param region string 地区
     * @return_param uptoken array 上传token
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/28
     * @TIME: 8:59 上午
     */
    public function getUploadConfig()
    {
        $access_key = env('QINIU_ACCESS_KEY');
        $secret_key = env('QINIU_SECRET_KEY');
        $bucket = env('QINIU_BUCKET');
        $auth = new Auth($access_key, $secret_key);
        $expires = 3600 * 24;
        $policy = null;
        $upToken = $auth->uploadToken($bucket, null, $expires, $policy, true);
        $data = [
            'region' => env('QINIU_DEFAULT_REGION'),
            'uptoken' => $upToken,
        ];
        return apiResponse(2001, $data);
    }

    /**
     * showdoc
     * @catalog 后台/文件管理
     * @title 检查文件是否存在
     * @description 检查文件是否存在
     * @method POST
     * @url http://xx.com/admin/file/getImageFileCheck
     * @header token 必选 string 设备token
     * @param file_id 必选 string 文件ID
     * @return {"error_code":2001,"data":{"file_id":"website/05299d97e4c87ecc6bcbdc606e2afa05_NWjzqMJYPDIK.png","img":"http://qqcyalxmp.hn-bkt.clouddn.com/website/05299d97e4c87ecc6bcbdc606e2afa05_NWjzqMJYPDIK.png"},"message":"success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param data array data
     * @return_param file_id string file_id
     * @return_param img string img
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/28
     * @TIME: 8:59 上午
     */
    public function getImageFileCheck(Request $request)
    {
        $file_id = $request->file_id;
        if (!$file_id) {
            return apiResponse(2004);
        }
        $file = File::where('file_id', $file_id)->first();
        if (!$file) {
            return apiResponse(2006);
        }
        $file_disk = $request->type ?: $file->disk;
        //$file_disk = $file->disk;
        switch ($file_disk) {
            case 'local'://存在本地storage
                $temp_path = storage_path() . $file->file_path;
                $pictureData = fread(fopen($temp_path, "r"), $file->size);
                return response($pictureData, 200)->header('Content-Type', $file->mime_type);
                break;
            case "qiniu":
                $res['file_id'] = $file->file_path;//cdn使用file_path
                $res['img'] = env('QINIU_DOMAIN_FULL') . $file->file_path;
                return apiResponse(2001, $res);
                break;
            default:
                $data = ['file' => $file->file_path];
                return apiResponse(2001, $data);
                break;
        }
    }

    /**
     * showdoc
     * @catalog 后台/文件管理
     * @title  图片信息写入数据库
     * @description 图片上传接口
     * @method POST
     * @url http://xx.com/admin/file/addImageInfo
     * @header token 必选 string 设备token
     * @param file_id 必选 string 文件md5值
     * @param path 必选 string 路径
     * @param size 必选 string 文件大小
     * @param client_file_name 必选 string 客户端文件名称
     * @param mime_type 必选 string 文件类型
     * @return {"error_code":2001,"data":{"file_id":"website/05299d97e4c87ecc6bcbdc606e2afa05_NWjzqMJYPDIK.png","img":"http://qqcyalxmp.hn-bkt.clouddn.com/website/05299d97e4c87ecc6bcbdc606e2afa05_NWjzqMJYPDIK.png"},"message":"success"}
     * @return_param error_code int 返回码
     * @return_param message string 返回说明
     * @return_param name string 用户名称
     * @remark 这里是备注信息
     * @number 99
     * @DATE: 2020/9/25
     * @TIME: 4:14 下午
     */
    public function addImageInfo(Request $request)
    {
        $this->validate($request, [
            "file_id" => "required",
            "mime_type" => "required",
            "size" => "required",
            "client_file_name" => "required",
            "path" => "required",
        ]);
        $temp_file = File::where('file_id', $request->file_id)->first();
        $disk = env('FILE_UPLOAD_DISK', 'local');
        $fileName = $request->file_id . '_' . $request->client_file_name;
        $data = [
            'file_id' => $request->file_id,
            'mime_type' => $request->mime_type,
            'size' => $request->size,
            'file_name' => $fileName,
            'client_file_name' => $request->client_file_name,
            'file_path' => $request->path,
            'disk' => $disk,
        ];
        if (!$temp_file) {
            if ($disk == 'qiniu') {
                File::create($data);
                $res['img'] = env('QINIU_DOMAIN_FULL') . $request->path;//cdn使用file_path
                $res['file_id'] = $request->path;
            }
        } else {
            $res['img'] = env('QINIU_DOMAIN_FULL') . $temp_file->file_path;//cdn使用file_path
            $res['file_id'] = $temp_file->file_path;
        }
        return apiResponse(2001, $res);
    }
}

