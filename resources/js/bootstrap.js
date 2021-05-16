window._ = require('lodash');

/**
 * Loading Bootstrap and dependencies: jQuery and Popper
 */
try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
} catch (e) {}

/**
 * Loading axios HTTP library
 */
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Register the CSRF Token
 */
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Creating Vue Events handler
 */
window.Vue = require('vue');
window.events = new Vue();

/**
 * Importing localization
 */
window.lang = [];
window.lang.en = require('./../lang/en.json');
