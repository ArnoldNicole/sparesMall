<template>
	<div class="checkout">
	    <div class="container-fluid"> 
	        <div class="row">
	            <div class="col-md-8">
	                <div class="checkout-inner mb-1" v-if="!hasAdresses">
	                    <div class="billing-address">
	                        <h2>Add Shipping & Billing Address</h2>
	                        <div class="row">
	                            <div class="col-md-6">
	                                <label>Mobile No</label>
	                                <input class="form-control" v-model="data.phone_number" type="text" placeholder="Mobile No">
	                            </div>
	                            <div class="col-md-12">
	                                <label>Address</label>
	                                <input class="form-control" v-model="data.Address" type="text" placeholder="Address">
	                            </div>
	                            <div class="col-md-6">
	                                <label>Region</label>
	                                <select class="custom-select" v-model="data.region">
	                                    <option selected value="">Select Region Below</option>
	                                    <option value="Coast Region">Coast Region</option>
	                                    <option value="North Eastern">North Eastern</option>
	                                    <option value="Eastern">Eastern</option>
	                                    <option value="Central">Central</option>
	                                    <option value="Nairobi">Nairobi</option>
	                                    <option value="Rift Valley (North Rift)">Rift Valley (North Rift)</option>
	                                    <option value="Rift Valley (South Rift)">Rift Valley (South Rift)</option>
	                                    <option value="Western">Western</option>
	                                    <option value="Nyanza">Nyanza</option>
	                                    
	                                </select>
	                            </div>
	                            <div class="col-md-6">
	                                <label>City</label>
	                                <input v-model="data.city" class="form-control" type="text" placeholder="City or Town in the Region">
	                            </div>
	                             <div class="col-md-12">
	                                <label>Aditional Information</label>
	                                <textarea class="form-control" rows="6" v-model="data.additional_information"  placeholder="More Info About your Shipping address">
	                                </textarea>
	                            </div> 
	                            <div class="col-md-12">
	                            	<Button type="info" :loading="isSaving" :disabled="isSaving" @click="saveAdress">
	                            	{{isSaving ? 'Saving...' : 'Save Shipping Address'}}
	                            </Button>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <div class="checkout-inner mb-1" v-if="address.length">
	                    <div class="billing-address">
	                        <h2>Select Billing Address <span class="float-right text-info  font-weight-bold" @click="hasAdresses=false">Add New</span></h2>
	                        <div class="row"  v-for="(adres, a) in address" :key="adres.id">
	                        	<div class="col-md-12 mb-2">
	                        	<div class="card">
	                        		<div class="card-body"> 				
	                        				<div class="custom-control custom-radio">
	                        				    <input type="radio" v-model="orderData.shipToAdress" class="custom-control-input" :id="'payment'+adres.id"  :value="adres.id">
	                        				    <label class="custom-control-label" v-bind:for="'payment'+adres.id"><h5 class="text-info">Use This Adress</h5></label>
	                        				</div>	                        	    
	                        			    <p>{{adres.Address}},  {{adres.city}}, {{adres.region}}</p>
	                        			    <p>Mobile: {{adres.phone_number}}</p>
	                        			    <p v-html="adres.additional_information"></p>
	                        		</div>
	                        	</div>
	                        	 </div>
	                        </div>
	                    </div>
	                </div>
	                <div class="checkout-inner">
	                    <div class="checkout-summary">
	                        <h1>Cart Total</h1>
	                        <div v-for="(product, p) in $store.state.cart.products">
	                        	<p>{{product.name}}<small>&nbsp;{{product.pivot.quantity}}  Piece(s)</small><span> : <code>KSH</code> {{product.price*product.pivot.quantity}}</span></p>
	                        </div>
	                        <hr>
	                        <p class="sub-total">Sub Total<span>{{totalPrice}}</span></p>
	                        <hr>
	                        <p class="ship-cost">Shipping Cost<span>Ksh 250</span></p>
	                        <h2>Grand Total<span>{{Number(totalPrice)+Number(250)}}</span></h2>
	                    </div>
	                </div>
	               
	            </div>
	            <div class="col-md-4">
	               <div class="checkout-payment">
	                   <div class="payment-methods">
	                       <h1>Payment Methods</h1>
	                       <div class="payment-method">
	                           <div class="custom-control custom-radio">
	                               <input v-model="orderData.paymentMethod" type="radio" value="Mpesa" class="custom-control-input" id="payment-1" name="payment">
	                               <label class="custom-control-label" for="payment-1">Mpesa</label>
	                           </div>
	                           <div class="payment-content" id="payment-1-show">
	                               <p>
	                                   We`ll give you our paybill in the next step
	                               </p>
	                           </div>
	                       </div>
	                      <!--  <div class="payment-method">
	                           <div class="custom-control custom-radio">
	                               <input v-model="orderData.paymentMethod" type="radio" value="Cash On Delivery" class="custom-control-input" id="payment-2" name="payment">
	                               <label class="custom-control-label" for="payment-2">Cash On Delivery</label>
	                           </div>
	                           <div class="payment-content" id="payment-2-show">
	                               <p>
	                                   Our Agents will collect Cash from you during delivery. However Shipping fees is paid prior to shipment via MPESA.
	                               </p>
	                           </div>
	                       </div> -->
	                       <div class="payment-method">
	                           <div class="custom-control custom-radio">
	                               <input v-model="orderData.paymentMethod" type="radio" value="Eazzy Pay" class="custom-control-input" id="payment-3" name="payment">
	                               <label class="custom-control-label" for="payment-3">Eazzy Pay</label>
	                           </div>
	                           <div class="payment-content" id="payment-3-show">
	                               <p>
	                                  Use your Banking app to make Payments
	                               </p>
	                           </div>
	                       </div>	                        
	                   </div>
	                   <div class="checkout-btn" v-if="$store.state.cart.products">
	                       <Button @click="placeOrder" :loading="placingOrder" :disabled="placingOrder"><span class="text-sm">{{placingOrder ? 'Ordering...' : 'Place Order'}}</span></Button>
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
				data:{
					phone_number:'',
					Address:'',
					additional_information:'',
					region:'',
					city:'',
				},
				orderData:{
					shipToAdress:'',
					paymentMethod:'',
					totalPrice:0,
				},
				placingOrder:false,
				address:[],				
				isSaving:false,
				hasAdresses:true,			
				totalPrice:0,
				products:[],
				element:0,
				
			}
		},
		methods:{
			async placeOrder(){	
				this.placingOrder = true
				this.orderData.totalPrice=Number(this.totalPrice)+250
				if (this.orderData.shipToAdress=='') {
					this.placingOrder = false
					return this.e('You have not chosen a Shipping Adress')
				}
				if (this.orderData.paymentMethod=='') {
					this.placingOrder = false
					return this.e('Please choose a Payment Method')
				}

				this.start()
				const orderRequest = await this.callApi('post','/customer/product/placeOrder', this.orderData)
				if (orderRequest.status == 201) {
					this.placingOrder = false
					this.orderData = ''
					this.stop()
					this.s('Order Placed, Redirecting to Payment')
					window.location = '/customer/product/completeOrder'
				}
				else{
					this.placingOrder = false
					this.showError()

					if (orderRequest.status == 422) {
						for (let i in orderRequest.data.errors) {
							this.e(orderRequest.data.errors[i][0])
						}
					}else{
						if (orderRequest.status == 405) {
							this.e(orderRequest.data.msg)
						}else{
						this.e('Something went Wrong. Thats all we Know!')
					}
					}
				}
			},
			calculateTotal(){
				this.totalPrice = 0
				this.products = this.$store.state.cart.products	
				if(this.$store.state.cart.products){
					for (let i = 0; i < this.products.length; i++) {				this.element =this.products[i].price*this.products[i].pivot.quantity
						this.totalPrice = Number(this.totalPrice)+Number(this.element)
						
					}
				}			
				
			},
			async saveAdress(){
				this.isSaving = true
				if (this.data.phone_number.trim()=='') {
					this.isSaving = false
					return this.e('Phone Number is Required')
				}

				if (this.data.Address.trim()=='') {
					this.isSaving = false
					return this.e('Address is Required')
				}

				if (this.data.additional_information.trim()=='') {
					this.isSaving = false
					return this.e('Please provide additional Information about your location')
				}

				if (this.data.region=='') {
					this.isSaving = false
					return this.e('Select A Valid Region')
				}

				if (this.data.city.trim()=='') {
					this.isSaving = false
					return this.e('City Is required')
				}

				this.start()
				const saveAdressRequest = await this.callApi('post', '/customer/product/add_acount_data', this.data)
				if (saveAdressRequest.status == 201) {
					this.stop()
					this.isSaving = false
					this.address.unshift(saveAdressRequest.data)
					this.hasAdresses = true
					this.data = ''
				}
				else{
					this.showError()
					this.isSaving = false
					if (saveAdressRequest.status==422) {
						 		for (let i in saveAdressRequest.data.errors) {
										this.e(saveAdressRequest.data.errors[i][0])
								}
					}else{
						if (saveAdressRequest.status == 401) {
							this.e('Expired Session Please Refresh Page')
						}else{
							this.swr()
						}
					}
				}

			}
		},
		async created()
		{	//get default address
			this.start()
			const res = await this.callApi('get','/customer/product/account_data')
			if (res.status==200) {
				this.address = res.data
				this.hasAdresses =  true
				this.stop()
			}
			else{
				if (res.status == 404) {
					this.hasAdresses = false
					this.stop()
				}else{
					this.showError()
				}
			}

			this.calculateTotal()
		}
	}
</script>