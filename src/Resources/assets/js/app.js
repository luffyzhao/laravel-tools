
import Vue from 'vue'
import iView from 'iview'
import VueRouter from 'vue-router'
import App from './App.vue'
import store from './store'
import {router} from '@/router/index'
import Util from '@/libs/util'
import {fetch,post,patch,put,head,destroy,download} from '@/libs/ajax'

Vue.use(VueRouter);
Vue.use(iView);

Vue.prototype.$get = fetch
Vue.prototype.$post = post
Vue.prototype.$patch = patch
Vue.prototype.$put = put
Vue.prototype.$head = head
Vue.prototype.$delete = destroy
Vue.prototype.$download = download

Vue.prototype.$util = Util

const app = new Vue({
    el: '#app',
    store,
    router,
    mounted () {
        this.$nextTick(function () {
            this.$store.commit('init')
        });
    },
    render: h => h(App)
});
