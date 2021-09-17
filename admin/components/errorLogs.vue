<template>
  <el-dialog title="错误日志" :visible.sync="dialogTableVisible" width="80%">
    <div class="dialog-body">
      <el-table :data="errorLogs" border height="60vh" width="100%">
        <el-table-column width="500" label="Message">
          <template slot-scope="scope">
            <div class="error-item">
              <span class="message-title">Msg:</span>
              <el-tag class="message" type="danger">{{ scope.row.msg }}</el-tag>
            </div>
            <div class="error-item">
              <span class="message-title">Info:</span>
              <el-tag type="warning">{{scope.row.info}}</el-tag>
            </div>
            <div class="error-item">
              <span class="message-title">Url:</span>
              <el-tag type="success">{{scope.row.url}}</el-tag>
            </div>
            <div class="error-item" v-if="scope.row.status_code">
              <span class="message-title">Code:</span>
              <el-tag type="info">{{scope.row.status_code}}</el-tag>
            </div>
            <div class="error-item">
              <span class="message-title">Type:</span>
              <el-tag type="info">{{scope.row.type}}</el-tag>
            </div>
          </template>
        </el-table-column>
        <el-table-column label="Stack">
          <template slot-scope="scope">{{ scope.row.stack}}</template>
        </el-table-column>
      </el-table>
    </div>
  </el-dialog>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  name: 'errorLogs',
  data() {
    return {
      dialogTableVisible: false
    }
  },
  computed: {
    ...mapGetters({
      errorLogs: "error/logs",
      show: "error/show",
    })
  },
  methods: {

  },
  watch: {
    show(n) {
      this.dialogTableVisible = !!n;
    },
    dialogTableVisible(n) {
      if (!n) {
        this.$store.dispatch("error/switchStatus", false)
      }
    }
  },
}
</script>

<style lang="scss" scoped>
.dialog-body {
  height: 60vh;
  overflow: hidden;
  overflow-y: auto;

  .error-item {
    padding: 10px 0;
    display: flex;
    align-items: center;
    .message-title {
      font-size: 16px;
      color: #333;
      font-weight: bold;
      padding-right: 8px;
      width: 3em;
      flex: 0 0 3em;
      display: inline-block;
    }
    .el-tag.message {
      display: inline-block;
      width: 100%;
      flex: auto;
      white-space: break-spaces;
      height: auto;
    }
  }
}
</style>