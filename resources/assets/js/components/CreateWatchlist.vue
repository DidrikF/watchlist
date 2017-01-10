<template>
	<h2>Create New Watchlist</h2>
	<label>Title:</label>
	<input v-model="title"></input>
	<label>Description:</label>
	<textarea v-model="description"></textarea>
	<button type="button" @click="createWatchlist"></button>
</template>

<script>

export default{

	data(){
		return {
			title: null, 
			description: null,
		}
	}, 
	methods: {
		createWatchlist(){
			//send ajax or tell parent, 
		}
	}

}

</script>