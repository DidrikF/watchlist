<template>
	<div class="box">
		<div>
			<input class="input" v-model="name" placeholder="Name your notification">
			<textarea class="textarea" v-model="description" placeholder="Describe your notification"></textarea>
		</div>
		<div>
			<select class="select" v-model="dataId">
				<option v-for="(dataItem, key) in dataList" v-bind:value="key">{{ dataItem }}</option>
			</select>
			<select class="select" v-model="selectedComparisonOperator">
				<option v-for="comparisonOperator in comparisonOperators" v-bind:value="comparisonOperator">
					{{ comparisonOperator }}
				</option>
			</select>
			<input v-model="dataValue" placeholder="Value">
			<button class="button is-success" @click="addCondition">Add</button>
			<span> {{ statusMessage }} </span>
		</div>

		<ul>
			<li v-for="condition in conditions">
				{{ condition.dataName }} {{ condition.comparisonOperator }} {{ condition.dataValue }}
				<button class="button is-warning" @click="removeCondition(condition)">Delete</button>
			</li>
		</ul>
		<button class="button is-primary" @click="createNotification">Create</button>
		<button class="button is-danger" @click="cancelNotification">Cancel</button>

	</div>
</template>

<script>

export default {
	data () {
		return {
			name: null,
			description: null,
			conditions: [],
			dataList: {"p": "Previous close", "y": "Dividend yield", "d": "Dividend per share", "t8": "1 year target price", "m4": "200 day moving avg", "g3": "Annualizd gain", "s6": "Revenue", "w": "52 week range", "j1": "Market capitalization", "v": "Volume", "e": "EPS", "b4": "Book value", "j4": "EBITDA", "p5": "Price/Sales", "p6": "Price/Book", "r": "P/E ratio", "r5": "PEG ratio", "s7": "Short ratio"}, //Pass in at server side
			dataId: null,
			comparisonOperators: ['=', '<', '>'],
			selectedComparisonOperator: null,
			dataValue: null,
			currentValue: null,
			statusMessage: null,

		}
	},
	props: ['ticker'],
	methods: {
		addCondition() {
			let inConditions = this.conditions.find(element => {
				return element.dataId === this.dataId;
			});
			if(inConditions){
				this.statusMessage = "Condition for '" + this.dataList[this.dataId] + "'' allready exist";
				return;
			}
			if(this.selectedComparisonOperator && this.dataId && this.dataValue) {
				this.conditions.push(
					{dataName: this.dataList[this.dataId], dataId: this.dataId, comparisonOperator: this.selectedComparisonOperator, dataValue: this.dataValue}
					);
				return;
			}
			
			this.statusMessage = 'Missing fields';
		},
		removeCondition(condition) {
			let index = this.conditions.indexOf(condition);
			this.conditions.splice(index, 1);
		},
		cancelNotification() {
			this.name = null;
			this.description = null;
			this.conditions = null;
			this.dataId = null; 
			this.selectedComparisonOperator = null;
			this.dataValue = null;
			this.statusMessage = null;
		},
		createNotification() {
			if(! (this.name && this.conditions.length)){
				this.statusMessage = 'Name or conditions missing';
				return; 
			}
			axios.post('/notification/' + this.ticker, {name: this.name, description: this.description, conditions: this.conditions}).then(response => {
				if(response.status === 201){
					this.statusMessage = 'Succesfully created notification';
					this.cancelNotification();
					return; 
				}
				this.statusMessage = 'Failed to create notification';
			}).catch(error => {
				console.log('Failed to create notification. Error message: ' + error);
			});

		},
		loadComponent() {
			/*
			this.axios.get().then(response => {

			}).catch(error => {

			});
			*/
		}
	},
	mounted() {
		//this.loadComponent();
	}

}

</script>