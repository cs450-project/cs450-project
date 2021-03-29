import Vue from "vue";
import BootstrapVue from "bootstrap-vue";

import App from "./App.vue";
import router from "./router";
import store from "./store";

import "./assets/scss/cs450.scss";

Vue.use(BootstrapVue);

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: (h) => h(App),
}).$mount("#app");
