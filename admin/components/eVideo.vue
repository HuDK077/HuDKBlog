<template>
  <video ref="video" v-if="url" :src="url">
    <svg
      t="1602660788414"
      class="icon"
      viewBox="0 0 1024 1024"
      version="1.1"
      xmlns="http://www.w3.org/2000/svg"
      p-id="16378"
      width="100%"
      height="100%"
    >
      <path
        d="M906.24 157.184h-344.064l-25.6 65.536h316.928c22.016 0 39.424 17.408 39.424 39.424v249.344L771.072 389.632c-12.8-12.8-33.28-12.8-46.08 0l-104.96 104.96 65.024 37.888-137.216 268.288-33.792 65.536H906.24c29.184 0 52.736-23.552 52.736-52.736v-604.16c0-28.672-23.552-52.224-52.736-52.224z m-360.448 412.16l-93.696 93.696-128-128c-10.24-10.24-27.136-10.24-37.376 0l-156.16 155.648V261.632c0-22.016 17.92-39.424 39.424-39.424h256.512l25.6-65.536H117.76c-29.184 0-52.736 23.552-52.736 52.736v604.672c0 29.184 23.552 52.736 52.736 52.736h282.112l33.792-65.536 117.248-228.864-5.12-3.072z"
        fill="#bfbfbf"
        p-id="16379"
      />
      <path
        d="M411.136 866.816h-23.552l148.992-290.816-210.944-123.392 115.712-295.424h22.016l-112.128 286.72 212.48 124.416z"
        fill="#bfbfbf"
        p-id="16380"
      />
      <path
        d="M523.776 866.816h-23.552l172.032-330.24-219.136-128 98.304-251.392h20.992l-94.208 242.176 221.184 129.536z"
        fill="#bfbfbf"
        p-id="16381"
      />
    </svg>
  </video>
</template>

<script>
export default {
  name: "eVideo",
  props: {
    id: String,
    src: String,
  },
  data() {
    return {
      url: "",
      isLoad: false
    }
  },
  created() {
    this.loadVideo()
  },
  methods: {
    loadVideo(n) {
      if (this.id) {
        this.url = this.jointUrl(this.id);
      } else if (this.src) {
        this.url = n || this.src;
      }
    },
    jointUrl(id) {
      return this.$env.API_URL + this.$url.getFile + '?file_id=' + id;
    },
  },
  computed: {
    video() {
      return this.$refs["video"] || undefined;
    }
  },
  watch: {
    id(n) {
      this.loadVideo(n)
    },
    src(n) {
      this.loadVideo(n)
    }
  },
}
</script>

<style lang="scss" scoped>
.load-error,
.load-placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #f4f4f5;
}
</style>