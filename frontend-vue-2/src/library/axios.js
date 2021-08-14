import Vue from "vue";
import axios from "axios";
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = process.env.API_URL;
Vue.prototype.$axios = axios;
