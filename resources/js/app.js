require('./bootstrap');

import Vue from 'vue'
import CoreuiVue from '@coreui/vue-pro'
import { linearSet, duotoneSet, solidSet, flagSet, brandSet } from '@coreui/icons-pro'
import store from './store'

Vue.prototype.$apiAdress = process.env.MIX_APP_URL
Vue.config.performance = true
Vue.use(CoreuiVue)

import helpers from './helpers'

const plugin = {
    install () {
        Vue.helpers = helpers
        Vue.prototype.$helpers = helpers
    }
}

Vue.use(plugin)

import App from './Main'

import router from './router'
