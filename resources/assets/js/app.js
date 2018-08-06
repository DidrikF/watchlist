
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import axiosInstance from './axiosInstance';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import Vue from 'vue';
import AdminPanel from './components/AdminPanel.vue';
import CompanyAnalysis from './components/CompanyAnalysis.vue';
import Watchlist from './components/Watchlist.vue';
import CreateWatchlist from './components/CreateWatchlist.vue';
import AddToWatchlist from './components/AddToWatchlist.vue';
import CreateNotification from './components/CreateNotification.vue';

Vue.component('admin-panel', AdminPanel);
Vue.component('company-analysis', CompanyAnalysis);
Vue.component('watchlist', Watchlist);
Vue.component('create-watchlist', CreateWatchlist);
Vue.component('add-to-watchlist', AddToWatchlist);
Vue.component('create-notification', CreateNotification);


Vue.component('watchlist-container', {
    template: `
        <div>
            <watchlist v-for="(watchlist, index) in watchlistsData" :watchlist="watchlist" :index="index" :triggeredNotifications="triggeredNotifications" v-on:deleteWatchlist="deleteWatchlist"></watchlist>
            <create-watchlist :errorsProp="errors" v-on:createWatchlist="createWatchlist"></create-watchlist> 
        </div>
    `,
    data() {
        return {
            hideWatchlist: null, //holds watchlist id to hide
            watchlistsData: this.watchlists, //Source of truth for watchlists
            errors: [],
        }
    },
    props: ['watchlists', 'triggeredNotifications'], //recieved from Laravel/Blade
    methods: {
        deleteWatchlist(watchlistId, index){ //
            //hide watchlist getting deleted
            console.log('delete watchlist fired, ', watchlistId, index);
            let backup = this.watchlistsData[index];
            this.watchlistsData.splice(index, 1);
            axiosInstance.delete('/watchlist/' + watchlistId).then(response => {
                if(response.status !== 200){
                    this.watchlistsData.splice(index, 0, backup);
                }
            }).catch(error => {
                this.watchlistsData.splice(index, 0, backup);
                consoled.log('Failed to delete watchlist component. Error: ' + error);
            });
        },
        createWatchlist(title, description){
            console.log('create watchlist fired, ', title, description);
            axiosInstance.post('/watchlist', {name: title, description: description}, {validateStatus: function(status) {
                return status < 500; //reject only if status is equal to or above 500
            }}).then(response => {
                if(response.status === 201){
                    this.watchlistsData = response.data;
                    return;
                }
                this.errors = response.data;
                
            }).catch(error => {
                console.log('Failed to create watchlist. Error: ' + error);
            });
        }
    }
});

/*
tinymce.init({
    selector: '#analysis'
});
*/

const vm = new Vue({
    el: '#app',
    data: {
        watchlist: window.watchlist,
    },
    /*component: {
        'watchlist-container': watchlistContainer //I hope this component have access to the other components, so they can be rendered inside
    },*/
    
});


/*___HELPER_FUNCTIONS____-*/


//Am I even using this...
/*
function textarea_height(TextArea, MaxHeight) {
    textarea = document.getElementById(TextArea);
    textareaRows = textarea.value.split("\n");
    if(textareaRows[0] != "undefined" && textareaRows.length < MaxHeight) counter = textareaRows.length;
    else if(textareaRows.length >= MaxHeight) counter = MaxHeight;
    else counter = 1;
    textarea.rows = counter; 
}
*/