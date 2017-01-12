<template> <!-- This is an extended Vue instance -->
	<div>
		<!-- Use v-bind:xxxx - bind a data model to a html element property, do not use {{ }} -->
		<form class="analysis-form" action="#" method="post"> <!-- But i end up preventing this action... --> 
			<div class="form-group">
				<label for="financialScore">Financial Score: </label>
				<input v-on:keyup="dynamicSaveAnalysis" type="text" name="financialScore" id="financialScore" placeholder="1-10" v-model="financialScore" > <!-- value="{{ financialScore }}" (wait a minute)is not nececary, v-model does it all -->
			</div>
			<div class="form-group">
				<label for="cfScore">Cash Flow Score: </label>
				<input v-on:keyup="dynamicSaveAnalysis" type="text" name="cfScore" id="cfScore" placeholder="1-10" v-model="cfScore">
			</div>
			<div class="form-group">
				<label for="growthScore">Growth potential: </label>
				<input v-on:keyup="dynamicSaveAnalysis" type="text" name="growthScore" id="growthScore" placeholder="1-10" v-model="growthScore">
			</div>
			<div class="form-group">
				<label for="riskScore">Risk Score: </label>
				<input v-on:keyup="dynamicSaveAnalysis" type="text" name="riskScore" id="riskScore" placeholder="1-10" v-model="riskScore">
			</div>
			<div class="form-group">
				<label for="analysis">Your analysis</label>
				<br>
				<textarea v-on:keyup="dynamicSaveAnalysis" name="analysis" id="analysis" placeholder="Write your thoughts..." v-model="textAnalysis"></textarea>

			</div>
			<!-- or: v-on:click="saveAnalysis" (directive:argument) v-on = @-->
			<button type="submit" @click.prevent="saveAnalysis">Save</button>
		</form>
		<button type="button" @click="deleteAnalysis">Delete</button>
		<button type="button" @click="getAnalysis">Get analysis (remove later)</button>
	</div>
</template>

<script>
	export default {
		data () { //must be a function, and declare all data models here, as we do not want each instance of the component to reference the same data.
			return {
				//only these proxied properties are reactive
				financialScore: null,
				cfScore: null,
				growthScore: null,
				riskScore: null,
				textAnalysis: null,
				prevFinancialScore: "",
				prevCfScore: "",
				prevGrowthScore: "",
				prevRiskScore: "",
				prevTextAnalysis: "",
				statusMessage: null,

				user: window.watchlist.user,
				exists: false
			}			
		},

		props: ['ticker']

		,methods: {
			getAnalysis () {
				console.log('getAnalysis running');
				if(this.user.authenticated){ //can easily be manipulated... need auth on server side
					console.log('all good to run Ajax');
					axios.get('/analysis/' + this.ticker).then((response) => {
						console.log(response);
						if(response.status != 200) {
							this.exists = false;
							return;
						}
						this.exists = true; //I rely on this method getting execute
						this.financialScore = response.data.financialScore;
						this.cfScore = response.data.cfScore;
						this.growthScore = response.data.growthScore;
						this.riskScore = response.data.riskScore;
						this.textAnalysis = response.data.textAnalysis;
					}).catch((error) => {
						console.log('No analysis found associated with given user and ticker');
					});
				}
			},
			dynamicSaveAnalysis(){
				if( this.prevFinancialScore != this.financialScore || this.prevCfScore != this.cfScore || 
					this.prevGrowthScore != this.growthScore || this.prevRiskScore != this.riskScore || 
					this.prevTextAnalysis != this.textAnalysis)
				{
					this.prevFinancialScore = this.financialScore
					this.prevCfScore = this.cfScore
					this.prevGrowthScore = this.growthScore
					this.prevRiskScore = this.riskScore
					this.prevTextAnalysis = this.textAnalysis

					let data = {
						financialScore: this.financialScore,
						cfScore: this.cfScore,
						growthScore: this.growthScore,
						riskScore: this.riskScore,
						textAnalysis: this.textAnalysis
					};

					if(timeout) clearTimeout(timeout);
					timeout = setTimeout(() => {
						if(this.exists){
							axios.put('/analysis/' + this.ticker, data).then(response => {
								this.exists = true;
								flashMessage(this.statusMessage, "Updated");
							}).catch(error => {
								flashMessage(this.statusMessage, "Update failed");
								console.log('Could not update/put analysis'); 
							});
							return;
						}
						axios.post('/analysis/' + this.ticker, data).then(response => {
							this.exists = true;
							flashMessage(this.statusMessage, "Saved");
						}).catch(error => {
							flashMessage(this.statusMessage, "Save failed");
							console.log('Could not post analysis'); 
						});
					}, 4000);
				}
			},
			saveAnalysis() {
				if(this.user.authenticated){
					let data = {
						financialScore: this.financialScore,
						cfScore: this.cfScore,
						growthScore: this.growthScore,
						riskScore: this.riskScore,
						textAnalysis: this.textAnalysis
					};
					console.log(this.exists);
					if(this.exists){
						axios.put('/analysis/' + this.ticker, data).then(response => {
							this.exists = true;
							flashMessage(this.statusMessage, "Updated");
						}).catch(error => {
							flashMessage(this.statusMessage, "Update failed");
							console.log('Could not update/put analysis'); 
						});
						return;
					}
					axios.post('/analysis/' + this.ticker, data).then(response => {
						this.exists = true;
						flashMessage(this.statusMessage, "Saved");
					}).catch(error => {
						flashMessage(this.statusMessage, "Save failed");
						console.log('Could not post analysis'); 
					});
				}
			},

			deleteAnalysis() {
				if(this.user.authenticated){
					this.financialScore = null;
					this.cfScore = null;
					this.growthScore = null;
					this.riskScore = null;
					this.textAnalysis = null;
					axios.delete('/analysis/' + this.ticker).then((response) => {
						flashMessage(this.statusMessage, "Delete successful");
						this.exists = false;
					}).catch(error => {
						flashMessage(this.statusMessage, "Delete failed");
						console.log('Could not delete analysis');
					});
					
				}
			},
			//____HELPER FUNCTIONS____________________________________________________________
			flashMessage(variable, message){
				variable = message;
         		setTimeout(() => { variable = null }, 4000);
			},

		},
		//There are also other hooks which will be called at different stages of the instance’s lifecycle, for example mounted, updated, and destroyed.
		mounted() {
			this.getAnalysis();
		}

	}

</script>