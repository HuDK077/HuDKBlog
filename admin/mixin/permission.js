import { mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            inter: "permission/interface",
        }),
    },
    methods: {
        // 列表检测
        checkArray(initialArr, finalArr) {
            for (let i = 0; i < finalArr.length; i++) {
                if (initialArr.includes(finalArr[i])) {
                    return true;
                }
            }
            return false
        },
        checkOne(tag) {
            return this.inter.includes(tag)
        }
    },
}
