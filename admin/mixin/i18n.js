import { mapGetters, mapActions } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      locale: 'lang/locale',
      locales: 'lang/locales',
    }),

    localName() {
      return this.locales[this.locale];
    },
  },
  methods: {
    ...mapActions({
      setLocal: "lang/setLocale",
    }),
    // 多语言下拉事件
    onCommand(locale) {
      console.log(locale);
      if (this.$i18n.locale !== locale) {
        this.$loadMessages(locale)
        this.setLocal({ locale })
        // this.$store.dispatch('lang/setLocale', { locale })
      }
    },
  },
}