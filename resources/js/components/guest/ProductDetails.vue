<template>
	<div class="container-fluid">
		<div class="row justify-content-center" v-if="!missingProduct">
			<div class="col-md-4">
			    <div class="product-content">
			        <div class="title"><h2>{{data.name}}</h2></div>
			        <div class="price m-3">
			            <h4>Price:</h4>
			            <p><span>KSH {{data.price}}</span></p>
			        </div>
			        <div class="p-size m-3">
			            <h4>Size:</h4>
			            <div class="btn-group btn-group-sm">
			            	<button class="btn">{{data.size}}</button>
			            </div> 
			        </div>
			        <div class="p-color m-3">
			            <h4>Color:</h4>
			            <div class="btn-group btn-group-sm" >
			                <h2 class="font-weight-bold" :style="'color:'+data.color+';'">{{data.color}}</h2>		               
			            </div> 
			        </div>
			        <div class="action">
			            <addtocart :product="data"/>	            
			        </div>
			    </div>
			</div>
			<div class="col-md-8">
				<div class="row product-detail-bottom">
				    <div class="col-lg-12">
				        <ul class="nav nav-pills nav-justified">
				            <li class="nav-item">
				                <a class="nav-link active" data-toggle="pill" href="#description">Description</a>
				            </li>
				            <li class="nav-item">
				                <a class="nav-link" data-toggle="pill" href="#specification">Specification</a>
				            </li>
				            <li class="nav-item">
				                <a class="nav-link" data-toggle="pill" href="#reviews">Photos</a>
				            </li>
				        </ul>

				        <div class="tab-content">
				            <div id="description" class="container tab-pane active">
				                <h4>Product description</h4>
				                <div class="card-category" v-html="data.description">
				                </div>
				                <img v-bind:src="'/product_images/'+data.image_url" alt="Slider Image" class="w-75 img-fluid" />
				            </div>
				            <div id="specification" class="container tab-pane fade">
				                <h4 class="card-title">Other Details</h4>
				                <ul>			                    
				                    <li>{{data.year_of_manufacture}}</li>
				                    <li>{{data.country}}</li>
				                    <li>Made by: {{data.manufacturer}}</li>
				                    <li>{{data.status}}</li>
				                </ul>
				            </div>
				            <div id="reviews" class="container tab-pane fade" v-if="data!=''">
				                <div class="row justify-content-center mt-1" v-if="data.images.length">
									<div class="col-md-4" v-for="(file,f) in data.images" :key="f">
										<img :src="'/product_photos/'+file.image" class="img-fluid">
										<hr>
										<p>{{file.caption}}</p>
									</div>
								</div>
								<div v-else>
									<img v-bind:src="'/product_images/'+data.image_url" alt="Slider Image" class="w-100 img-fluid img-thumbnail" />
								</div>
				            </div>
				        </div>
				    </div>
				</div>
			</div>
		</div>
		<div class="card" v-if="missingProduct">
			<div class="card-body">
				<h4 class="text-center font-weight-bold text-danger">
					PRODUCT NOT FOUND
				</h4>
				<p class="text-center text-info">
					The product you are trying to view is either out of stock or taken down by the seller.
				</p>
			</div>
		</div>
	</div>
</template>
<script>
	import addtocart from './cartButton'
	export default{
		data(){
			return {
				data:'',
				missingProduct:false,
			}
		},
		components:{
			addtocart
		},
		async created(){
			const product = await this.callApi('get','/customer/product/description/'+this.$route.params.id)
			if (product.status==200) {
				this.data=product.data.data
			}
			else{
				this.swr()
				this.missingProduct = true
			}
		},
		mounted(){
        window.scrollTo(0,0)
    }
	}
</script>