/**
 * This code block can be directly in your main.js file if you keep it this simple
 * otherwise extract it to its own file and require it in main.js
 *
 * Don't forget that the code below will be executed within every request.
 *
 */

import API from './api.js'
import {apiUrl} from "@/constants/config";
let disabled_loaded_content = [
    apiUrl +'/'+ 'docs/regulation/list',
    'feeds/all',
    apiUrl +'/'+ 'docs/trust/list',
    apiUrl +'/'+ 'docs/contract/list',
    apiUrl +'/'+ 'docs/template/list',
    apiUrl +'/'+ 'learning/catalog',
    apiUrl +'/'+ 'employee/list',
    apiUrl +'/'+ 'application/list',
    apiUrl +'/'+ 'quote/list',
    apiUrl +'/'+ 'news/list',
    apiUrl +'/'+ 'news/all',
    apiUrl +'/'+ 'polling/list',
    apiUrl +'/'+ 'feed/list',
    apiUrl +'/'+ 'role/list',
    apiUrl +'/'+ 'gallery/catalog',

];

API.interceptors.request.use(function (config) {
    if(disabled_loaded_content.indexOf(config.url) > -1){
        emitter.emit('contentLoaded', true);
    }
    let token = window.localStorage.getItem('token')
    config.headers.Authorization = 'Bearer ' + token;
    return config;
});

API.interceptors.response.use(
    (response) => {
        if(disabled_loaded_content.indexOf(response.config.url) > -1) {
            emitter.emit('contentLoaded', false);
        }
        return response
    },
    (err) => {
        if (err.response.statusText === 'Unauthorized') {
            localStorage.removeItem('auth-user');
            localStorage.removeItem('token');
            localStorage.removeItem('employee');
            emitter.emit('loggedOut');
        }

        if(disabled_loaded_content.indexOf(err.config.url) > -1) {
            emitter.emit('contentLoaded', false);
        }
        return Promise.reject(err);
    }
)
