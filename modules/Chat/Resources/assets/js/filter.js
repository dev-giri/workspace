import Vue from "vue";
import moment from "moment";

// Global Filter
Vue.filter("timeFormat", function(arg) {
    return moment(arg).format("MMMM Do YYYY, h:mm:ss a");
});
