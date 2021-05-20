<template>
	<div class="product-view">
	    <div class="container-fluid">
			<div class="row justify-content-center mb-2">
					                <!-- Pagination Start -->
	                <div class="col-md-12">
	                    <nav aria-label="Products Navigation">
	                        <ul class="pagination justify-content-center">
	                            <li class="page-item" v-bind:class="[{disabled: !pagination.prev_page_url}]">
	                                <a class="page-link" href="#" @click="fetchProducts(pagination.prev_page_url)" :disabled="isLoading" :loading="isLoading">{{isLoading ? 'Loading...' : 'Previous'}}</a>
	                            </li>
	                            <li class="page-item disabled">
	                            	<a class="page-link">Page {{ pagination.current_page}} of {{pagination.last_page}}</a>
	                            </li>
	                            <li class="page-item" v-bind:class="[{disabled: !pagination.next_page_url}]">
	                                <a class="page-link" @click="fetchProducts(pagination.next_page_url)" :disabled="isLoading" :loading="isLoading">{{isLoading ? 'Loading...' : 'More'}}</a>
	                            </li>
	                        </ul>
	                    </nav>
	                </div>
	                <!-- Pagination Start -->
			</div>
	        <div class="row">
	            <div class="col-md-9 single-scroll">
	                <div class="row">
	                    <div class="col-md-2" v-for="(product, p) in products" :key="p">
	                        <div class="product-item">
	                            <div class="product-title">
	                                <a href="#">{{product.name}}</a>
	                            </div>
	                            <div class="product-image">
	                                <a href="#">
	                                    <img v-bind:src="'/product_images/'+product.image_url" alt="Product Image" class="img-fluid">
	                                </a>
	                                <div class="product-action">
	                                    <router-link title="Go To Description" class="btn" :to="'/customer/product/description/'+product.id"><i class="fa fa-hand-paper-o" aria-hidden="true"></i></router-link>
	                                    <cart-button :product="product"></cart-button>
	                                </div>
	                            </div>
	                            <div class="product-price">
	                                <h3><span>Ksh {{product.price}}</span></h3>
	                                <!-- <router-link class="btn" :to="'/customer/product/description/'+product.id"><i class="fa fa-shopping-cart"></i>More</router-link> -->
	                            </div>
	                        </div>
	                    </div>	                    
	                </div>                

	            </div>           
	            
	            <!-- Side Bar Start -->
	            <div class="col-md-3 sidebar">
	                <div class="sidebar-widget category">
	                    <h2 class="title">Our Product Categories</h2>
						<ul class="list-group">
							<li class="list-group-item d-flex justify-content-between align-items-center" v-for="(category, c) in categories" :key="c">
								<router-link :to="'/customer/product/categories/'+category.id">
									<span class="badge badge-secondary badge-pill">{{category.categoryName}}</span>
								</router-link>								
							</li>
						</ul>
	                </div>
	                
	            </div>
	            <!-- Side Bar End -->
	        </div>
	    </div>
	</div>
</template>
<script>
import cartButton from '../guest/cartButton'
import FeaturedProduct from '../guest/featuredProduct'
	export default{
		components:{
			cartButton,
			FeaturedProduct
		},
		data(){
			return{
				products:[],
				pagination:{},
				categories:[],
				isLoading:false,
			}
		},
		methods:{
			async fetchProducts(page_url){
				this.isLoading = true
				let vm = this
				page_url = page_url || '/customer/browse/products/all'
				const res = await this.callApi('get', page_url)				
				this.products = res.data.data
				this.isLoading = false
				vm.makePagination(res.data.meta, res.data.links);
			},
			makePagination(meta, links){
			let pagination ={
				current_page:meta.current_page,
				last_page: meta.last_page,
				next_page_url:links.next,
				prev_page_url:links.prev
				};	

			this.pagination=pagination;
			}
		},
		async created(){
			this.fetchProducts()
			const fetchCategories = await this.callApi('get','/app/seller/categories/all')
			if (fetchCategories.status == 200) {
				this.categories = fetchCategories.data				
			}else{
				this.e('Error In Page');
			}
		},
		mounted(){
        window.scrollTo(0,0)
    }
	}
</script>