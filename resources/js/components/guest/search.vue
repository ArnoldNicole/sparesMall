<template>
	<div>		
		<div class="search">
			<input type="text" v-model="search.query" placeholder="Search Products">
			<button @click="submitSearch"><i class="fa fa-search"></i></button>		
		</div>
		<Modal v-model="resultsModal" title="Search Results" :mask-closable="false" :closable="true" :width="80" :scrollable="true">
			<div class="card">
				<div class="card-body mb-2" v-for="(result, r) in results" :key="result.id">
					<router-link :to="'/customer/product/description/'+result.id">
						<div class="row">
							<div class="col-4">
								<p>Name: {{result.name}}</p>
								<p>Price: {{result.price}}</p>
							</div>
							<div class="col-8">
								<img v-bind:src="'/product_images/'+result.image_url" v-bind:alt="result.name">
							</div>
						</div>
					</router-link>
				</div>
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
				resultsModal:false,
				results:[],
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