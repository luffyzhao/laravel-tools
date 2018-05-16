import Storage from 'web-storage-cache'

let Util = {}
// 缓存
Util.cache = new Storage({
  storage: 'sessionStorage'
})

Util.exist = function (ele, arr) {
  if (arr.indexOf(ele) !== -1) {
    return true
  } else {
    return false
  }
}

export default Util
