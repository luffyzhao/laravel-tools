import Vue from 'vue'
import Vuex from 'vuex'

import App from './modules/admin'
import Messages from './modules/messages'

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {},
  mutations: {
  },
  actions: {
  },
  modules: {
    App,
    Messages
  }
})

export default store
