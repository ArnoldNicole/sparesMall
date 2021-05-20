<template>
	<div>
		<div class="row">
			<div class="col-12">
				<div class="btn-group">
					<button class="btn btn-primary" @click="fetchCompleteOrders">Completed</button>
					<button class="btn btn-info" @click="fetchShippingInProgressOrders">Shipping In Progress</button>
					<button class="btn btn-warning" @click="fetchOrders">Unpaid</button>

				</div>
					<table class="table table-hover table-responsive-md">
					  <thead>
					    <tr class="bg-info">
					      <th scope="col">#</th>
					      <th scope="col">Amount Paid</th>
					      <th scope="col">Placed</th>
					      <th scope="col">Status</th>
					      <th scope="col"></th>		      
					      <th scope="col">Action</th>
					    </tr>
					  </thead>
					  <tbody>
					    <tr v-for="(order, o) in orders" :key="order.id">
					      <td>{{order.id}}</td>
					      <td><p>Paid: {{order.amount_received}}</p>
					      	<p>Expected: {{order.totalPrice}}</p>
					      </td>
					      <td>{{order.created_at}}</td>
					      <td>
					      	
					    	 {{order.status}} 
					      
					      	<!-- <p>{{order.address.Address}},  {{order.address.city}}, {{order.address.region}}</p> -->
				            <!-- <p>Mobile: {{order.address.phone_number}}</p> -->
				            <!-- <p v-html="order.address.additional_information"></p> -->
					      </td>
					      <td>
					      	<Button size="small" type="info" @click="verifyPayment(order, o)">
					      			Details
					      		</Button>
					      	<!-- <div class="col-md-12 mb-1 border border-info" v-for="(product, p) in order.products"> -->
					      		<!-- {{product}} -->
					      	    <!-- <p>Name: {{product.name}},</p> -->
					      	    <!-- <p>Price: {{product.price}} /=</p>	 -->
					      	    <!-- <p>Quantity: {{product.pivot.quantity}}</p>	      	     -->
					      	<!-- </div> -->
					      </td>				      
					      <td>
					      	<div class="btn-group">
					      		<Button size="small" type="info" @click="pay(order, o)" v-if="order.status=='pending'">
					      			Pay
					      		</Button>
					      		<Button size="small" type="error" @click="calculateTotal(order.products, o)">
					      			Bill
					      		</Button>
					      	</div>
					      </td>		      
					    </tr>				    
					  </tbody>
					</table>
			</div>		
		</div>
		<Modal v-model="paymentModal" :closable="true" :mask-closable="false" footer-hide :styles="{top:'20px'}">
			<div slot="header">
				Follow The Steps Below to pay your order
			</div>
			<div class="row justify-content-center">
				<div class="col-8" v-if="payment_data.payment_method=='Eazzy Pay'">
					<h5 class="text-center">EAZZY PAY</h5>
					<ol class="">
						<li>Go To Your Bank</li>
						<li>Transfer Funds</li>
						<li>Enter Account Number <code>XXXXXXXXXX</code></li>
						<li>Enter account name as <code> {{payment_data.address.phone_number+payment_data.id}}</code>
						</li>
						<li>Enter amount as <code>{{Number(payment_data.totalPrice)-Number(payment_data.amount_received)}} /=</code>
						</li>
						<li>In the Payment Input below Enter the BANK Code Received.</li>
					</ol>
				</div>
				<div class="col-8" v-if="payment_data.payment_method=='Mpesa'">
					<h5 class="text-center text-success">MPESA PAYMENT INSTRUCTIONS</h5>
					<ol class="">
						<li>Ensure the phone number registered on our platform is on</li>
						<li>Click on the Complete Payment Button Below</li>
						<li>AFter a success message, check your phone</li>
						<li>Enter Your Mpesa Pin and continue.
						</li>
						<li>Ensure Amount Billled is <code>{{Number(payment_data.totalPrice)-Number(payment_data.amount_received)}} /=</code>
						</li>
						<li>Check Back After a few seconds for the payment status</li>
					</ol>
				</div>
				<div class="col-8" v-if="payment_data.payment_method=='Cash On Delivery'">
					Currently we are not providing Cash On Delivery. Check Back Later.
				</div>

				<!-- <div class="col-12">
					<label>Payment Code</label>
					<input type="text" v-model="payment.code"  class="form-control" required placeholder="PH76G546H">
					<p class="text-danger">UnderPaying or Over Paying will lead your order being void, while refunds are possible, it`s always not worth. <strong>Enter Exact Amounts</strong></p>
				</div> -->
				<div class="col-md-12">
					<Button type="success" @click="submitPayment" :loading="isPaying" :disabled="isPaying">{{isPaying ? 'Processing': 'Complete Payment'}} <i class="fa fa-money" aria-hidden="true"></i></Button>
				</div>
			</div>
		</Modal>
		<Modal v-model="verifyPaymentModal" fullscreen scrollable :closable="true" :mask-closable="false" footer-hide>
			<div slot="header">
				 Order Details Payment Pop Up
			</div>
			<div class="row justify-content-center" v-if="verifyPaymentment_data!=[]">
				<div class="col-12 mb-2">
				<h5>Shipping Adress</h5>				
				 <p>{{address.Address}},  {{address.city}}, {{address.region}}</p>
				 <p>Mobile: {{address.phone_number}}</p> 
				 <p v-html="address.additional_information"></p> 
				</div>

				<div class="col-12 mb-2">
					<h5>Products</h5>

					<div class="row">
					    <div class="col-md-3" v-for="(product, p) in verifyPaymentment_data.products" :key="p">
					    	<p>Name: {{product.name}},</p>
					    	<p>Price: {{product.price}} /=</p>	
					    	<p>Quantity: {{product.pivot.quantity}}</p>
					    	<p>Size: {{product.size}}</p>
					    	<p>Color: {{product.color}}</p>
					    	<img v-bind:src="'/product_images/'+product.image_url" alt="Slider Image" class="img-thumbnail img-fluid w-75" />
					    </div>	
					    <!-- <div class="col-8">	
					    	
					    </div> -->      	    
					</div>
				</div>


				<div class="col-12 mb-2">
					<h5>Payment Data</h5>
					<p>Payment Mode: {{verifyPaymentment_data.payment_method}}</p>
					<p v-if="paymentData!=null">Payment Code: {{paymentData.code}}</p>
					<p v-if="paymentData!=null">Amount Received: {{paymentData.amount}}</p>
					<p v-if="paymentData!=null">Balance: {{Number(verifyPaymentment_data.totalPrice)-Number(paymentData.amount)}} </p>
				</div>
			</div>
		</Modal>
	</div>
</template>
<script>
	export default{
		data(){
			return {
				isWorking:false,
				address:[],
				verifyPaymentment_data:[],				
				verifyPaymentment:{
					order:'',
					amount:'',
					code:''
				},
				paymentData:[],
				orders:[],
				index:-1,
				isPaying:false,
				products:[],
				totalPrice:0,
				element:0,
				payment_data:[],
				paymentModal:false,
				verifyPaymentModal:false,
				payment:{
					order:'',
					amount:'',
				}
			}
		},
		methods:{
			async submitPayment(){
				this.isPaying = true 
				let obj = {
					order: this.payment_data.id,
					amount:Number(this.payment_data.totalPrice)-Number(this.payment_data.amount_received),
				}
				this.payment = obj
				this.start()
			 const paymentRequest = await this.callApi('post','/customer/product/orderPayment', this.payment)
			 if (paymentRequest.status == 201) {
			 	this.stop()
			 	this.orders[this.index].status = 'Paid, Awaiting Confirmation';
			 	this.paymentModal = false
			 	this.payment_data = []
			 	this.payment = ''
			 	this.isPaying = false,
				 this.s('Success, Please check your phone')
			 }else{
			 	this.isPaying = false
			 	this.showError()
			 	if (paymentRequest.status == 422) {
			 		for (let i in paymentRequest.data.errors) {
			 			this.e(paymentRequest.data.errors[i][0])
			 		}
			 	}else{
			 		if (paymentRequest.status == 404) {
			 			this.e('Order has a pending Payment')
			 		}else{
			 			this.swr()
			 		}
			 	}
			 }


			},
			async fetchOrders(){
				this.start()
				const res = await this.callApi('get','/customer/product/getOrders')
				if (res.status == 200) {
					this.orders = res.data.data
					this.stop
					this.products = this.orders.products
				}else{
					this.showError()
				}
			},
			async fetchCompleteOrders(){
				this.start()
				const res = await this.callApi('get','/customer/product/fetchCompleteOrders')
				if (res.status == 200) {
					this.orders = res.data.data
					this.stop()
					this.products = this.orders.products
				}else{
					this.showError()
				}
			},
			async fetchShippingInProgressOrders(){
				this.start()
				const res = await this.callApi('get','/customer/product/fetchShippingInProgressOrders')
				if (res.status == 200) {
					this.orders = res.data.data
					this.stop()
					this.products = this.orders.products
				}else{
					this.showError()
				}
			},
			verifyPayment(order, index){
				this.verifyPaymentment_data = order
				this.index = index
				this.address=order.address
				this.paymentData=order.payment
				this.verifyPaymentModal = true
			},
			pay(order, index){
				this.payment_data = order
				this.index = index
				this.paymentModal = true
			},
			calculateTotal(products, index){
				alert(this.orders[index].totalPrice)				
			},
		},

		created(){
			this.fetchOrders()
		}
	}
</script>