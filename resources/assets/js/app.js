
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('company-analysis', require('./components/CompanyAnalysis.vue'));
Vue.component('watchlist', require('./components/Watchlist.vue'));
Vue.component('create-watchlist', require('./components/CreateWatchlist.vue'));

let watchlistContainer = {
    template: `
        <div>
            <watchlist v-show="hideWatchlist != index" v-for="(watchlist, index) in watchlists" v-bind:watchlist="watchlist" v-bind:index="index"></watchlist>
            <create-watchlist v-on:createWatchlist="createWatchlist"></create-watchlist>
        </div>
    `,
    data() {
        return {
            hideWatchlist: null;
        }
    }
    props: ['watchlists'], //recieved from Laravel/Blade
    methods: {
        deleteWatchlist(watchlistId, index){
                //hide watchlist getting deleted
            this.hideWatchlist = index;
            axios.delete('/watchlist/' + watchlistId).then(response => {
                if(response.status != 200){
                    //bring the component back
                    this.hideWatchlist = null;
                    return;
                }
                this.watchlists.splice(index, 1);
            }).catch(error => {
                consoled.log('Failed to delete watchlist component. Error: ' + error);
            });
        },
        createWatchlist(title, description){
            axios.post('/watchlist', {title: title, description: description}).then(response => {
                this.watchlists.push(response.data);
            }).catch(error => {
                console.log('Failed to create watchlist. Error: ' + error);
            });
        }
    }
};

const vm = new Vue({
    el: '#app',
    data: {
        watchlist: window.watchlist,
    },
    component: {
        'watchlist-container': watchlistContainer //I hope this component have access to the other components, so they can be rendered inside
    },
    
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