<template>
	<div>		
		<div v-if="!isChecking" :loading="isChecking">
			<Button class="btn btn-danger" v-if="showRemove" @click="removeProduct"><i class="fa fa-shopping-cart"></i>{{isAdding ? 'Removing..' : 'Remove From Cart'}}</Button>
			<Button class="btn btn-succes" @click="addToCart" v-else :loading="isAdding" :disabled="isAdding"><i class="fa fa-shopping-cart"></i>{{isAdding ? 'Adding..' : 'Add to Cart'}}</Button>
		</div>
	</div>
</template>
<script>
	export default{
		props:['product'],
		data(){
			return {
				showRemove:false,
				isAdding:false,
				isChecking:false,
				index:'',
				newObject:[],
			}
		},
		methods:{
			async addToCart(){
				this.isAdding = true				
				const res = await this.callApi('post','/customer/product/addtocart/'+this.product.id)
				this.isAdding = false
				if (res.status == 200) {
					let obj = {
						category_id: this.product.category_id,
						color: this.product.color,
						country:this.product.country,
						created_at: this.product.created_at,
						deleted_at: this.product.deleted_at,
						description: this.product.description,
						featured: this.product.featured,
						id: this.product.id,
						image_url: this.product.image_url,
						is_featured: this.product.is_featured,
						manufacturer: this.product.manufacturer,
						name:this.product.name,
						price: this.product.price,
						size: this.product.size,
						status: this.product.status,
						updated_at: this.product.updated_at,
						year_of_manufacture: this.product.year_of_manufacture,
						pivot:{
							basket_id:'',
							product_id:this.product.id,
							quantity:1
						}
					}
					this.newObject = obj
					this.$store.commit('updateCart', this.newObject)
					this.s(this.product.name+' Added to Cart')
					this.showRemove = true
					//update cart counter
				}else{
					if (res.status == 401) {
						this.e('To add a product to cart, Please Login first')
					}
					else{
						if (res.status == 201) {
							this.showRemove = true
							this.i('Product Exists in Cart. Proceed to Cart if you want to add more')
						}else{
							this.swr
						}
					}
				}
			},
			getProduct(product){
				return product.id===this.product.id
			},
			async removeProduct(){
				// alert('To remove product from Cart, got to cart and click Remove')
				this.index=this.$store.state.cart.products.findIndex(this.getProduct)
				this.isAdding = true
				//get the index of the product from cart				
				
				const res = await this.callApi('post','/customer/product/removeFromCart/'+this.product.id)
				this.isAdding = false
				if (res.status == 200) {
					//get the index of the product
					this.$store.commit('downGradeCart', this.index)
					this.s(this.product.name+' Removed From Cart')
					this.showRemove = false
					//update cart counter
				}else{
					if (res.status == 401) {
						this.e('To Modify Cart, Please Login first')
					}
					else{
						if (res.status == 201) {
							this.showRemove = true
							this.i('Product Does Not Exist in Cart. Click Add To Cart')
						}else{
							this.swr
						}
					}
				}
			}
		},
		async created(){
			this.isChecking = true;
			//check status in cart
			const inCartReq = await this.callApi('post','/customer/product/checkCart/'+this.$route.params.id)
			if (inCartReq.status==401) {
				this.isChecking = false
				this.i('To add products to Cart, you`ll be required to login')
			}else{
				if (inCartReq.status == 200) {
					this.showRemove = true
					this.isChecking = false
				}else{
					if (inCartReq.status==201) {
						this.showRemove=false
						this.isChecking = false
					}else{
						if (inCartReq.status ==404) {
							this.e('Missing Product. Redirecting to Homepage')
							// window.location = '/'
							}else{
							this.isChecking = false
						}
					}
				}
			}
		}
	}
</script>