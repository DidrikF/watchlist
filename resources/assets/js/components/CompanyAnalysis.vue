<template> <!-- This is an extended Vue instance -->
	<div class="box">
		<!-- Use v-bind:xxxx - bind a data model to a html element property, do not use {{ }} -->
		<h3 class="title">Your Analysis</h3>
		<form action="#" method="post"> <!-- But i end up preventing this action... --> 
			<div class="columns" >
				<div class="column is-2">
					<label class="label has-text-centered" style="margin-top: 5px;" for="financialScore">Financial Score: </label>
				</div>
				<div class="column is-1">
					<input class="input" v-on:keyup="dynamicSaveAnalysis" name="financialScore" id="financialScore" placeholder="1-10" v-model="financialScore" type="number" min="0" max="10">
				</div>

				<div class="column is-2">
					<label class="label has-text-centered" style="margin-top: 5px;" for="cfScore">Cash Flow Score: </label>
				</div>
				<div class="column is-1">
					<input class="input" v-on:keyup="dynamicSaveAnalysis" name="cfScore" id="cfScore" placeholder="1-10" v-model="cfScore" type="number" min="0" max="10">
				</div>

				<div class="column is-2">
					<label class="label has-text-centered" style="margin-top: 5px;" for="growthScore">Growth potential: </label>
				</div>
				<div class="column is-1">
					<input class="input" v-on:keyup="dynamicSaveAnalysis" name="growthScore" id="growthScore" placeholder="1-10" v-model="growthScore" type="number" min="0" max="10">
				</div>

				<div class="column is-2">
					<label class="label has-text-centered" style="margin-top: 5px;" for="riskScore">Risk Score: </label>
				</div>
				<div class="column is-1">
					<input class="input" v-on:keyup="dynamicSaveAnalysis" name="riskScore" id="riskScore" placeholder="1-10" v-model="riskScore" type="number" min="0" max="10">
				</div>
			</div>
			<div>
				<label class="label" for="analysis">Other thoughts</label>
				<textarea class="textarea" v-on:keyup="dynamicSaveAnalysis" name="analysis" id="analysis" placeholder="Write your thoughts..." v-model="textAnalysis"></textarea>
				<div style="margin: 10px 0;">
					<button class="button is-primary" style="margin: 0 20px;" type="submit" @click.prevent="saveAnalysis">Save</button>
					<button class="button is-danger" type="button" @click="deleteAnalysis">Delete</button>
					<span class="tag is-info is-pulled-right" style="margin: 10px 0" v-if="statusMessage">{{ statusMessage }}</span>
				</div>
			</div>
		</form>		
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
				statusMessage: "",
				timeout: null,

				user: window.watchlist.user,
				exists: false
			}			
		},

		props: ['ticker'],
		methods: {
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

					if(this.timeout){
						clearTimeout(this.timeout);
						//this.timeout = null;
					}
					this.timeout = setTimeout(() => {
						if(this.exists){ //PUT
							axios.put('/analysis/' + this.ticker, data).then(response => {
								this.exists = true;
								this.flashMessage("Updated");
							}).catch(error => {
								this.flashMessage("Update failed");
								console.log('Could not update/put analysis'); 
							});
							return;
						}
						axios.post('/analysis/' + this.ticker, data).then(response => {
							this.exists = true;
							this.flashMessage("Saved");
						}).catch(error => {
							this.flashMessage("Save failed");
							console.log('Could not post analysis'); 
						});
					}, 2000);
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
					if(this.exists){ //PUT
						axios.put('/analysis/' + this.ticker, data).then(response => {
							this.exists = true;
							this.flashMessage("Updated");
						}).catch(error => {
							this.flashMessage("Update failed");
							console.log('Could not update/put analysis'); 
						});
						return;
					}
					axios.post('/analysis/' + this.ticker, data).then(response => {
						this.exists = true;
						this.flashMessage("Saved");
					}).catch(error => {
						this.flashMessage("Save failed");
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
						this.flashMessage("Delete successful");
						this.exists = false;
					}).catch(error => {
						this.flashMessage("Delete failed");
						console.log('Could not delete analysis');
					});
				}
			},
			//____HELPER FUNCTIONS____________________________________________________________
			flashMessage(message){
				this.statusMessage = message;
         		setTimeout(() => { this.statusMessage = null }, 4000);
			},

		},
		//There are also other hooks which will be called at different stages of the instance’s lifecycle, for example mounted, updated, and destroyed.
		mounted() {
			this.getAnalysis();
		}

	}

</script>