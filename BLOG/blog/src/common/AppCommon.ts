import { session } from '../utils/session';
export class AppCommon {
    // ⽤户
    private static _userInfo:any;
    // 获取⽤户信息
    static get userInfo() {
        if (!this._userInfo) {
            this._userInfo = session.get("_userInfo");
        }
        return this._userInfo;
    }
    // 设置⽤户信息
    static set userInfo(value) {
        this._userInfo = value;
        session.set("_userInfo", value);
    }
    //token
    private static _token:any;
    // 获取token
    static get token() {
        if (!this._token) {
            this._token = session.get("_token");
        }
        return this._token;
    }
    // 设置token
    static set token(value) {
        this._token = value;
        session.set("_token", value);
    }
}
