import mqtt from "mqtt";

export class MQTT {
  constructor({ server, clientId, username, password, store }) {
    this._server = server;
    this._clientId = clientId;
    this._username = username;
    this._password = password;
    this._store = store;
    this.connector = "";
    this._subscribeTopics = [
      "farmsynthesize/receive/#"
    ];
    this._subscribed = []
  }
  /**
   * 初始化
   */
  init() {
    this.opt = { clientId: this._clientId, username: this._username, password: this._password, }
    let timeStamp = this._store.getters["mqtt/timestamp"];
    if (Date.now() - timeStamp < 600000) {
      this.connect();
    }
  }
  /**
   * 连接服务器
   */
  connect() {
    this._setConnect("connect")
    if (this.connector) {
      this.connector.reconnect(this.opts);
      return
    }
    this.connector = mqtt.connect(this._server, this.opt)
    this.connector.on('connect', (res) => {
      console.log("connect", res);
      this._setConnect("connected");
      this.subscribeList();
    })

    this.connector.on('message', this._onMessage.bind(this))

    this.connector.on('packetsend', this._onPacketsend.bind(this))

    this.connector.on('error', (res) => {
      console.log("error", res);
      this._store.dispatch("mqtt/setConnect", "disconnected")
    })
  }
  /**
   * 断开连接
   */
  disconnect() {
    if (!this.connector) { return }
    this._setConnect("disconnect")
    this.connector.end(true, this.opt, () => {
      console.log("disconnect");
      this._setConnect("disconnected")
    })
  }

  /**
   * 批量订阅
   */
  subscribeList() {
    if (!this._testConnect()) {
      return;
    }
    this._subscribeTopics.forEach(v => {
      if (!this._subscribed.includes(v)) {
        this.connector.subscribe(v, (err, arr) => {
          console.log(err, arr);
          if (!err) {
            this._subscribed.push(v)
          } else {
            console.warn("topic", v, "订阅失败");
          }
        })
      }
    });
  }
  /**
   * 单个订阅
   * @param {String} topic 主题
   * @param {Object} opt 配置
   */
  subscribe(topic, opt) {
    if (!this._testConnect()) {
      return;
    }
    if (!opt instanceof Object || opt instanceof Array) {
      opt = { qos: 0 }
    }
    if (!this._subscribed.includes(topic)) {
      this.connector.subscribe(topic, opt, (err, arr) => {
        console.log(err, arr);
        if (!err) {
          this._subscribed.push(topic)
        } else {
          console.warn("topic", topic, "订阅失败");
        }
      })
    }
  }
  /**
   * 取消订阅
   * @param {String} topic 主题
   */
  unsubscribe(topic) {
    if (!this._testConnect()) {
      return;
    }
    this.connector.unsubscribe(topic, (err, arr) => {
      console.log(err, arr);
      if (!err) {
        this._subscribed.splice(this._subscribed.indexOf(topic), 1)
      } else {
        console.warn("topic", topic, "取消订阅失败");
      }
    })
  }
  /**
   * 推送消息
   * @param {String} topic 主题
   * @param {Object} message 消息
   * @param {Object} opt 配置
   */
  publish(topic, message, opt) {
    if (!this._testConnect()) {
      return;
    }
    if (!opt instanceof Object || opt instanceof Array) {
      opt = { qos: 0 }
    }
    if (typeof message == "object") {
      message = JSON.stringify(message);
    }
    this.connector.publish(topic, message, opt, (res) => {
      // console.log(res);
    })
  }
  /**
   * 消息回调
   * @param {String} topic 主题
   * @param {*} message 消息
   */
  _onMessage(topic, message) {
    // console.log(arguments);
    if (message) {
      let msg = message.toString();
      try {
        msg = JSON.parse(msg)
      } catch (error) {
        console.error(error);
      }
      // console.log(topic, msg);
      if (topic == "emqx/hello") { this._hello(msg); }
      if (topic == "farmsynthesize/receive/") { this._recevie(msg); }
    }

  }
  /**
   * 连接事件
   * @param {Object} res 信息
   */
  _onPacketsend(res) {
    console.log("packetsend", res);
    if (res.cmd) {
      switch (res.cmd) {
        case "connect":
          this._setConnect("connect");
          break;
        case "subscribe":

          break;
        case "unsubscribe":

          break;
        case "pingreq":
          this._store.dispatch("mqtt/refreshTime")
          break;

        default:
          break;
      }
    }
  }
  /**
   * 连接状态
   */
  _testConnect() {
    if (!this.connector || !this._store.getters["mqtt/connected"]) {
      console.warn("mqtt 服务未连接");
      return false
    }
    return true
  }
  /**
   * 设置状态属性
   * @param {String} status 状态
   */
  _setConnect(status) {
    this._store.dispatch("mqtt/setConnect", status)
  }
  /**
   * 消息回调函数
   */
  /**
   * 回调消息
   * @param {String} msg 消息
   */
  _hello(msg) {
    console.log(msg);
    this._store.dispatch("mqtt/setHelloMsg", msg)
  }
  /**
 * 回调消息
 * @param {String} msg 消息
 */
  _recevie(msg) {
    // console.log(msg);
    this._store.dispatch("mqtt/setReceive", msg)
  }


}

export default MQTT;