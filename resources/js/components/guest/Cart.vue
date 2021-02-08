<template>
	<div class="cart-page">
	    <div class="container-fluid">
	        <div class="row justify-content-center">
	            <div class="col-md-12">
	                <div class="card">
	                	<div class="card-body">	                		
	                	
	                		        <table class="table table-bordered" v-if="$store.state.cart">
	                		            <thead class="thead-dark">
	                		                <tr>
	                		                    <th>Product</th>
	                		                    <th>Price</th>
	                		                    <th>Quantity</th>
	                		                    <th>Total</th>	
	                		                    <th>Action</th>
	                		                </tr>
	                		            </thead>
	                		            <tbody>
	                		                <tr v-for="(product, p) in $store.state.cart.products">
	                		                    <td>
	                		                        <div class="img">
	                		                            <router-link :to="'/customer/product/description/'+product.id"><img v-bind:src="'/product_images/'+product.image_url" :alt="product.name"></router-link>
	                		                            <p>{{product.name}}</p>
	                		                        </div>
	                		                    </td>
	                		                    <td>Ksh {{product.price}}</td>
	                		                    <td>
	                		                    	<Button size="small" type="error" v-if="product.pivot.quantity!=1" @click="DecreamentProduct(product, p)" :disabled="product.isSaving" :loading="product.isSaving">-
	                		                    	</Button>
	                		                    	{{product.pivot.quantity}}
	                		                    	<Button size="small" type="error" @click="increaseProduct(product, p)" :disabled="product.isSaving" :loading="product.isSaving">+
	                		                    	</Button>

	                		                    	<a :disabled="product.isSaving" class="text-info" @click="updateQuantity(product, product.pivot.quantity )">{{product.isSaving ? 'Saving...' : 'Confirm'}}</a>
	                		                    </td>
	                		                    <td>
	                		                    	{{product.price*product.pivot.quantity}}
	                		                    </td>
	                		                    <td>
	                		                    	<div>
	                		                    		<a class="text-info" @click="removeProduct(product, p)">{{product.isAdding ? 'Removing..' : 'Remove'}}
	                		                    			<i class="fa fa-trash"></i>
	                		                    		</a>
	                		                    	</div>
	                		                    </td>
	                		                </tr>
	                		                <hr>	
	                		                <tr>
	                		                    <td>
	                		                       Grand Total
	                		                    </td>
	                		                    <td>Ksh {{totalPrice}}</td>
	                		                    <td>
	                		                    </td>
	                		                    <td>
	                		                    </td>
	                		                </tr>

	                		            </tbody>
	                		        </table>
	                		        <!-- {{$store.state.cart.products}} -->
	                	</div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</template>
<script>
	
	export default{
		data(){
			return {
				index:-1,
				isChecking:false,
				isAdding:false,
				totalPrice:0,
				quantityData:[],
				products:[],
				element:0,				
			}
		},
		methods:{
			async removeProduct(product, index){
				this.$set(product, 'isAdding', true)
				const res = await this.callApi('post','/customer/product/removeFromCart/'+product.id)
				this.isAdding = false
				if (res.status == 200) {					
					this.$store.commit('downGradeCart', index)
					this.s(product.name+' Removed From Cart')
					this.showRemove = false
					this.calculateTotal()
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
			}, 
			DecreamentProduct(product, index){
				this.index = index
				this.$store.commit('DecreamentProduct', this.index)
				this.calculateTotal()
			},
			increaseProduct(product, index){
				this.index = index
				this.$store.commit('IncrementProduct', this.index)
				this.calculateTotal()
			},
			calculateTotal(){
				this.totalPrice = ""
				this.products = this.$store.state.cart.products				
				for (let i = 0; i < this.products.length; i++) {				this.element =this.products[i].price*this.products[i].pivot.quantity
					this.totalPrice = Number(this.totalPrice)+Number(this.element)
					
				}
			},
			async updateQuantity(product, value){
				if(!confirm('Are you sure you want to Update The Quantity?'))
					return
				this.$set(product, 'isSaving', true)
				let obj = {
					id:product.id,
					quantity:value,					
				}
				this.quantityData = obj
				const updateStatus = await this.callApi('post','/customer/product/updateQuantity', this.quantityData)
				this.$set(product, 'isSaving', false)
				if (updateStatus.status == 401) {
					this.e('Seems your session is Expired, Please Login Again')
				}else{
					if (updateStatus.status==200) {
						this.s('Quantity has been Saved Sucessfully')
					}else{
						this.swr()
					}
				}
			}
		},
		async created(){			
			this.calculateTotal()
			if (!this.$store.state.cart.products.length) {
				this.i('Cart Is Empty')
			}
		}
		
	}
</script>