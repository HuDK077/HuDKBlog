import Http from '../plugins/request'
import urls from "@/configs/urls";
// 调用到方法 api('接口名',参数)
interface Request {
    url: string; // 接口地址
    method: string; // 请求方法
}
const apis = (api: string, data: object = {}) => {
    let requestInfo: Request = urls[api];
    return Http[requestInfo['method']](requestInfo['url'], data)
};
export {
    apis
}
