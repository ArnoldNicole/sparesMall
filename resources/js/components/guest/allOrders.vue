<template>
	<div>
		<div class="row">
			<div class="col-12">
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
					      	<Button>
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
					      		<Button size="small" type="info" @click="pay(order, o)">
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
		<Modal v-model="paymentModal" :closable="false" :mask-closable="false">
			<div slot="header">
				Order Payment Modal
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
						<li>Go To Mpesa</li>
						<li>Select Lipa na Mpesa</li>
						<li>Enter business Numer as <code>XXXXXX</code></li>
						<li>Enter account as <code> {{payment_data.address.phone_number+payment_data.id}}</code>
						</li>
						<li>Enter amount as <code>{{Number(payment_data.totalPrice)-Number(payment_data.amount_received)}} /=</code>
						</li>
						<li>In the Payment Input below Enter the MPESA Code Received.</li>
					</ol>
				</div>
				<div class="col-8" v-if="payment_data.payment_method=='Cash On Delivery'">
					Currently we are not providing Cash On Delivery. Check Back Later.
				</div>

				<div class="col-12">
					<label>Payment Code</label>
					<input type="text" v-model="payment.code"  class="form-control" required placeholder="PH76G546H">
					<p class="text-danger">UnderPaying or Over Paying will lead your order being void, while refunds are possible, it`s always not worth. <strong>Enter Exact Amounts</strong></p>
				</div>
				<div class="col-md-12">
					<Button @click="submitPayment" :loading="isPaying" :disabled="isPaying">{{isPaying ? 'Processing': 'Complete Payment'}}</Button>
				</div>
			</div>
		</Modal>
	</div>
</template>
<script>
	export default{
		data(){
			return {
				orders:[],
				index:-1,
				isPaying:false,
				products:[],
				totalPrice:0,
				element:0,
				payment_data:[],
				paymentModal:false,
				payment:{
					order:'',
					amount:'',
					code:''
				}
			}
		},
		methods:{
			async submitPayment(){
				this.isPaying = true 
				if (this.payment.code=='') {
					this.isPaying = false
					return this.e('PLease Provide a Code')
				}
				let obj = {
					order: this.payment_data.id,
					amount:Number(this.payment_data.totalPrice)-Number(this.payment_data.amount_received),
					code:this.payment.code,
				}
				this.payment = obj
				this.start()
			 const paymentRequest = await this.callApi('patch','/customer/product/orderPayment', this.payment)
			 if (paymentRequest.status == 200) {
			 	this.stop()
			 	this.orders[this.index].status = 'Paid, Awaiting Confirmation';
			 	this.paymentModal = false
			 	this.payment_data = []
			 	this.payment = ''
			 	this.isPaying = false
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