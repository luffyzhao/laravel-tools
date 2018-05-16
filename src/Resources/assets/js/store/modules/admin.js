import Util from '@/libs/util'

const app = {
  state: {
    currentPage: 'admin.home',
    notCachePages: ['common.login', 'common.error-404', 'common.error-403', 'common.error-500', 'common.lock', 'admin.book.document.index'],
    openPageList: [{
      meta: {title: '首页'},
      path: '/admin/home',
      name: 'admin.home'
    }],
    body: {
      width: document.body.clientWidth,
      height: document.body.clientHeight
    },
    layoutContent:{
      height: 0
    },
  },
  mutations: {
    init (state) {
      state.openPageList = Util.cache.get('openPageList') || state.openPageList
      state.currentPage = Util.cache.get('currentPage') || state.currentPage
      state.body.width = document.body.clientWidth
      state.body.height = document.body.clientHeight
      state.layoutContent.height = state.body.height - 42 - 60
      window.addEventListener('resize', () => {
        state.body.width = document.body.clientWidth
        state.body.height = document.body.clientHeight
        state.layoutContent.height = state.body.height - 42 - 60
      })
    },
    openPage (state, router) {
      if (!Util.exist(router.name, state.notCachePages)) {
        if (state.openPageList.findIndex((n) => n.name === router.name) === -1) {
          state.openPageList.push({
            meta: router.meta,
            path: router.path,
            name: router.name
          })
          Util.cache.set('openPageList', state.openPageList)
        }
        state.currentPage = router.name
        Util.cache.set('currentPage', state.currentPage)
      }
    },
    closePage (state, data) {
      let closePage = state.openPageList[data.index]
      if (typeof closePage === 'object') {
        if (closePage.name === state.currentPage) {
          state.currentPage = state.openPageList[data.index - 1].name
          Util.cache.set('currentPage', state.currentPage)
          data.vm.$router.push(state.openPageList[data.index - 1])
        }
        state.openPageList.splice(data.index, 1)
        Util.cache.set('openPageList', state.openPageList)
      }
    },
    closePages (state, data) {
      if (data.name === 'closeAll') {
        state.openPageList.splice(1)
        Util.cache.set('openPageList', state.openPageList)
        if (state.currentPage !== state.openPageList[0].name) {
          state.currentPage = state.openPageList[0].name
          Util.cache.set('currentPage', state.currentPage)
          data.vm.$router.push(state.openPageList[0])
        }
      } else {
        let index = state.openPageList.findIndex((n) => n.name === state.currentPage)
        let arr = []

        arr.push(state.openPageList[0])
        if (index !== 0) {
          arr.push(state.openPageList[index])
        }
        state.openPageList = arr
        Util.cache.set('openPageList', state.openPageList)
      }
    },
    savePageCache (state, cache) {
      Util.cache.set(`${state.currentPage}_formSaveDraft`, cache)
    }
  },
  actions: {
    getSavePageCache (state) {
      return Util.cache.get(`${state.state.currentPage}_formSaveDraft`)
    }
  }
}

export default app
