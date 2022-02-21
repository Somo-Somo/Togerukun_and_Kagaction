import {getCookieValue} from './util';

window.axios = require ('axios');

axios.defaults.withCredentials = true;

// axios の response インターセプター
// レスポンスを受けた後の処理を上書きしている
// 第一引数は成功時の処理
// 第二引数は失敗時の処理
window.axios.interceptors.response.use(
    response => response,
    error => error.response || error
  )