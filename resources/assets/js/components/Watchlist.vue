<template>

	<div>
		<h2></h2>
		<p></p>
		<ul>
			<watchlist-item v-on:removeCompany="removeCompany" v-for="item in items" :ticker="{{ ticker }}" company-name="{{ companyName }}" :score="{{ score }}"></watchlist-item> <!-- kebab case might be nessecary, v-bind makes the parent dynamically send data down to the child when data changes, need to use v-bind to make vue evaluate the score value as a js expression (translating it to a number), state of data can only be send down in the compenent stack, do not mutate propes in the childs as the state is linked to the parent and we want the state to be the same (use data or computed properties to mutate state of the shared prop) -->
		</ul>

	</div>
	
</template>


<script>

var watchlistItem = {
	template: `
		<li>
			{{ companyName }} - {{ ticker }} - {{ exchange }}
			<a href="{{ companyLink }}">Go to company page</a>
			<button type="button" @click="removeCompany">Remove</button>
		</li>
	`,
	props: ['ticker', 'companyName', 'exchange', 'score', 'companyLink']
	,
	methods: {
		removeCompany(){
			this.$emit('removeCompany'); //emit event to parent
		}
	}


}


export default {
	data () {
		return {
			title: null,
			description: null,
			items: null, //{ 1: {ticker: "STO", companyName: "Statoil ASA", exchange:"NYSE", scode: 34, companyLink: "/company/{ticker}"}, 2: {}, 3: {} }
		}
	},

	methods: {
		addCompany(){

		},
		removeCompany(){

		}
	},
	components: {
		'watchlist-item': watchlistItem
	} 
}


//https://vuejs.org/v2/guide/components.html#Content-Distribution-with-Slots
</script>