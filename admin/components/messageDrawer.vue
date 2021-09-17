<template>
  <div>
    <el-drawer title="未读消息" :visible.sync="drawer" direction="rtl" size="40%">
      <div class="orders">
        <el-button type="text" class="read-all" :disabled="!count.all" @click="readAll">标记全部为已读</el-button>
        <el-tabs v-model="activeName">
          <el-tab-pane name="product">
            <el-badge :value="count.product_order" :hidden="!count.product_order" slot="label" class="message-badge">
              <span>产品订单</span>
            </el-badge>
            <template v-for="(item,index) in product_order">
              <el-row :key="index" class="item" @click.native="toDetail(1,item)">
                <el-col :span="23">
                  <div>
                    {{item.data.name}}
                    <el-tag>{{item.data.phone}}</el-tag>
                    <el-tag effect="dark">{{item.data.delivery_time}}</el-tag>
                  </div>
                  <div>{{item.data.address}}</div>
                </el-col>
                <el-col :span="1" style="text-align:right">
                  <el-button type="text" icon="el-icon-arrow-right" style="font-size:20px"></el-button>
                </el-col>
              </el-row>
            </template>
            <el-divider v-if="!product_order.length">没有消息</el-divider>
          </el-tab-pane>
          <el-tab-pane name="refund">
            <el-badge
              :value="count.product_order_refund"
              :hidden="!count.product_order_refund"
              slot="label"
              class="message-badge"
            >
              <span>订单退款</span>
            </el-badge>
            <template v-for="(item,index) in refund">
              <el-row :key="index" class="item" @click.native="toDetail(3,item)">
                <el-col :span="23">
                  <div>
                    {{item.data.name}}
                    <el-tag>{{item.data.phone}}</el-tag>
                  </div>
                  <div>{{item.data.address}}</div>
                </el-col>
                <el-col :span="1" style="text-align:right">
                  <el-button type="text" icon="el-icon-arrow-right" style="font-size:20px"></el-button>
                </el-col>
              </el-row>
            </template>
            <el-divider v-if="!refund.length">没有消息</el-divider>
          </el-tab-pane>
        </el-tabs>
      </div>
    </el-drawer>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: "messageDrawer",
  data() {
    return {
      drawer: false,
      // all: [],
      artwork_order: [],
      product_order: [],
      refund: [],
      acquisition_apply: [],
      agency_apply: [],
      count: "",
      activeName: "product",
    }
  },
  computed: {
    ...mapGetters({
      msgs: "message/messages",
      show: "message/show",
      isLogin: "auth/isLogin"
    })
  },
  created() {
    this.startHeartbeat();
  },
  beforeDestory() {
    if (this.timer) {
      clearInterval(this.timer)
    }
  },
  methods: {
    // 开始心跳
    startHeartbeat() {
      this.loadData();
      if (this.timer) {
        clearInterval(this.timer)
      }
      this.timer = setInterval(() => {
        this.loadData();
      }, 10000);
    },
    // 停止心跳
    stopHeartbeat() {
      if (this.timer) {
        clearInterval(this.timer)
        this.$store.dispatch("message/resetMessage");
      }
      this.timer = "";
    },
    // 数据加载
    loadData() {
      this.$apis.noticeList()
        .then(res => {
          // console.log(res.data)
          let { error_code, data } = res.data;
          if (error_code == 2001) {
            let { artwork_order, count, product_order, product_order_refund, acquisition_apply, agency_apply } = data;
            this.$store.dispatch("message/setCount", count);
            // this.all = all;
            this.count = count;
            this.artwork_order = artwork_order;
            this.product_order = product_order;
            this.acquisition_apply = acquisition_apply;
            this.agency_apply = agency_apply;
            this.refund = product_order_refund;
          }
        });
    },
    // 去详情
    toDetail(key, row) {
      this.$apis.noticeRead({ ids: [row.id] })
        .then(res => {
          // console.log(res.data)
          let { error_code } = res.data;
          if (error_code == 2001) {
            this.loadData()
          }
        });
      this.$store.dispatch("message/switchStatus", false);
      if (key == 1 || key == 3) {
        this.$router.push({ name: "order.product" })
        this.$store.dispatch("message/setProductId", row.data.order_no);
      } else if (key == 2) {
        this.$router.push({ name: "order.artwork" })
        this.$store.dispatch("message/setArtworkId", row.data.order_no);
      } else if (key == 4) {
        // 代理
        this.$router.push({ name: "apply.agent" })
        this.$store.dispatch("message/setAgencyStatus", true);
      } else if (key == 5) {
        // 集采
        this.$router.push({ name: "apply.buy" })
        this.$store.dispatch("message/setAcquisitionStatus", true);
      }
    },
    // 全部已读
    readAll() {
      this.$confirm("确认标记全部消息为已读", "标记已读", {
        confirmButtonText: "标记已读",
        confirmButtonClass: "el-button--warning",
        cancelButtonText: "取消",
        type: "warning",
      })
        .then(() => {
          return this.$apis.noticeRead()
        })
        .then(res => {
          console.log(res.data);
          let { error_code, message } = res.data;
          if (error_code == 2001) {
            this.loadData();
          } else {
            this.$message.error(message);
          }
        })
        .catch(res => {
          console.log(res);
        });
      // this.$apis.noticeRead()
      //   .then(res => {
      //     let { error_code } = res.data;
      //     if (error_code == 2001) {
      //       this.loadData()
      //     }
      //   });
    }
  },
  watch: {
    show: {
      immediate: true,
      handler(n) {
        this.drawer = !!n;
      }
    },
    drawer(n) {
      if (!n) {
        this.$store.dispatch("message/switchStatus", false)
      }
    },
    isLogin(n) {
      if (n) {
        if (!this.timer) {
          this.startHeartbeat();
        }
      } else {
        this.stopHeartbeat();
      }
    }
  },
}
</script>

<style lang="scss" scoped>
.orders {
  padding: 0 10px;
  .item {
    padding: 10px;
    background-color: rgb(247, 247, 247);
    border-radius: 8px;
    .el-tag {
      line-height: 24px;
      height: 24px;
    }
    &:hover {
      background-color: rgb(237, 237, 237);
    }
    & + .item {
      margin-top: 15px;
    }
  }
  .message-badge {
    /deep/ .el-badge__content {
      top: 12px;
      right: 5px;
    }
  }
  .read-all {
    color: #8c6d3f;
    &.is-disabled {
      color: #c0c4cc;
    }
  }

  .el-tabs /deep/ .el-tabs__content {
    height: 80vh;
    overflow-y: auto;
  }
}
</style>