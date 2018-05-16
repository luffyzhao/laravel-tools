export const adminRouter = [
  { path: 'home', name: 'admin.home', meta: {title: '首页'}, component: resolve => { require(['@/views/admin/common/home.vue'], resolve) } },
  { path: 'profile', name: 'admin.profile', meta: {title: '个人中心'}, component: resolve => { require(['@/views/admin/common/profile.vue'], resolve) } },
  { path: 'article', name: 'article.index', meta: {title: '文章管理'}, component: resolve => { require(['@/views/admin/article/index.vue'], resolve) } },

]
