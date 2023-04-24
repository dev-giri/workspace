require("./bootstrap");

window.Vue = require("vue").default;

import Vuex from "vuex";
Vue.use(Vuex);

import StoreVueX from "./store/index";

const store = new Vuex.Store(StoreVueX);

Vue.component("main-app", require("./components/MainApp.vue").default);

import filter from "./filter";

import VueChatScroll from "vue-chat-scroll";
Vue.use(VueChatScroll);

import Notifications from "vue-notification";
Vue.use(Notifications);

const app = new Vue({
    el: "#app",
    store
});
