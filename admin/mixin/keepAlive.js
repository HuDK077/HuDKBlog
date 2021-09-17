export default {
  data() {
    return {
      isLoad: false,
    }
  },
  created() {
    console.log("created");
    this.withCreated();
  },
  activated() {
    console.log("activated");
    if (this.isLoad) {
      this.withCreated()
    } else {
      this.isLoad = true;
    }
  },
  methods: {

  },
}