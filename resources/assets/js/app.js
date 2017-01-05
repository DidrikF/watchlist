
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

var VueResource = require('vue-resource');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('company-analysis', require('./components/CompanyAnalysis.vue'));



Vue.use(VueResource);

const vm = new Vue({
    el: '#app'
});



/*___HELPER_FUNCTIONS____-*/

//Am I even using this...
function textarea_height(TextArea, MaxHeight) {
    textarea = document.getElementById(TextArea);
    textareaRows = textarea.value.split("\n");
    if(textareaRows[0] != "undefined" && textareaRows.length < MaxHeight) counter = textareaRows.length;
    else if(textareaRows.length >= MaxHeight) counter = MaxHeight;
    else counter = 1;
    textarea.rows = counter; 
}