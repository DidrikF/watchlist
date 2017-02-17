<template>
	<div>
		<div class="box">
			<h3 class="title is-4">Active Notifications</h3>
			<div v-if="!activeNotifications.length" style="margin-bottom: 2em;">
				<p>No active notifications for this company. You can create a notification by clicking the button bellow.</p>
			</div>
			<div v-if="activeNotifications.length">
				<table>
					<thead>
						<th><abbr title="Name">Name</abbr></th>
						<th><abbr title="Description">Description</abbr></th>
						<th><abbr title="Status">Status</abbr></th>
						<th><abbr></abbr></th>
					</thead>
					<tbody>
						<tr v-for="notification in activeNotifications">
							<td><a @click="prepareEditNotification(notification)" title="Edit">{{ notification.name }}</a></td>
							<td>{{ notification.description }}</td>
							<td>{{ notification.triggered ? 'Triggered' : 'Not triggered' }}</td>
							<td><button class="button is-danger is-small" @click="deleteNotification(notification)">Delete</button></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div>
				<button class="button is-primary" @click="openNotificationMaker">Create New Notification</button>
			</div>
		</div>
		<div class="box" v-if="showNotificationMaker">
			<div>
				<h3 v-if="!enableEditNotification" class="title is-4 is-inline">Create New Notification</h3>
				<h3 v-if="enableEditNotification" class="title is-4 is-inline">Edit Notification</h3>
				<button class="button is-danger pull-right" @click="closeNotificationMaker">X</button>
			</div>
			<div class="columns"> <!-- If no active notifications or ... -->
				<div class="column is-third">
					<label class="label" for="name">Name</label>
					<input name="name" class="input" v-model="name" placeholder="Name your notification">
					<span class="help is-danger" v-if="validationErrors.name">{{ validationErrors.name[0] }}</span>
					<label class="label" for="description">Description</label>
					<textarea name="description" class="textarea" v-model="description" placeholder="Describe your notification"></textarea>
					<span class="help is-danger" v-if="validationErrors.description">{{ validationErrors.description[0] }}</span>
				</div>
				<div class="column">
					<div style="min-height: 10em;">
						<h3 class="title is-4">Conditions</h3>
						<span class="help is-danger" v-if="validationErrors.conditions">{{ validationErrors.conditions[0] }}</span>
						<table>
							<thead>
								<th><abbr></abbr></th>
								<th><abbr></abbr></th>
								<th><abbr></abbr></th>
							</thead>
							<tbody>
								<tr v-for="condition in conditions">
									<td>{{ condition.dataName }} {{ condition.comparisonOperator }} {{ condition.dataValue }}</td>
									<td v-if="validationErrors[condition.dataId]"><span class="help is-danger">{{ validationErrors[condition.dataId][0] }}</span></td>
									<td v-else></td>
									<td><button class="button is-warning is-small" @click="removeCondition(condition)">Delete</button></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div style="min-height: 1em;">
						<span class="help is-danger"> {{ statusMessage }} </span>
						<span class="help"> {{ statusMessage }} </span>
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
						<input class="input is-inline" style="width:10em;" v-model="dataValue" placeholder="Value" >
						<button class="button is-success pull-right" @click="addCondition">Add</button>
					</div>
					<div style="bottom: 0px; margin-top:1em;">
						<span class="tag is-info" style="margin: 10px 0" v-if="successMessage">{{ successMessage }}</span>
						<button class="button is-danger" style="float: right; margin-left: 2em;" @click="resetNotification">Reset</button>
						
						<button v-if="!enableEditNotification" class="button is-primary" style="float: right;" @click="createNotification">Create</button>

						<button v-if="enableEditNotification" class="button is-primary" @click="editNotification" style="float: right; margin-left: 2em;">Update</button>

						
					</div>
				</div>
			</div>	
		</div>
		
	</div>
</template>

<script>

export default {
	data () {
		return {
			notificationId: null,
			name: null,
			description: null,
			conditions: [],
			dataList: {"p": "Previous close", "y": "Dividend yield", "d": "Dividend per share", "t8": "1 year target price", "m4": "200 day moving avg", "g3": "Annualizd gain", "s6": "Revenue", "w": "52 week range", "j1": "Market capitalization", "v": "Volume", "e": "EPS", "b4": "Book value", "j4": "EBITDA", "p5": "Price/Sales", "p6": "Price/Book", "r": "P/E ratio", "r5": "PEG ratio", "s7": "Short ratio"}, //Pass in at server side
			dataId: null,
			comparisonOperators: ['<', '>'],
			selectedComparisonOperator: null,
			dataValue: null,
			currentValue: null,
			statusMessage: null,
			activeNotifications: this.propActiveNotifications, //
			showNotificationMaker: false,
			validationErrors: [],
			enableEditNotification: false,
			successMessage: null,

		}
	},
	props: ['ticker', 'propActiveNotifications', 'companyData'], //Company data not working
	computed: {
		/*prettyConditions() {
			let conditionsCopy = {copy: this.conditions}; //copy and return pointer to the new array
			return conditionsCopy.copy.map(element => {
				if(element.comparisonOperator === '<') element.comparisonOperator = 'less than';
				else if(element.comparisonOperator === '>') element.comparisonOperator = 'greater than';
				return element;
			});
		}*/
	},
	methods: {
		addCondition() {
			this.statusMessage = null;
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
			this.statusMessage = null;
			let index = this.conditions.indexOf(condition);
			delete this.validationErrors[condition.dataId];
			
			this.conditions.splice(index, 1);
		},
		resetNotification() {
			this.notificationId = null;
			this.name = null;
			this.description = null;
			this.conditions = [];
			this.dataId = null; 
			this.selectedComparisonOperator = null;
			this.dataValue = null;
			this.statusMessage = null;
			this.enableEditNotification = false; 
			this.showNotificationMaker = true;
		},
		closeNotificationMaker(){
			this.enableEditNotification = false;
			this.showNotificationMaker = false;
			this.resetNotification();
		},
		openNotificationMaker(){
			this.enableEditNotification = false; 
			this.showNotificationMaker = true;
			this.resetNotification();
		},
		createNotification() {
			this.validationErrors = [];
			this.statusMessage = null;
			axios.post('/notification/' + this.ticker, {name: this.name, description: this.description, conditions: this.conditions}, {validateStatus: function(status) {
					return status < 500; //reject only if status is equal to or above 500
			}}).then(response => {
				if(response.status === 201){
					this.activeNotifications.push(response.data.notification); //NEED TO CHECK THIS

					this.flashSuccessMessage('Notification Created');
					return; 
				}
				if(response.status === 422){
					this.validationErrors = response.data || [];
					if(this.validationErrors['general']) this.statusMessage = this.validationErrors['general'][0];
					return;
				}
				this.statusMessage = 'Failed to create notification';
			}).catch((error) => {
				console.log('Failed to create notification. Error message: ' + error);
			});

		},
		prepareEditNotification(notification){
			this.resetNotification()
			this.enableEditNotification = true;
			this.showNotificationMaker = true;
			this.notificationId = JSON.parse(JSON.stringify(notification.id));
			//I need to copy the data over, not create a reference!
			this.name = JSON.parse(JSON.stringify(notification.name));
			this.description = JSON.parse(JSON.stringify(notification.description));
			this.conditions = JSON.parse(JSON.stringify(notification.conditions));
		},
		editNotification() {
			this.validationErrors = [];
			this.statusMessage = null;
			axios.put('/notification/' + this.notificationId + '/' + this.ticker, {name: this.name, description: this.description, conditions: this.conditions}, {validateStatus: function(status) {
					return status < 500; //reject only if status is equal to or above 500
			}}).then(response => {
				if(response.status === 200){
					let removeNotification = this.activeNotifications.find(element => {
						return element.id = this.notificationId;
					});
					let removeIndex = this.activeNotifications.indexOf(removeNotification);
					this.activeNotifications.splice(removeIndex, 1, response.data.notification)

					this.flashSuccessMessage('Notification Updated');
					return; 
				}
				if(response.status === 422){
					this.validationErrors = response.data;
					if(this.validationErrors['general']) this.statusMessage = this.validationErrors['general'][0];
					return;
				}
				this.statusMessage = 'Failed to update notification';
			}).catch((error) => {
				console.log('Failed to create notification. Error message: ' + error);
			});
		},
		deleteNotification(notification) {
			axios.delete('/notification/' + notification.id).then(response => {
				if(response.status === 200){
					let indexOfnotificaitonToRemove = this.activeNotifications.indexOf(notification);
					console.log(indexOfnotificaitonToRemove);
					this.activeNotifications.splice(indexOfnotificaitonToRemove, 1);
					return;	
				}
				this.statusMessage = 'Failed to delete notification';
			}).catch(error => {
				console.log('Failed to delete notidication: Error message: ' + error);
				this.statusMessage = 'Failed to delete notification';
			});
		},
		//____HELPER FUNCTIONS____________________________________________________________
		flashSuccessMessage(message){
			this.successMessage = message;
     		setTimeout(() => { this.successMessage = null }, 4000);
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