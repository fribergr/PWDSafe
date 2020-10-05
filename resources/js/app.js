/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Clipboard from 'v-clipboard';
Vue.use(Clipboard);


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('dropdown-menu', require('./components/DropdownMenu.vue').default);
Vue.component('dropdown-link', require('./components/DropdownLink.vue').default);
Vue.component('pwdsafe-button', require('./components/Button.vue').default);
Vue.component('pwdsafe-alert', require('./components/Alert.vue').default);
Vue.component('pwdsafe-label', require('./components/Label.vue').default);
Vue.component('pwdsafe-input', require('./components/Input.vue').default);
Vue.component('pwdsafe-textarea', require('./components/Textarea.vue').default);
Vue.component('pwdsafe-select', require('./components/Select.vue').default);
Vue.component('credential-card', require('./components/CredentialCard.vue').default);
Vue.component('pwdsafe-modal', require('./components/Modal.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data() {
        return {
            mobileMenuOpen: false
        }
    }
});
