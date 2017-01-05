<template> <!-- This is an extended Vue instance -->
	<div>
		<!-- Use v-bind:xxxx - bind a data model to a html element property, do not use {{ }} -->
		

		<form class="analysis-form" action="/company/xxticker/analysis" method="post"> <!-- But i end up preventing this action... --> 
			<p>Hello there: {{ ticker }}</p>
			<div class="form-group">
				<label for="financialScore">Financial Score: </label>
				<input type="text" name="financialScore" id="financialScore" placeholder="1-10" v-model="financialScore" > <!-- value="{{ financialScore }}" (wait a minute)is not nececary, v-model does it all -->
			</div>
			<div class="form-group">
				<label for="cfScore">Cash Flow Score: </label>
				<input type="text" name="cfScore" id="cfScore" placeholder="1-10" v-model="cfScore">
			</div>
			<div class="form-group">
				<label for="growthScore">Growth potential: </label>
				<input type="text" name="growthScore" id="growthScore" placeholder="1-10" v-model="growthScore">
			</div>
			<div class="form-group">
				<label for="riskScore">Risk Score: </label>
				<input type="text" name="riskScore" id="riskScore" placeholder="1-10" v-model="riskScore">
			</div>
			<div class="form-group">
				<label for="analysis">Your analysis</label>
				<br>
				<textarea onchange="javascript:textarea_height(analysis, 15);" name="analysis" id="analysis" placeholder="Write your thoughts..." v-model="textAnalysis"></textarea>

			</div>
			<!-- or: v-on:click="saveAnalysis" (directive:argument) v-on = @-->
			<button type="submit" @click.prevent="saveAnalysis">Save</button>
		</form>
		<div>{{ financialScore }}</div>
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
			}			
		},

		props: ['ticker']

		,methods: {
			getAnalysis () {
				this.$http.get('/company/' + this.ticker + '/analysis').then((response) => {
					this.financialScore = response.json().data.financialScore;
					this.cfScore = response.json().data.cfScore;
					this.growthScore = response.json().data.growthScore;
					this.riskScore = response.json().data.riskScore;
					this.textAnalysis = response.json().data.textAnalysis;
					//this.ticker = response.json().data.ticker;
				});
			},
			saveAnalysis() {
				console.log(financialScore.value);

				this.$http.post('/analysis/' + this.ticker + '/save', {
					financialScore: financialScore,
					cfScore: cfScore,
					growthScore: growthScore,
					riskScore: riskScore,
					textAnalysis: textAnalysis
				});
			},
			deleteAnalysis() {
				this.$http.delete('/analysis/' + this.ticker + '/delete', {
					
				});
			}
		},

		//There are also other hooks which will be called at different stages of the instance’s lifecycle, for example mounted, updated, and destroyed.
		ready () {
			getAnalysis(); //or render on the server side...
		}

	}

</script>