<template>
	<div class="box">
		<h3 class="title is-3">Create New Watchlist</h3>
		<label class="label">Title</label>
		<p class="control">
		  <input class="input" type="text" placeholder="Name you new watchlist..." v-model="title">
		</p>
		<label class="label">Description</label>
		<p class="control">
		  <textarea class="textarea" placeholder="Describe the intention of your new watchlist..." v-model="description"></textarea>
		</p>
		<div class="control is-grouped">
		  <p class="control">
    		<button class="button is-primary" @click="createWatchlist">Create</button>
  	      </p>
		  <p class="control">
		    <button class="button is-link" @click="cancel">Cancel</button>
		  </p>
		</div>

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
			console.log('Emitting create watchlist event!');
			this.$emit('createWatchlist', this.title, this.description);
			this.title = null;
			this.description = null; //no consideration to failure
		},
		cancel(){
			this.title = null;
			this.description = null;
		}
	}

}
</script>