
export const commonRouter = [
  {path: 'login', name: 'common.login', meta: {title: 'Login - 登录'}, component: resolve => { require(['@/views/common/Login.vue'], resolve)}},
  {path: '*', name: 'common.error-404', meta: {title: '404-页面不存在'}, component: resolve => { require(['@/views/common/error-page/404.vue'], resolve)}},
  {path: '403', name: 'common.403', meta: {title: '403-权限不足'}, component: resolve => { require(['@/views/common/error-page/403.vue'], resolve)}},
  {path: '500', name: 'common.500', meta: {title: '500-服务端错误'}, component: resolve => { require(['@/views/common/error-page/500.vue'], resolve)}},
  {path: 'lock', name: 'common.lock', meta: {title: 'Lock - 锁定'}, component: resolve => { require(['@/views/common/Lock.vue'], resolve)}},
  {path: '403', name: 'common.403-权限不足', meta: {title: '403-权限不足'}, component: resolve => { require(['@/views/common/error-page/403.vue'], resolve)}},
]
