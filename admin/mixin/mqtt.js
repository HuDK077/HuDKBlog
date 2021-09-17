import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      connected: "mqtt/connected",
      connecting: "mqtt/connecting",
    }),
  },
  methods: {
    // mqtt 连接/断开
    switchStatus() {
      if (this.connected) {
        this.$mqtt.disconnect();
      } else {
        this.$mqtt.connect();
      }
    }
  },
}