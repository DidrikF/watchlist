<template>
	<div>
		<div>
			<h3 class="title is-4">Not Accepted Users</h3>
				<div>
					<table class="table is-striped">
					  <thead>
					    <tr>
					      <th><abbr title="Name">Name</abbr></th>
					      <th><abbr title="Email">Email</abbr></th>
					      <th><abbr title=""></abbr></th>
					      <th><abbr title=""></abbr></th>
					    </tr>
					  </thead>
					  <tbody>
					  	<tr v-for="user in notAcceptedUsers">
					  		<td>{{ user.name }}</td>
							<td>{{ user.email }}</td>
							<td><button class="button is-warning is-small" type="button" @click="denyUser(user)">Deny</button></td>
							<td><button class="button is-primary is-small" type="button" @click="acceptUser(user)">Accept</button></td>
						</tr>
					  </tbody>
					</table>
				</div>
		</div>
		<div>
			<h3 class="title is-4">Accepted Users</h3>
			<div>
				<table class="table is-striped">
				  <thead>
				    <tr>
				      <th><abbr title="Name">Name</abbr></th>
				      <th><abbr title="Email">Email</abbr></th>
				      <th><abbr title=""></abbr></th>
				      <th><abbr title=""></abbr></th>
				    </tr>
				  </thead>
				  <tbody>
				  	<tr v-for="user in acceptedUsers">
				  		<td>{{ user.name }}</td>
						<td>{{ user.email }}</td>
						<td><button class="button is-warning is-small" type="button" @click="banUser(user)">Ban</button></td>
						<!-- Only if admin is prime boss -->
						<td><button class="button is-primary is-small" type="button" @click="makeAdmin(user)">Give Admin Rights</button></td>
					</tr>
				  </tbody>
				</table>
			</div>
		</div>
		<div>
			<!-- Only if admin is prime boss -->
			<h3 class="title is-4">Site Admins</h3>

			<div>
				<table class="table is-striped">
				  <thead>
				    <tr>
				      <th><abbr title="Name">Name</abbr></th>
				      <th><abbr title="Email">Email</abbr></th>
				      <th><abbr title=""></abbr></th>
				      <th><abbr title=""></abbr></th>
				    </tr>
				  </thead>
				  <tbody>
				  	<tr v-for="user in admins">
				  		<td>{{ user.name }}</td>
						<td>{{ user.email }}</td>
						<td><button class="button is-warning is-small" type="button" @click="banUser(user)">Ban</button></td>
						<td><button class="button is-danger is-small" type="button" @click="removeAdmin(user)">Remove Admin Rights</button></td>
					</tr>
				  </tbody>
				</table>
			</div>
		</div>

	</div>

</template>

<script>
	export default {
		name: 'admin-panel',
		data () {
			return {
				users: this.usersProp,
			}
		},
		props: [
			'usersProp'
		],
		computed: { //filters
			acceptedUsers(){
				let users = JSON.parse(JSON.stringify(this.users));
				return users.filter(user => {
					return user.accepted === 1 && user.admin === 0;
				});	
			},
			notAcceptedUsers(){
				let users = JSON.parse(JSON.stringify(this.users));
				return users.filter(user => {
					return user.accepted === 0;
				});	
			},
			admins(){
				let users = JSON.parse(JSON.stringify(this.users));
				return users.filter(user => {
					return user.admin === 1;
				});
			}
		},
		/*
			Route::put('/admin/accept/{user}', 'AdminController@acceptUser');
			Route::put('/admin/ban/{user}', 'AdminController@banUser');

			Route::put('/admin/makeadmin/{user}', 'AdminController@makeAdmin');
			Route::put('/admin/removeadmin/{user}', 'AdminController@removeAdmin');
		*/
		methods: {
			acceptUser(user){
				axios.put('/admin/accept/' + user.id).then(response => {
					if(response.status === 200){
						this.users = response.data;
					}
				}).catch((error) => {
					console.log('Filed to accept user: ' + error);
				});
			},
			denyUser(user){
				axios.delete('/admin/deny/' + user.id).then(response => {
					if(response.status === 200){
						this.users = response.data;
					}
				}).catch((error) => {
					console.log('Filed to deny user: ' + error);
				});
			},
			banUser(user){
				axios.put('/admin/ban/' + user.id).then(response => {
					if(response.status === 200){
						this.users = response.data;
					}
				}).catch((error) => {
					console.log('Filed to ban user: ' + error);
				});
			},

			//Only if admin is prime boss
			makeAdmin(user){
				axios.put('/admin/makeadmin/' + user.id).then(response => {
					if(response.status === 200){
						this.users = response.data;
					}
				}).catch((error) => {
					console.log('Filed to make user admin: ' + error);
				});
			},
			removeAdmin(user){
				axios.put('/admin/removeadmin/' + user.id).then(response => {
					if(response.status === 200){
						this.users = response.data;
					}
				}).catch((error) => {
					console.log('Filed to remove admin privileges from user: ' + error);
				});
			},
		},

		ready () {

		}

	}
</script>