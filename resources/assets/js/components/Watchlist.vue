<template>

	<div class="watchlist-container">
		<h2>{{ title }}</h2>
		<p>{{ description }}</p>
		<ul>
			<watchlist-item 
				v-for="item in items" 
				v-bind:item="item"
				v-on:removeCompany="removeCompany" 
			></watchlist-item> 

			<!-- kebab case might be nessecary, v-bind makes the parent dynamically send data down to the child when data changes, need to use v-bind to make vue evaluate the score value as a js expression (translating it to a number), state of data can only be send down in the compenent stack, do not mutate props in the childs as the state is linked...(line truncated)... -->
		

		</ul>

	</div>
	
</template>


<script>

var watchlistItem = {
	template: `
		<li>
			{{ item.companyName }} - {{ item.ticker }} - {{ item.exchange }} - {{ item.scores }}
			<a :href="item.companyLink">Go to company page</a>
			<button type="button" @click="emitRemoveCompany">Remove</button>
		</li>
	`
	,props: ['item']

	//props: ['ticker', 'companyName', 'exchange', 'score', 'companyLink'] //data given to the child component is a list item
	,
	methods: {
		emitRemoveCompany(){
			this.$emit('removeCompany'); //emit event to parent
		}
	}


}


export default {
	data () {
		return {
			title: "Watchlist title", //set initial value via props from blade
			description: "Watchlist description, lorim ipsum lorem fatuin horm lezolatoulium",
			items: [ 
				{ticker: "STO", companyName: "Statoil ASA", exchange:"NYSE", score: 34, companyLink: "/company/STO"}, 
				{ticker: "AAPL", companyName: "Apple Inc", exchange:"NYSE", score: 24, companyLink: "/company/AAPL"}, 
				{ticker: "GOOGL", companyName: "Google", exchange:"NYSE", score: 35, companyLink: "/company/GOOGL"} 
			]
		}
	},

	methods: {
		getWathchlist(){
			//get watchlist data, the watchlist components are inserted at the server level by blade.

		},
		addCompany(){

		},
		removeCompany(){
			//I need to get the ticker of the child componen that triggered the event to remove itself
		}
	},
	components: {
		'watchlist-item': watchlistItem
	} 
}


//https://vuejs.org/v2/guide/components.html#Content-Distribution-with-Slots
</script>

<style>
	.watchlist-container{
		padding: 15px;
		border: 2px solid black;
	}
</style>


