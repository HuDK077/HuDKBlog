import { AppCommon } from '@/common/AppCommon'
import axios from 'axios'
// 创建 axios 实例
const service = axios.create({
    baseURL: process.env.VUE_APP_URL,
    timeout: 20000, // request timeout
})
// 请求拦截器
service.interceptors.request.use(
    config => {
        config.headers['Content-Type'] = 'application/json;charset=UTF-8'
        config.headers['Accept'] = 'application/json'
        let token = AppCommon.token;
        if (token && token['access_token'] && token['refresh_token']) {
            config.headers['Authorization'] = token['access_token']
            config.headers['RefreshToken'] = token['refresh_token']
        }
        return config
    },
    (error) => {
        return Promise.reject(error)
    }
)
// 响应拦截器
service.interceptors.response.use(
    (response) => {
        //届时根据后端返回success或者code值判断
        return response
    },
    (error) => {
        //响应错误
        let status = error.response.status //固定
        if (status === 400) {
            //Message.error('参数错误')
        }
        if (status === 401) {
            //Message.error('登录过期,请重新登录')
        }
        if (status === 403) {
            //Message.error('没有权限')
        }
        if (status === 404) {
            // Message.error('接⼝路径错误')
        }
        if (status === 500) {
            //Message.error('服务器出错')
        }
        if (status === 503) {
            //Message.error('服务器在维护')
        }
        let msg={
            status:status,
            err_msg: error.response.data.message
    }
        return Promise.reject(msg)
    }
)
const post=(url: string, data: object = {},withToken=true) =>{
    return new Promise((resolve: Function, reject: Function) => {
        service({
            method: 'post',
            url,
            data: data,
            responseType:'json'
        })
            .then(res => {
                resolve(res.data)
            })
            .catch(err => {
                reject(err)
            });
    })
};
const get = (url: string, data: object = {}, withToken = true)=> {
    return new Promise((resolve, reject) => {
        service({
            method: 'get',
            url,
            params: data,
            responseType: 'json'
        })
            .then(res => {
                resolve(res.data)
            })
            .catch(err => {
                reject(err)
            })
    })
}
const put=(url: string, data: object = {},withToken=true) =>{
    return new Promise((resolve: Function, reject: Function) => {
        service({
            method: 'post',
            url,
            data: data,
            responseType:'json'
        })
            .then(res => {
                resolve(res.data)
            })
            .catch(err => {
                reject(err)
            });
    })
};
const deleted=(url: string, data: object = {},withToken=true) =>{
    return new Promise((resolve: Function, reject: Function) => {
        service({
            method: 'post',
            url,
            data: data,
            responseType:'json'
        })
            .then(res => {
                resolve(res.data)
            })
            .catch(err => {
                reject(err)
            });
    })
};
export default {
    get,
    post,
    put,
    deleted,
};
