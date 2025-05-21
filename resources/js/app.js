/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

// registration page
Vue.component('registration-component', require('./components/Registration/RegistrationComponent.vue').default);
Vue.component('edit-registration-component', require('./components/EditRegistrationComponent.vue').default);
Vue.component('edit-lookup-component', require('./components/EditLookupComponent.vue').default);
Vue.component('create-lookup-component', require('./components/CreateLookUpComponent.vue').default);
Vue.component('barcode-component', require('./components/BarcodeComponent.vue').default);
Vue.component('ticket-component', require('./components/TicketComponent.vue').default);
Vue.component('banner-component', require('./components/BannerComponent.vue').default);
Vue.component('upload-component', require('./components/UploadComponent.vue').default);
Vue.component('booking', require('./components/BookingComponent.vue').default);
Vue.component('pagination', require('./components/Common/Pagination.vue').default);
Vue.component('create-user-component', require('./components/CreateUserComponent.vue').default);
Vue.component('events-component', require('./components/EventsComponent.vue').default);

import Vue from 'vue';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import '../css/app.css';
import { Loading } from 'element-ui';
import VueQRCodeComponent from 'vue-qrcode-component'

import { func } from '../js/func.js';

import locale from 'element-ui/lib/locale/lang/en'

import { allCountries } from '../js/countries';
Vue.prototype.$allCountries = allCountries

Vue.prototype.$func = func

// Loading.service({ fullscreen: true })

Vue.config.lang = 'en'

Vue.use(ElementUI, { locale });

Vue.component('qr-code', VueQRCodeComponent);

const app = new Vue({
    el: '#app',
    data: function () {
        return {
            visible: false,
            search: null,
        }
    }
});
