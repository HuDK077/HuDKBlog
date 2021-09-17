export default {
  data() {
    return {
      isLoad: false,
      pageShow: false
    }
  },
  created() {
    console.log("created");
    this.withCreated();
  },
  activated() {
    console.log("activated");
    this.pageShow = true
    if (this.isLoad) {
      this.withCreated()
    } else {
      this.isLoad = true;
    }
  },
  deactivated() {
    this.pageShow = false
  },
  methods: {

  },
}