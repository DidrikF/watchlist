<template>
	<div class="box">
		<h3 class="title is-3">Create New Watchlist</h3>
		<div>
			<label class="label">Title</label>
			<p class="control">
			  <input class="input" type="text" placeholder="Name you new watchlist..." v-model="title">
			</p>
			<span class="help is-danger" v-if="errors.name">{{ errors.name[0] }}</span>
		</div>
		<div>
			<label class="label">Description</label>
			<p class="control">
			  <textarea class="textarea" placeholder="Describe the intention of your new watchlist..." v-model="description"></textarea>
			</p>
			<span class="help is-danger" v-if="errors.description">{{ errors.description[0] }}</span>
		</div>
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
			errors: this.errorsProp || [],
		}
	}, 
	props: ['errorsProp'],
	methods: {
		createWatchlist(){
			console.log('Emitting create watchlist event!');
			if((this.title === '' || this.title === null) || (this.description === '' || this.description === null)){
				this.errors = {name: ['Title and description are required fields']};
				return;
			}
			this.$emit('createWatchlist', this.title, this.description);
			this.title = null;
			this.description = null;
			this.errors = [];
		},
		cancel(){
			this.title = null;
			this.description = null;
			this.errors = [];
		}
	}

}
</script>