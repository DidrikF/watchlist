<template>
	<div class="watchlist-container">
		<h2>{{ title }}</h2> <!-- How to accept input -->
		<button type="button" title="Edit Watchlist title and description" @click="enableWatchlistEdit">Edit</button> 
		<button type="button" title="Delete Watchlist" @click="deleteWatchlist">X</button>
		<span>Description</span> <a v-if="" @click=""> - </a> <a v-if="" @click=""> + </a> <!-- toggle class -->
		<div class="description">{{ description }}</div>
		<button type="button" @click="cancelWatchlistEdit">Cancel</button>
		<button type="button" @click="saveWatchlistEdit">Save</button>
		<ul>
			<!-- I'd like to sort these by the score -->
			<watchlist-item
				v-for="item in items" 
				v-bind:item="item"
				v-on:removeCompany="removeCompany" 
			></watchlist-item> 
			<!-- kebab case might be nessecary, v-bind makes the parent dynamically send data down to the child when data changes, need to use v-bind to make vue evaluate the score value as a js expression (translating it to a number), state of data can only be send down in the compenent stack, do not mutate props in the childs as the state is linked...(line truncated)... -->
		</ul>
		<search v-on:addCompany="addCompany"></search>
	</div>
</template>
<script>

var watchlistItem = {
	template: `
		<li>
			{{ item.companyName }} - {{ item.exchange }} - {{ item.score }}
			<a :href="item.companyLink">Go to company page</a>
			<button type="button" @click="emitRemoveCompany">Remove</button>
		</li>
	`,
	props: ['item'],
	//props: ['ticker', 'companyName', 'exchange', 'score', 'companyLink'] //data given to the child component is a list item
	methods: {
		emitRemoveCompany(){
			this.$emit('removeCompany', 'item.ticker'); //emit event to parent
		}
	}
}
var searchComponent = {
	template: `
		<div>
			<input type="text" name="searchWord" id="searchWord" v-model="searchWord" placeholder="Company name/ticker"></input>
			<button type="button" @click="search">Search</button>
			<div>
				<span>{{ statusMessage }}</span>
				<ul>
					<li v-for="company in results">
						{{ company.name }} - {{ company.ticker }} - {{ company.exchange }}
						<button type="button" @click="addCompany(company.ticker)">Add</button>
					</li>
				</ul>
			</div>
		<div>
	`,
	data() {
		return {
			searchWord: null, //
			results: null, //{name: , ticker: , exchange: }
			statusMessage: null, //Don't know if I need this
		}
	},
	props: ['watchlistId'],
	methods: {
		search(){
			this.statusMessage = null;
			axios.get('/search/' + this.searchWord).then(response => {
				this.results = response.data;
				//of no search result
				if(!results.Resultset.Result.count()) this.statusMessage = 'No results';
			}).catch(error => {
				console.log('Search failed. Message: ' + error);
			});
		},
		addCompany(ticker){ //company.ticker
			//clear the search:
			this.statusMessage = null;
			this.results = null;
			this.searchWord = null;
			this.$emit('addCompany', 'ticker');
		}
		
	}
}

export default {
	data () {
		return {
			title: "Watchlist title", //set initial value via props from blade
			description: "Watchlist description, lorim ipsum lorem fatuin horm lezolatoulium",
			statusMessage: null,
			items: [ 
				{ticker: "STO", companyName: "Statoil ASA", exchange:"NYSE", score: 34, companyLink: "/company/STO"}, 
				{ticker: "AAPL", companyName: "Apple Inc", exchange:"NYSE", score: 24, companyLink: "/company/AAPL"}, 
				{ticker: "GOOGL", companyName: "Google", exchange:"NYSE", score: 35, companyLink: "/company/GOOGL"} 
			]
		}
	},
	props: [
		'watchlistId' //gotten from blade
	],
	methods: {
		getWathchlist(){
			//get watchlist data, title, description and items
			axios.get('/watchlist/' + this.watchlistId).then(response => {
				this.title = response.data.title;
				this.description = response.data.description;
				this.items = response.data.items;
			}).catch();
		},
		enableWatchlistEdit(){

		},
		cancelWatchlistEdit(){

		},
		saveWatchlistEdit(){
			axios.put('/watchlist/' + this.watchlistId, {title: this.title, description: this.description}).then(response => {
				if(response.status = 200){
					this.statusMessage = "Update successful";
					return;
				}
				this.statusMessage = "Update failed, try again."

			}).catch(error => {
				console.log("Failed to edit watchlist " + this.watchlistId); 
			});
		},
		deleteWatchlist(){
			let answer = confirm('Are you sure you want to delete the entire watchlist and all of its contents?');
			if(answer == true) this.$emit('deleteWatchlist', 'watchlistId');
			else this.statusMessage = 'Canceled deletion';
		},
		
		//WATCHLIST-ITEM METHODS
		removeCompany(ticker){
			//remove company from local items array

			axios.delete('/watchlist/' + this.watchlistId + '/' + ticker).then(response => {
				if(response.status = 200){
					this.statusMessage = 'Removed ' + ticker + 'from watchlist'; //but remove after a few seconds
					return;
				}
				//add company back to items
			}).catch(error => {
				//add company back to items
				console.log('Failed to remove company from watchlist. Error: ' + error);
			});
		}
		addCompany(ticker){
			if(this.items.find(company => { return company.ticker == ticker }) != undefined){
				this.statusMessage = 'That company is allready in the watchlist';
				return;
			}
			//add company to items (I need company data from the search component)

			axios.post('/watchlist/' + this.watchlistId + '/' + ticker).then(response => {
				this.statusMessage = null; //the visual update of the watchlist is enough
			}).catch(error => {
				console.log('Failed to add item to watchlist. Error: ' + error);
			});
		}
	},
	components: {
		'watchlist-item': watchlistItem,
		'search': searchComponent,
	},
	mounted() {
		this.getWathchlist();
	}
}
</script>
<style>
	.watchlist-container{
		padding: 15px;
		border: 2px solid black;
	}
</style>


