<template>
  <el-button
    :size="size"
    :type="type"
    :plain="plain"
    :round="round"
    :circle="circle"
    :loading="loading"
    :disabled="disabled"
    :icon="icon"
    :autofocus="autofocus"
    :native-type="nativeType"
    v-if="isShow"
    @click="onClick"
  >
    <slot />
  </el-button>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: "eBtn",
  props: {
    size: {
      type: String
    },
    type: {
      type: String
    },
    plain: {
      type: Boolean,
      default: false
    },
    round: {
      type: Boolean,
      default: false
    },
    circle: {
      type: Boolean,
      default: false
    },
    loading: {
      type: Boolean,
      default: false
    },
    disabled: Boolean,
    icon: {
      type: String
    },
    autofocus: {
      type: Boolean
    },
    nativeType: {
      type: String
    },
    tag: {
      type: String | Array
    }
  },
  computed: {
    ...mapGetters({
      widgets: "permission/widgets",
    }),
    isShow() {
      if (this.tag && this.widgets && this.widgets.length) {
        if (this.tag instanceof Array) {
          // 列表比对
          return this.checkArray(this.widgets, this.tag)
        } else {
          // 单个包含
          return this.widgets.includes(this.tag)
        }
      } else {
        // 默认显示
        return true
      }
    }
  },
  data() {
    return {

    }
  },
  methods: {
    onClick(e) {
      this.$emit("click", e)
    },
    // 列表检测
    checkArray(initialArr, finalArr) {
      for (let i = 0; i < finalArr.length; i++) {
        if (initialArr.includes(finalArr[i])) {
          return true;
        }
      }
      return false
    }
  },
}
</script>

<style lang="scss" scoped>
</style>