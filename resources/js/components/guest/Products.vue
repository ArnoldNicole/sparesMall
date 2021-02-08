<template>
	<div class="product-view">
	    <div class="container-fluid">
	        <div class="row">
	            <div class="col-md-9 single-scroll">
	                <div class="row">
	                    <div class="col-md-4" v-for="(product, p) in products" :key="product.id">
	                        <div class="product-item">
	                            <div class="product-title">
	                                <a href="#">{{product.name}}</a>
	                                <div class="ratting">
	                                    <i class="fa fa-star"></i>
	                                    <i class="fa fa-star"></i>
	                                    <i class="fa fa-star"></i>
	                                    <i class="fa fa-star"></i>
	                                    <i class="fa fa-star"></i>
	                                </div>
	                            </div>
	                            <div class="product-image">
	                                <a href="product-detail.html">
	                                    <img v-bind:src="'/product_images/'+product.image_url" alt="Product Image">
	                                </a>
	                                <div class="product-action">
	                                    <a href="#"><i class="fa fa-cart-plus"></i></a>
	                                    <a href="#"><i class="fa fa-heart"></i></a>
	                                    <a href="#"><i class="fa fa-search"></i></a>
	                                </div>
	                            </div>
	                            <div class="product-price">
	                                <h3><span>Ksh {{product.price}}</span></h3>
	                                <router-link class="btn" :to="'/customer/product/description/'+product.id"><i class="fa fa-shopping-cart"></i>More</router-link>
	                            </div>
	                        </div>
	                    </div>	                    
	                </div>
	                
	                <!-- Pagination Start -->
	                <div class="col-md-12">
	                    <nav aria-label="Page navigation example">
	                        <ul class="pagination justify-content-center">
	                            <li class="page-item" v-bind:class="[{disabled: !pagination.prev_page_url}]">
	                                <a class="page-link" href="#" @click="fetchProducts(pagination.prev_page_url)" :disabled="isLoading" :loading="isLoading">{{isLoading ? 'Loading...' : 'Previous'}}</a>
	                            </li>

	                            <li class="page-item disabled">
	                            	<a class="page-link">{{ pagination.current_page}} of {{pagination.last_page}}</a>
	                            </li>


	                            <li class="page-item" v-bind:class="[{disabled: !pagination.next_page_url}]">
	                                <a class="page-link" @click="fetchProducts(pagination.next_page_url)" :disabled="isLoading" :loading="isLoading">{{isLoading ? 'Loading...' : 'Next'}}</a>
	                            </li>
	                        </ul>
	                    </nav>
	                </div>
	                <!-- Pagination Start -->
	            </div>           
	            
	            <!-- Side Bar Start -->
	            <div class="col-md-3 sidebar">
	                <div class="sidebar-widget category">
	                    <h2 class="title">Category</h2>
	                    <nav class="navbar bg-light">
	                        <ul class="navbar-nav" v-for="(category, c) in categories" :key="category.id">
	                            <li class="nav-item">
	                                <router-link class="nav-link" :to="'/customer/product/categories/'+category.id"></i>{{category.categoryName}}</router-link>
	                            </li>	                            
	                        </ul>
	                    </nav>
	                </div>
	                
	                <div class="sidebar-widget widget-slider">
	                    <div class="sidebar-slider normal-slider">
	                        <div class="product-item">
	                            <div class="product-title">
	                                <a href="#">Product Name</a>
<!-- 	                                <div class="ratting">
	                                    <i class="fa fa-star"></i>
	                                    <i class="fa fa-star"></i>
	                                    <i class="fa fa-star"></i>
	                                    <i class="fa fa-star"></i>
	                                    <i class="fa fa-star"></i>
	                                </div> -->
	                            </div>
	                            <div class="product-image">
	                                <a href="product-detail.html">
	                                    <img src="img/product-10.jpg" alt="Product Image">
	                                </a>
	                                <div class="product-action">
	                                    <a href="#"><i class="fa fa-cart-plus"></i></a>	    
	                                    <a href="#"><i class="fa fa-search"></i></a>
	                                </div>
	                            </div>
	                            <div class="product-price">
	                                <h3><span>$</span>99</h3>      
	                            </div>
	                        </div>
	                    </div>
	                </div> 
	            </div>
	            <!-- Side Bar End -->
	        </div>
	    </div>
	</div>
</template>
<script>
	export default{
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
		}
	}
</script>