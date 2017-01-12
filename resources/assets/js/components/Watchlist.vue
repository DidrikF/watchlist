<template>
	<div class="watchlist-container">
		<div>
			<h2 v-if="!editMode">{{ title }} [{{ watchlist.id }}]</h2>
			<input v-if="editMode" v-model="title">
			<button v-if="!editMode" type="button" title="Edit Watchlist title and description" @click="enableWatchlistEdit">Edit</button> 
			<button type="button" title="Delete Watchlist" @click="deleteWatchlist">X</button>
		</div>
		<div v-if="editMode && validationError.title"> {{ validationError.title }} </div>
		<div>
			<span>Description</span> 
			<a v-if="showDescription && !editMode" @click="showDescription=false"> - </a> <a v-else-if="!showDescription && !editMode" @click="showDescription=true"> + </a> <!-- toggle class -->
		</div>
		<div>
			<div v-if="showDescription && !editMode" class="description">{{ description }}</div>
			<textarea v-if="editMode" v-model="description"></textarea> <!-- can use v-model, but interpolation wont work -->
		</div>
		<div v-if="editMode && validationError.description"> {{ validationError.description }} </div>
		
		<button v-if="editMode" type="button" @click="cancelWatchlistEdit">Cancel</button>
		<button v-if="editMode" type="button" @click="saveWatchlistEdit">Save</button>
		<ul>
			<!-- I'd like to sort these by the score -->
			<watchlist-item
				v-for="item in items" 
				v-bind:item="item"
				v-bind:scores="scores"
				v-on:removeCompany="removeCompany" 
			></watchlist-item> 
			<!-- kebab case might be nessecary, v-bind makes the parent dynamically send data down to the child when data changes, need to use v-bind to make vue evaluate the score value as a js expression (translating it to a number), state of data can only be send down in the compenent stack, do not mutate props in the childs as the state is linked...(line truncated)... -->
		</ul>
		<div> <!--  __SEARCH__ -->
			<input v-on:keyup="dynamicSearch" type="text" name="searchWord" id="searchWord" v-model="searchWord" placeholder="Company name/ticker"></input>
			<button type="button" @click="regularSearch">Search</button>
			<div>
				<span>{{ statusMessage }}</span>
				<ul>
					<li v-for="company in searchResults">
						{{ company.name }} - {{ company.ticker }} - {{ company.exchange }}
						<button type="button" @click="addCompany(company.ticker)">Add</button>
					</li>
				</ul>
			</div>
		<div>
	</div>
</template>
<script>
//---------------------------------------------------------------------------------
var watchlistItem = {
	template: `
		<li>
			{{ item.companyName }} - {{ item.exchange }} - {{ score }}
			<a :href="item.companyLink">Go to company page</a>
			<button type="button" @click="emitRemoveCompany">Remove</button>
		</li>
	`,
	props: ['item', 'scores'],
	//props: ['ticker', 'companyName', 'exchange', 'score', 'companyLink'] //data given to the child component is a list item
	computed: {
		score: () => {
			companyScores = this.scores.find(element => {
				return element.ticker == this.item.ticker;
			});
			return companyScores.financial + companyScores.cash_flow + companyScores.growthPotential + companyScores.risk;

		}
	},
	methods: {
		emitRemoveCompany(){
			this.$emit('removeCompany', 'item.ticker'); //emit event to parent
		}
	}
}
//------------------------------------------------------------------------------------
export default {
	data () {
		return {
			title: this.watchlist.name || null, //set initial value via props from blade
			description: this.watchlist.description || null,
			oldTitle: null,
			oldDescription: null,
			statusMessage: null,
			editMode: false,
			showDescription: false,
			validationError: null,
			items: null,
			itemScores: null,
			searchWord: null,
			prevSearchWord: "",
			searchResults: null, //{name: , ticker: , exchange: }
			searchStatusMessage: null,

			/*[ 
				{ticker: "STO", companyName: "pple Inc", exchange:"NYSE", score: 24, companyLink: "/company/AAPL"}, 
				{ticker: "GOOGL", companyName: "Statoil ASA", exchange:"NYSE", score: 34, companyLink: "/company/STO"}, 
				{ticker: "AAPL", companyName: "AGoogle", exchange:"NYSE", score: 35, companyLink: "/company/GOOGL"} 
			]*/
		}
	},
	props: [
		'watchlist', 'index',
	],
	methods: {
		getWathchlist(){ //Not needed on first reder as all data is passed form Laravel/Blade
			//get watchlist data, title, description and items
			axios.get('/watchlist/' + this.watchlist.id).then(response => {
				this.title = response.data.title; //the DB is the source of truth
				this.description = response.data.description;
				this.items = response.data.items;
			}).catch(error => {
				console.log('Failed to get watchlist data. Error: ' + error);
			});
		},
		enableWatchlistEdit(){
			this.oldTitle = this.title;
			this.oldDescription = this.description;
			this.editMode = true;
			this.validationError = null;
		},
		cancelWatchlistEdit(){
			this.editMode = false;
			this.validationError = null;
			this.title = this.oldTitle;
			this.description = this.oldDescription;
			this.oldTitle = null;
			this.oldDescription = null;
		},
		saveWatchlistEdit(){
			this.validationError = null;
			axios.put('/watchlist/' + this.watchlist.id, {title: this.title, description: this.description}).then(response => {
				if(response.status = 200){
					this.flashMessage(this.statusMessage, "Update successful");
					return;
				}
				this.statusMessage = "Update failed, try again."
				this.validationError = response.data;

			}).catch(error => {
				console.log("Failed to edit watchlist " + this.watchlist.id); 
			});
		},
		deleteWatchlist(){
			let answer = confirm('Are you sure you want to delete the entire watchlist and all of its contents?');
			if(answer == true){
				this.$emit('deleteWatchlist', 'watchlist.id', 'index');
			}else {
				this.statusMessage = 'Canceled deletion';	
			} 
		},
		
		// ____WATCHLIST-ITEM METHODS__________________________________________________________
		removeCompany(ticker){
			//remove company from local items array

			axios.delete('/watchlist/' + this.watchlist.id + '/' + ticker).then(response => {
				if(response.status = 200){
					this.statusMessage = 'Removed ' + ticker + 'from watchlist'; //but remove after a few seconds
					return;
				}
				//add company back to items
			}).catch(error => {
				//add company back to items
				console.log('Failed to remove company from watchlist. Error: ' + error);
			});
		},

		// ___SEARCH RELATED METHODS____________________________________________________________
		addCompany(ticker){
			if(this.items.find(company => { return company.ticker == ticker }) != undefined){
				this.searchStatusMessage = 'That company is allready in the watchlist';
				return;
			}
			//add company to items (I need company data from the search component)
			this.searchStatusMessage = null;
			this.searchResults = null;
			this.searchWord = null;
			let company = this.searchResults.find(searchResult => {return searchResult.ticker == ticker });
			this.items.push(company);

			axios.post('/watchlist/' + this.watchlist.id + '/' + ticker).then(response => {
				this.statusMessage = null; //the visual update of the watchlist is enough
			}).catch(error => {
				console.log('Failed to add item to watchlist. Error: ' + error);
			});
		},
		dynamicSearch(){
			this.searchStatusMessage = null;
			if(this.searchWord != this.prevSearchWord){
				this.prevSearchWord = this.searchWord;
				if(timeout) clearTimeout(timeout);
				timeout = setTimeout(() => {
					axios.get('/search/' + this.searchWord).then(response => {
						this.searchResults = response.data;
						//if no search result
						if(!results.Resultset.Result.count()) this.searchStatusMessage = 'No results';
					}).catch(error => {
						console.log('Search failed. Message: ' + error);
					});

				}, 2000);
			}
		},
		regularSearch(){
			this.prevSearchWord = this.searchWord;
			this.searchStatusMessage = null;
			axios.get('/search/' + this.searchWord).then(response => {
					this.searchResults = response.data; 
					//if no search result
					if(!results.Resultset.Result.count()) this.searchStatusMessage = 'No results';
				}).catch(error => {
					console.log('Search failed. Message: ' + error);
				});
		},
		// ____HELPER FUNCTIONS_____________________________________________________________
		flashMessage(variable, message){
			variable = message;
         	setTimeout(() => { variable = null; }, 4000);
		},
	},
	components: {
		'watchlist-item': watchlistItem,
	},
	mounted() {
		//this.getWathchlist(); All data gotten from Laravel/Blade on first render.
	}
}
</script>
<!-- __________________________________________________________________________________________ -->
<style>
	.watchlist-container{
		padding: 15px;
		border: 2px solid black;
	}
</style>


