export default class SocketService {
    static instance:any;
    static get Instance() {
        if (SocketService.instance) {
            SocketService.instance = new SocketService();
        }
        return SocketService.instance;
    }
    // 和服务端连接的socket对象
    static ws:any;
    // 标识是否连接成功
    static connected:any;
    // 记录重试的次数
    static sendRetryCount:number = 0;
    // 重新连接尝试的次数
    static connectRetryCount:number = 0;
    //  定义连接服务器的方法
    static connect() {
        // 连接服务器
        if (!window.WebSocket) {
            return console.log('您的浏览器不支持WebSocket');
        }
        let url:string = process.env.VUE_APP_WS;
        SocketService.ws = new WebSocket(url, 'echo-protocol');
        // 连接成功的事件
        SocketService.ws.onopen = () => {
            console.log('连接服务端成功了');
            SocketService.connected = true;
            // 重置重新连接的次数
            SocketService.connectRetryCount = 0;
        };
        // 1.连接服务端失败
        // 2.当连接成功之后, 服务器关闭的情况
        SocketService.ws.onclose = () => {
            console.log('连接服务端失败');
            SocketService.connected = false;
            SocketService.connectRetryCount++;
            setTimeout(() => {
                this.connect();
            }, 500 * SocketService.connectRetryCount);
        };
        // 得到服务端发送过来的数据
        SocketService.ws.onmessage = msg => {
            console.log('从服务端获取到了数据：', msg.data );
        };
    }
    // 发送数据的方法
    static send(data:string) {
        // 判断此时此刻有没有连接成功
        if (SocketService.connected) {
            SocketService.sendRetryCount = 0;
            try {
                SocketService.ws.send(JSON.stringify(data));
            } catch (e) {
                SocketService.ws.send(data);
            }
        } else {
            SocketService.sendRetryCount++;
            setTimeout(() => {
                this.send(data);
            }, SocketService.sendRetryCount * 500);
        }
    }
    // 关闭连接
    static close(){
        if(SocketService){
            SocketService.sendRetryCount = 0;
            try {
                SocketService.ws.onclose();
            } catch (e) {
                console.log(e)
            }

        }else{
            console.log('连接未开启')
        }
    }
}

export {
    SocketService
}
