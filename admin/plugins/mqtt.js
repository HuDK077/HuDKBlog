import MQTT from "@/common/mqtt";
export default function ({ store, env }, inject) {
  // console.log(env);
  const member = store.getters["auth/member"];
  let { MQTT_SERVER, MQTT_CLIENTID, MQTT_PASSWORD, MQTT_USERNAME } = env;
  let obj = {
    server: MQTT_SERVER,
    clientId: `${MQTT_CLIENTID}_${member.username}`,
    username: MQTT_USERNAME,
    password: MQTT_PASSWORD,
    store
  }
  const mqtt = new MQTT(obj);
  mqtt.init();
  inject("mqtt", mqtt)
}