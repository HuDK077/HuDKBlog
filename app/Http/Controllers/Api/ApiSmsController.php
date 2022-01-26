<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Psy\Util\Json;
use TencentCloud\Sms\V20210111\SmsClient;
use TencentCloud\Sms\V20210111\Models\SendSmsRequest;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use function Symfony\Component\Translation\t;

class ApiSmsController extends Controller
{
    private $APP_ID;
    private $APP_KEY;
    private $TEMPLATE_ID;
    private $SECRET_ID;
    private $SECRET_KEY;

    public function __construct()
    {
        $this->APP_ID = env('TENCENT_SMS_APPID', 'default');
        $this->APP_KEY = env('TENCENT_SMS_APP_KEY', 'default');
        $this->TEMPLATE_ID = env('TENCENT_SMS_TEMPLATE_ID', 'default');
        $this->SECRET_ID = env('SECRET_ID', 'default');
        $this->SECRET_KEY = env('SECRET_KEY', 'default');
    }

    public function sendSms(Request $request)
    {
        $phone = '+8615152056569';
        $cred = new Credential($this->SECRET_ID, $this->SECRET_KEY);
        $httpProfile = new HttpProfile();
        $httpProfile->setReqMethod("POST");  // post请求(默认为post请求)
        $httpProfile->setReqTimeout(30);    // 请求超时时间，单位为秒(默认60秒)
        $httpProfile->setEndpoint("sms.tencentcloudapi.com");  // 指定接入地域域名(默认就近接入)
        // 实例化一个client选项，可选的，没有特殊需求可以跳过
        $clientProfile = new ClientProfile();
        $clientProfile->setSignMethod("TC3-HMAC-SHA256");  // 指定签名算法(默认为HmacSHA256)
        $clientProfile->setHttpProfile($httpProfile);
        // 实例化要请求产品(以sms为例)的client对象,clientProfile是可选的
        // 第二个参数是地域信息，可以直接填写字符串 ap-guangzhou，或者引用预设的常量
        $client = new SmsClient($cred, "ap-guangzhou", $clientProfile);
        // 实例化一个 sms 发送短信请求对象,每个接口都会对应一个request对象。
        $req = new SendSmsRequest();
        /* 短信应用ID: 短信SdkAppId在 [短信控制台] 添加应用后生成的实际SdkAppId，示例如1400006666 */
        $req->SmsSdkAppId = $this->APP_ID;
        /* 短信签名内容: 使用 UTF-8 编码，必须填写已审核通过的签名，签名信息可登录 [短信控制台] 查看 */
        $req->SignName = "编程问题记录分享";
        /* 短信码号扩展号: 默认未开通，如需开通请联系 [sms helper] */
        $req->ExtendCode = "";
        /* 下发手机号码，采用 E.164 标准，+[国家或地区码][手机号]
         * 示例如：+8613711112222， 其中前面有一个+号 ，86为国家码， ，最多不要超过200个手机号*/
        $req->PhoneNumberSet = array($phone);
        /* 国际/港澳台短信 SenderId: 国内短信填空，默认未开通，如需开通请联系 [sms helper] */
        $req->SenderId = "";
        /* 用户的 session 内容: 可以携带用户侧 ID 等上下文信息，server 会原样返回 */
        $req->SessionContext = $request->choose;
        /* 模板 ID: 必须填写已审核通过的模板 ID。模板ID可登录 [短信控制台] 查看 */
        $req->TemplateId = $this->TEMPLATE_ID;
        /* 模板参数: 若无模板参数，则设置为空*/
        if ($request->choose == 1) {
            $text = '同意';
        } else {
            $text = '拒绝';
        }
        $req->TemplateParamSet = array("", $text);
        $resp = $client->SendSms($req);
        $resArr = json_decode($resp->toJsonString(),true);
        if($resArr['SendStatusSet'][0]['Code'] == 'Ok'){
            return apiResponse('200');
        }else{
            return apiResponse('500',[],$resArr['SendStatusSet'][0]['Message']);
        }
    }
}
