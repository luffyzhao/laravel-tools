import axios from 'axios'
import Util from '@/libs/util'
import iView from 'iview';
import qs from 'qs'

axios.defaults.baseURL = 'http://127.0.0.1:8000/api/admin/';
axios.defaults.timeout = 3000;

const errorHandle = function(data){
  iView.LoadingBar.error();
  iView.Message.error(data.message);
  if(data.code === 401){
    Util.cache.delete('token')
  }
}


// 添加请求拦截器
axios.interceptors.request.use((config) => {
  iView.LoadingBar.start();
  config.headers = {
    'Accept': 'application/json'
  }
  let token = null;
  if ((token = Util.cache.get('token')) !== null) {
    config.headers['authorization'] = 'bearer ' + token;
  }
  return config;
}, (error) => {
  errorHandle(error.response.data)
  return Promise.reject(error);
});

// 添加响应拦截器
axios.interceptors.response.use((response) => {
  iView.LoadingBar.finish();
  return response;
}, (error) => {
  try {
    errorHandle(error.response.data)
  } catch (e) {

  }
  return Promise.reject(error);
});


/**
 * get方法
 * @param  {[type]} url         [description]
 * @param  {Object} [params={}] [description]
 * @return {[type]}             [description]
 */
export function fetch(url, params = {}) {
  return new Promise((resolve, reject) => {
    axios.get(url, {
      params: params
    }).then(response => {
      resolve(response.data)
    }).catch(err => {
      reject(err)
    })
  })
}

/**
 * post方法
 * @param  {[type]} url       [description]
 * @param  {Object} [data={}] [description]'application/json'
 * @return {[type]}           [description]
 */
export function post(url, data = {}) {
  return new Promise((resolve, reject) => {
    axios.post(url, data).then(response => {
      resolve(response.data)
    }).catch(err => {
      reject(err)
    })
  })
}

/**
 * patch方法
 * @param  {[type]} url       [description]
 * @param  {Object} [data={}] [description]
 * @return {[type]}           [description]
 */
export function patch(url, data = {}) {
  return new Promise((resolve, reject) => {
    axios.patch(url, data).then(response => {
      resolve(response.data)
    }).catch(err => {
      reject(err)
    })
  })
}

/**
 * put 方法
 * @param  {[type]} url       [description]
 * @param  {Object} [data={}] [description]
 * @return {[type]}           [description]
 */
export function put(url, data = {}) {
  return new Promise((resolve, reject) => {
    axios.put(url, data).then(response => {
      resolve(response.data)
    }).catch(err => {
      reject(err)
    })
  })
}

/**
 * head方法
 * @param  {[type]} url       [description]
 * @param  {Object} [data={}] [description]
 * @return {[type]}           [description]
 */
export function head(url, data = {}){
  return new Promise((resolve, reject) => {
    axios.head(url, data).then(response => {
      resolve(response.data)
    }).catch(err => {
      reject(err)
    })
  })
}

/**
 * delete方法
 * @param  {[type]} url       [description]
 * @param  {Object} [data={}] [description]
 * @return {[type]}           [description]
 */
export function destroy(url, data = {}){
  return new Promise((resolve, reject) => {
    axios.delete(url, data).then(response => {
      resolve(response.data)
    }).catch(err => {
      reject(err)
    })
  })
}

export function download(url, data = {}) {
    window.open(axios.defaults.baseURL + url + '?' + qs.stringify(data), '', 'height=100, width=400, toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no');
}
