import {InjectionKey} from "vue";
import {createStore, useStore as baseUseStore, Store} from "vuex";

export interface State {
    webSocketData: object;
}

export const key: InjectionKey<Store<State>> = Symbol();
createStore<State>({
    // 初始化state内容可为空
    state: {
        webSocketData:{},
    },
    // 设置state数据
    mutations: {
        SET_NOW(state, data) {
            state.webSocketData = data;
        }
    },
    // 修改state内容vue页面调用
    actions: {
        change(store: object, data: number) {
            this.commit('SET_NOW', data)
        }
    }
});

export function appStore() {
    return baseUseStore(key);
}
