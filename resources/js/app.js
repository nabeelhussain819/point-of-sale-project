/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');

window.Vue = require('vue').default;

///integrataion of ant design
import Antd from 'ant-design-vue'
import 'ant-design-vue/dist/antd.css'

Vue.config.productionTip = false;

Vue.use(Antd);
///integrataion of ant design

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


Vue.component('repair', require('./module/repair/index.vue').default);
Vue.component('sales', require('./module/sales/index.vue').default);
Vue.component('sales-listing', require('./module/sales/listing.vue').default);
Vue.component('finance', require('./module/finance/index.vue').default);
Vue.component('refund', require('./module/refund/index.vue').default);
Vue.component('quick-sale', require('./module/sales/quick-sale.vue').default);
Vue.component('inventory', require('./module/inventory/index').default);
Vue.component('tracking', require('./module/tracking/index').default);
Vue.component('purchase-order', require('./module/purchaseOrder/index').default);
Vue.component('reports', require('./module/reports/index').default);
Vue.component('transfer', require('./module/transfer/index').default);
Vue.component('sales-report', require('./module/reports/sales/index').default);
Vue.component('finance-report', require('./module/reports/finance/index').default);
Vue.component('repair-report', require('./module/reports/repair/index').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.prototype.$eventBus = new Vue(); // custom adding event bus if any issue in looping 
const app = new Vue({
    el: '#app',
});
