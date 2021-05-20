<template>
	<div class="row justify-content-center  pt-1">
		<div class="col-md-2 pb-2 header-slider-item" v-for="(item, i) in items" :key="i">		  
		  	<img v-bind:src="'/product_images/'+item.image_url" alt="Slider Image" class="img-fluid img-thumbnail" />
		  	<div class="header-slider-caption text-center">
		  		<p>{{item.name}}</p>
		  		<p>{{item.price}}</p>
		  		    <router-link class="btn" :to="'/customer/product/description/'+item.id"><i class="fa fa-shopping-cart"></i>MORE
		  		    </router-link>
		  	</div>		  		  
		</div>
	</div>
</template>
<script>
	export default{
		data(){
			return {
				items:[]
			}
		},
		async created(){			
			const res = await this.callApi('get','/products')
			if (res.status == 200) {
				this.items = res.data.data
				
			}else{
				this.e('Error Fetching Products')
			}
		}
	}
</script>