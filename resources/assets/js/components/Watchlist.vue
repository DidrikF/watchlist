<template>
	<div class="box">
		
		<!--<div v-if="editMode && validationError.title"> {{ validationError.title }} </div>-->

		<div class="modal" v-bind:class="{ 'is-active': deleteWatchlistModal }">
		  <div class="modal-background"></div>
		  <div class="modal-content">
		  	<div class="box has-text-centered">
			    <p>Are you sure you want to delete the watchlist and all of its contents?</p>
			    <p>
				    <button class="button is-danger" @click="deleteWatchlist">Yes</button>
				    <button class="button is-primary" @click="deleteWatchlistModal=false">Hell No!</button>
			    </p>
		    </div>
		  </div>
		  <button class="modal-close" @click="deleteWatchlistModal=false"></button>
		</div>
		
		<div class="columns is-mobile">
		  <div class="column is-10">
		    <h3 class="title is-4" v-if="!editMode">{{ title }}</h3>
			<input class="input" v-if="editMode" v-model="title">
		  </div>
		  <div class="column">
		    <button class="button is-info is-pulled-right" v-if="!editMode" type="button" title="Edit Watchlist title and description" @click="enableWatchlistEdit">Edit</button> 
		  </div>
		  <div class="column">

		    <button class="button is-danger is-pulled-right" type="button" title="Delete Watchlist" @click="deleteWatchlistModal=true">X</button>
		  </div>
		</div>

		<div style="margin-top: 5px">
			<label class="label">Description
				<a v-if="showDescription && !editMode" @click="showDescription=false"> - </a> 
				<a v-else-if="!showDescription && !editMode" @click="showDescription=true"> + </a>
			</label> 
			<textarea class="textarea" v-if="showDescription && !editMode" v-model="description" readonly>
			</textarea>
			<textarea class="textarea" v-if="editMode" v-model="description"></textarea> <!-- can use v-model, but interpolation wont work -->
		</div>
		<!--<div v-if="editMode && validationError.description"> {{ validationError.description }} </div>-->
		<button class="button is-primary is-pulled-right" style="margin: 5px 0 0 15px;" v-if="editMode" type="button" @click="saveWatchlistEdit">Save</button> 
		<button class="button is-warning is-pulled-right" style="margin-top: 5px" v-if="editMode" type="button" @click="cancelWatchlistEdit">Cancel</button> 
		

		<table class="table is-striped">
		  <thead>
		    <tr>
		      <th><abbr title="Position">Pos</abbr></th>
		      <th><abbr title="Company">Company</abbr></th>
		      <th><abbr title="Ticker">Ticker</abbr></th>
		      <th><abbr title="Exchange">Exchange</abbr></th>
		      <th><abbr title="Score">Score</abbr></th>
		      <th><abbr title=""></abbr></th>
		    </tr>
		  </thead>
		  <tbody>
		  	<watchlist-item
				v-for="item in sortedWatchlistItems" 
				v-bind:item="item"
				v-bind:itemScores="itemScores"
				v-on:removeCompany="removeCompany" 
			></watchlist-item> 
		  </tbody>
		</table>
			<!--  __SEARCH__ -->
		<div class="box">
			<div class="columns is-mobile"> 
				<div class="column is-two-thirds">
					<input class="input" v-on:keyup="dynamicSearch" type="text" v-model="searchWord" placeholder="Company/ticker">
				</div>
				<div class="column">
					<a class="button is-outlined" @click="regularSearch">
						<i class="fa fa-search" aria-hidden="true"></i> &nbsp; Search
					</a> 
				</div>
				<div class="column">
					<a v-if="searchResults" class="button is-light is-small is-pulled-right" @click="clearSearch">
					 	Crear search
					</a> 
				</div>
			</div>
				 <span>{{ searchStatusMessage }}</span>
				<table class="table is-striped">

				  <thead v-if="searchResults">
				    <tr>
				      <th><abbr title="Company">Company</abbr></th>
				      <th><abbr title="Ticker">Ticker</abbr></th>
				      <th><abbr title="Exchange">Exchange</abbr></th>
				      <th><abbr title=""></abbr></th>
				    </tr>
				  </thead>
				  <tbody>
				  	<tr v-for="company in searchResults">
				  		<td><a :href="'/company/' + company.symbol">{{ company.name }}</a></td>
						<td>{{ company.symbol }}</td>
						<td>{{ company.exch }}/{{ company.exchDisp }}</td>
						<td><button class="button is-primary is-small" type="button" @click="addCompany(company)">Add</button></td>
					</tr>
				  </tbody>
				</table>
			<div>
		</div>
	</div>
	<!--

	<input class="input" type="text" placeholder="Company/ticker" name="q" value="{{ Request::get('q') }}">
        &nbsp;
        <a class="button is-outlined"><i class="fa fa-search" aria-hidden="true"></i> &nbsp; Search</a>

	{{ item.name }} ({{ item.ticker }}) - {{ item.exchange }} - {{ score }}
			<a :href="link">Go to company page</a>
			<button type="button" @click="emitRemoveCompany">Remove</button>
	-->
</template>
<script>
//---------------------------------------------------------------------------------
var watchlistItem = {
	template: `
		<tr>
			<td>{{ item.position }}</td>
			<td><a :href="link">{{ item.name }}</a></td>
			<td>{{ item.ticker }}</td>
			<td>{{ item.exchange }}</td>
			<td>{{ score }}</td>
			<td><button class="button is-warning is-small" type="button" @click="emitRemoveCompany">Remove</button></td>
		</tr>
	`,
	data() {
		return {
			link: "/company/" + this.item.ticker,
			scores: this.itemScores || [],
		}
	},
	props: ['item', 'itemScores'],
	//props: ['ticker', 'companyName', 'exchange', 'score', 'companyLink'] //data given to the child component is a list item
	computed: {
		score: function(){
			let companyScores = this.scores.find(element => {
				return element.ticker == this.item.ticker;
			});
			if(companyScores){
				return companyScores.financial + companyScores.cash_flow + companyScores.growth_potential + companyScores.risk;
			}else {
				return "None";
			}
		}
	},
	methods: {
		emitRemoveCompany(){
			this.$emit('removeCompany', this.item.ticker); //emit event to parent
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
			validationError: {},
			items: null,
			itemScores: null,
			searchWord: "",
			prevSearchWord: "",
			searchResults: null,
			searchStatusMessage: null,
			timeout:null,
			deleteWatchlistModal: false,
		}
	},
	props: [
		'watchlist', 'index',
	],
	computed: {
        sortedWatchlistItems: function(){
        	if(!this.items) return;
        	let complete = this.items.map((element) => {
        		let score = this.itemScores.filter(itemScore => {
        			return itemScore.ticker == element.ticker;
        		});

        		//score = {cash_flow: 10, financial: 10, growth_potential: 10, risk: 10, ticker: "STO"}
        		//score = score[0];
        		//console.log(score);
        		//console.log(score.cash_flow, score.financial);
        		
        		if(score[0]){
        			score = score[0]
        			let totalScore = score.cash_flow + score.financial + score.growth_potential + score.risk;
        			element['score'] = totalScore;
        			return element;
        		}else {
        			element['score'] = 0;
        			console.log(element);
        			return element;
        		}
        	});
        	console.log('completed:');
        	console.log(complete);

            let sorted = complete.sort(function (x, y) { //prev, curr
                var n = y.score - x.score;
                if (n !== 0) {
                    return n;
                }
                return x.name.localeCompare(y.name);
                //return x.name - y.name;
            });
            let counter = 1;
            sorted = sorted.map(element => {
            	element['position'] = counter;
            	counter++;
            	return element;
            });
            console.log('sorted:');
            console.log(sorted);
            return sorted;
        }
    },
	methods: {
		//This method can be used to get all data needed to display a watchlist
		getWathchlist(){ //Using this primarly to get the watchlist items and scores
			axios.get('/watchlist/' + this.watchlist.id).then(response => {
				this.title = response.data.title; //the DB is the source of truth
				this.description = response.data.description;
				this.itemScores = response.data.scores;
				this.items = response.data.items;
				
			}).catch(error => {
				console.log('Failed to get watchlist data. Error: ' + error);
			});
		},
		enableWatchlistEdit(){
			console.log(this.title, this.description);
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
			this.editMode = false;
			this.validationError = null;
			this.oldTitle = null;
			this.oldDescription = null;
			axios.put('/watchlist/' + this.watchlist.id, {name: this.title, description: this.description}).then(response => {
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
			this.$emit('deleteWatchlist', this.watchlist.id, this.index); //, 
		},
		
		// ____WATCHLIST-ITEM METHODS__________________________________________________________
		removeCompany(ticker){
			axios.delete('/watchlist/' + this.watchlist.id + '/' + ticker).then(response => {
				if(response.status = 200){
					let itemToRemove = this.items.find(item => {
						return item.ticker == ticker;
					});
					let indexToRemove = this.items.indexOf(itemToRemove);
					this.items.splice(indexToRemove, 1);
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
		addCompany(company){
			if(this.items.find(item => { return item.ticker == company.symbol })){
				this.searchStatusMessage = 'That company is allready in the watchlist';
				return;
			}
			this.searchStatusMessage = null;
			this.searchResults = null;
			this.searchWord = null;
			//{{ company.name }} ({{ company.symbol }}) <span> - {{ company.exch }}/{{ company.exchDisp }} </span>
			let itemToAdd = {name: company.name, ticker: company.symbol, exchange: company.exch, industry: null};

			axios.post('/watchlist/' + this.watchlist.id, itemToAdd).then(response => {
				if(response.status != 201 || response.status != 200) {
					this.statusMessage = "Failed to add company";
				}
				this.items = response.data.items;
				this.itemScores = response.data.scores;
			}).catch(error => {
				console.log('Failed to add item to watchlist. Error: ' + error);
			});
		},
		dynamicSearch(){
			this.searchStatusMessage = null;
			if(this.searchWord != this.prevSearchWord && this.searchWord != ""){
				this.prevSearchWord = this.searchWord;
				if(this.timeout) clearTimeout(this.timeout);
				this.timeout = setTimeout(() => {
					axios.get('/jsonsearch/' + this.searchWord).then(response => {
						//if no search result $searchResults->ResultSet->Result as $company
						if(!response.data.ResultSet.Result || !response.data.ResultSet.Result.length) {
							this.searchResults = null;
							this.searchStatusMessage = 'No results';
							return;
						}
						this.searchResults = response.data.ResultSet.Result;
					}).catch(error => {
						this.searchResults = null;
						this.searchStatusMessage = 'No results';
						console.log('Search failed. Message: ' + error);
					});

				}, 400);
			}
		},
		regularSearch(){
			this.prevSearchWord = this.searchWord;
			this.searchStatusMessage = null;
			clearTimeout(this.timeout);
			axios.get('/jsonsearch/' + this.searchWord).then(response => {
					if(!response.data.ResultSet.Result) {
						this.searchResults = null;
						this.searchStatusMessage = 'No results';
						return;
					}
					this.searchResults = response.data.ResultSet.Result; 
					//if no search result
				}).catch(error => {
					this.searchResults = null;
					this.searchStatusMessage = 'No results';
					console.log('Search failed. Message: ' + error);
				});
		},
		clearSearch(){
			this.searchWord = null;
			this.prevSearchWord = null;
			this.searchResults = null;
			this.searchStatusMessage = null;
		},
		// ____HELPER FUNCTIONS_____________________________________________________________
		flashMessage(message){
			this.statusMessage = message;
         	setTimeout(() => { this.statusMessage = null; }, 4000);
		},
	},
	components: {
		'watchlist-item': watchlistItem,
	},
	mounted() {
		this.getWathchlist(); //Need to get watchlist items a and scores
	}
}
</script>
<!-- __________________________________________________________________________________________ -->
<style>
	.watchlist-container, .search-container{
		padding: 15px;
		border: 2px solid black;
	}

</style>


