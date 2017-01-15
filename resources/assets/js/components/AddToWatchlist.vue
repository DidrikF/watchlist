<template>
	<div>
		<div class="is-pulled-left" style="margin: 30px 0 0 0;">{{ statusMessage }}</div>
		<div class="is-pulled-right" style="margin: 20px;" v-if="watchlists.length">
			<select class="select" v-model="selected">
				<option v-for="watchlist in watchlists" v-bind:value="watchlist.id">{{ watchlist.name }}</option>
			</select>
			<button class="button is-primary" type="button" @click="addCompany">Add company to watchlist</button>
		</div>
	</div>
</template>

<script>

export default{

	data() {
		return {
			selected: (this.watchlists[0]) ? this.watchlists[0].id : null,
			statusMessage: null,
		}
	},
	props: {
	    ticker: {
	    	type: String,
	    	required: true
	    },
	    companyName: {
	    	type: String,
	    	required: true
	    },
	    companyExchange: {
	      type: String,
	      required: true
	   	},
	   	watchlists: {
	   		type: Array,
	   		required: false
	   	}
    },
	//	['ticker', 'companyName', 'companyExchange', 'watchlists'],
	methods: {
		addCompany(){
			if(this.selected && this.ticker && this.companyName && this.companyExchange){
				let itemToAdd = {name: this.companyName, ticker: this.ticker, exchange: this.companyExchange, industry: null};

				axios.post('/watchlist/' + this.selected, itemToAdd).then(response => {
					if(response.status == 200){
						this.flashMessage('Company is allready in the watchlist')
						return;
					}
					if(response.status == 201) this.flashMessage('Successfully added company');
				}).catch(error => {
					this.flashMessage('Failed to add company');
				});
			}else{
				console.log('Missing variables to add company to watchlist');
				this.flashMessage('Failed, reload page and try again');
			}
		},
		flashMessage(message){
			this.statusMessage = message;
         	setTimeout(() => { this.statusMessage = null; }, 4000);
		},
	},


}

</script>