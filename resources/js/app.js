import './bootstrap';
import "quasar/dist/quasar.prod.css";

import {createApp} from "vue";
import {Notify, Quasar} from 'quasar';
import langRu from 'quasar/lang/ru'

import emitter from '@/utils/mitt';
window.emitter = emitter;

import VueSocketIO from 'vue-socket.io'
import SocketIO from "socket.io-client"

import middleware from './utils/middleware'
import './utils/interceptors.js';

import moment from 'moment';
import 'moment/dist/locale/ru'
moment.locale('ru')

import { createI18n } from 'vue-i18n'
import ru from "./lang/ru.js";
const i18n = createI18n({
    locale: 'ru',
    messages: {
        ru: ru
    }
})
import '../css/app.scss';

// Import icon libraries
import "@quasar/extras/material-icons/material-icons.css";

import App from "./layouts/App.vue";
import router from './routes';

middleware(router)

const vSocket = new VueSocketIO({
    debug: false,
    connection: SocketIO('http://127.0.0.1:3000'),
});

const myApp = createApp(App);

myApp.config.globalProperties.$moment = moment
myApp.config.globalProperties.$emitter = emitter;

myApp.use(Quasar, {
    lang: langRu,
    plugins: {
        Notify
    },
    config: {
        notify: { /* look at QuasarConfOptions from the API card */ }
    }
});

myApp.use(router);
myApp.use(i18n);
myApp.use(vSocket);
// Assumes you have a <div id="app"></div> in your index.html
myApp.mount('#app')
