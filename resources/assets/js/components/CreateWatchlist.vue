<template>
	<div>
		<h2>Create New Watchlist</h2>
		<label for="title">Title:</label>
		<input name="title" v-model="title"></input>
		<label>Description:</label>
		<textarea v-model="description"></textarea>
		<button type="button" @click="createWatchlist"></button>
	</div>
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
			this.title = null;
			this.description = null; //no consideration to failure
			this.$emit('createWatchlist', 'title', 'description');
		}
	}

}
</script>