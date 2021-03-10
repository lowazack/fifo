import { merge } from 'axios/lib/utils'
window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

window.axios.defaults.baseURL = process.env.MIX_APP_URL + `/api`;

window.axios.defaults.headers.post['Content-Type'] = 'application/json';

window.axios.getAll = function(url, config) {
    return this.request(merge(config || {}, {
        method: 'get',
        url: url,
        paginate: true
    }));
};

window.axios.interceptors.request.use(
    config => {
        if (window.localStorage.getItem('authtoken') !== null) {
            const token = window.localStorage.getItem('authtoken');
            if (token) {
                config.headers.Authorization = `Bearer ${token}`;
            }
        }
        return config;
    },
    error => Promise.reject(error)
);

window.axios.interceptors.response.use(
    async response => {
        if(response.data.token) {
            window.localStorage.setItem('authtoken', response.data.token);
        }
        if(response.data.roles) {
            window.localStorage.setItem('roles', response.data.roles);
        }
        if('paginate' in response.config && response.config.paginate === true){
            if('data' in response.data){
                let responseData = response.data.data;
                if('links' in response.data && response.data.links.next !== null){
                    let next = response.data.links.next;
                    while (next !== null) {
                        let nextPage = await window.axios.get(response.data.links.next, { preserveData: true });
                        responseData.concat(nextPage.data.data);
                        next = nextPage.data.links.next;
                    }
                }
                response.data = responseData;
            }
        }else if('data' in response && 'data' in response.data && !response.config.preserveData){
            response.data = response.data.data;
        }
        return response;
    },
    error => Promise.reject(error)
);

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

window.Pusher = require('pusher-js');

import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    disableStats: true,
    forceTLS: true
});
