<!-- Just notes and expamples for reference -->

<template> <!-- This is an extended Vue instance -->
	<div>
		<!-- Use v-bind:xxxx - bind a data model to a html element property, do not use {{ }} -->
		<p v-bind:title="title">Hello there: {{ ticker }}</p>

		<!-- or: v-on:click="saveAnalysis" (directive:argument) v-on = @-->
		<button type="submit" @click.prevent="saveAnalysis">Save</button>

	<!--	
		//This will only render once, also msg will not be updated when changed 
		<span v-once>This will never change: {{ msg }}</span> 
		
		//To render real HTML to the dom, not raw text, which is the case when referencing a data model
		<div v-html="rawHtml"></div>

		//You can use the full power of javascript within {{ message.split("").sort().join("") }}
		//One restriction is that each binding can only contain one single expression. {{ a = 5 }} is not an 
		  an expression, its a statement. There are also limited access to global variables and objects. Date and Math are whitelisted...

		//Directives:
		A directive’s job is to reactively apply side effects to the DOM when the value of its expression changes. 
		v-bind directive is used to reactively update an HTML attribute -> <a v-bind:href="url"></a>
		Another example is the v-on directive, which listens to DOM events -> <a v-on:click="doSomething">
		//Modifiers:
		Modifiers are special postfixes denoted by a dot, which indicate that a directive should be bound in some special way. (.prevent is such a modifier)
		//Filters: you can do stuff like:  {{ message | capitalize }}
			- You yourself have to write the filter function, its added as an object property when instantansiating the vue instance. Sice it is a function, you can pass it arguments as well.

		//Short hand:
			- <a :href="url"></a>			v-bind
			- <a @click="doSomething"></a>	v-on
		//Computed properties
		When you want any logic associated with how you render a data model, use computed properties. These getter functions will make vue aware of any data model it depends upon and rerender the computed property when something it depends upon changes. (AN EXAMPLE IS BELOW)
			- You can actually achieve the same functionality by declaring the computed function as a method, but only in the case of the computed properti will the function only run when data it depends upon have changed. The result from the previour execution is chached and the function only runs if on of its dependencies have changed. The caching feature is very usefull when you have a lot of cumputation going on within it, and the result is used more than once in your page. If you dont want caching, use a method instead.
			- Computed properties are by default getter-only, but you can also provide a setter when you need it

		//Watcher: another way to set up reactivity to changing data models, but it watches only the one peace of data that you tell it to. A computed property will look at all its dependencies and rerun whenever ONE of them changes.
		//Other libs: you can simply pull in lowdash and other libraries and use them within your vue instance, Vue stays small by not reinventing the wheel and let you pull in what you know and love.
		//Class and style bindings:
		  Vue provides special enhancements when v-bind is used with class and style
		//Binding dynamic properties to the Vue instance, this can be done with v-bind. You can make a bound property take on the value of another property based on dom state.
			- A small example: <input type="radio" v-model="pick" v-bind:value="a">, vm.pick === vm.a


	-->

</template>

<script>
	export default {
		data () {

		},
  		computed: {
	   		// a computed getter, its a getter function for the message data model

	    	reversedMessage: function () {
	     		// `this` points to the vm instance
	     		return this.message.split('').reverse().join('')
	    	}
	    },
		props: {

		},

		methods: {

		},

		ready () {

		}

	}


</script>