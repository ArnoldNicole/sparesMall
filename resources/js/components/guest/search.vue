<template>
	<div class="mt-2">		
		<div class="search">
			<input @keyup.enter="submitSearch" type="text" v-model="search.query" placeholder="Search Products">
			<button @click="submitSearch"><i class="fa fa-search"></i></button>
		</div>	

		<Modal v-model="resultsModal" :mask-closable="false" scrollable footer-hide>
			<div slot="header">
				Search Found <code class="text-center">{{results.length}}</code> Products
			</div>
			<div v-if="results.length==0">
				<h4 class="text-center text-danger">
					SEARCH DID NOT YIELD ANY RESULTS
				</h4>
			</div>	
			<div v-else class="mb-2 card"  v-for="(result, r) in results" :key="r" >
				<router-link @click.native="resultsModal=false" :to="'/customer/product/description/'+result.id" >
					<div class="row">
						<div class="col-4">
							<p>Name: {{result.name}}</p>
							<p>Price: {{result.price}}</p>
						</div>
						<div class="col-8">
							<img v-bind:src="'/product_images/'+result.image_url" v-bind:alt="result.name" class="img-fluid w-75">
						</div>
					</div>
				</router-link>
			</div>
					
		</Modal>	
			
	</div>
</template>
<script>
	export default{
		data(){
			return {
				search:{
					query:''
				},				
				results:[],
				resultsModal:false,
			}
		},
		methods:{
			async submitSearch(){
			if (this.search.query.trim()=='') {
				return this.e('Please  enter something in search')
			}									
				const res= await this.callApi('post', '/search/products', this.search)
				if (res.status==200) {
					this.s('Search complete')
					this.results = res.data		
					this.resultsModal=true			
			 }else{
			 	this.e('An Error Keeps Blocking Search')
			 }
			}
		}
	}
</script>