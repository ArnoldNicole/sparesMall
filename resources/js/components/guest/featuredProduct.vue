<template>
    <div class="featured-product product">
        <div class="container-fluid" v-if="featuredProducts.length">
            <div class="section-header">
                <h1 class="text-center"> FEATURED PRODUCTS</h1>
            </div>
            <div class="row justify-content-center product-slider product-slider-4">
                <div class="col-lg-3" v-for="(product ,p) in featuredProducts" v-if="featuredProducts.length">
                    <div class="product-item">
                        <div class="product-title">
                            <a href="#">{{product.name}}</a>
                            <!-- <div class="ratting">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </div> -->
                        </div>
                        <div class="product-image">
                            <a href="#">
                                <img v-bind:src="'/product_images/'+product.image_url" v-bind:alt="product.name">
                            </a>
                            <div class="product-action">
                                 <router-link class="btn" :to="'/customer/product/description/'+product.id"><i class="fa fa-shopping-cart"></i>Description
                    </router-link>
                            </div>
                        </div>
                        <div class="product-price">
                            <h3><span>Ksh</span>{{product.price}}</h3>
                            <router-link class="btn" :to="'/customer/product/description/'+product.id"><i class="fa fa-eye"></i>View</router-link>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data(){
            return {
                featuredProducts:[],
            }
        },
        async created()
        {   this.start()
            const res = await this.callApi('get','/featuredProducts')
            if (res.status == 200) {
                this.featuredProducts = res.data
                this.stop()
            }
            else{
                this.error()
            }
        }
    }
</script>