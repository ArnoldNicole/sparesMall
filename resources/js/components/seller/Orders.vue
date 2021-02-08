<template>
	<div>
		<div class="row">
			<div class="col-md-3">
				<h4>View Orders</h4>
				<div class="btn-group-vertical">
					<button class="btn btn-block btn-success" @click="fetchCompleteOrders">Completed</button>
					<button class="btn btn-block btn-info" @click="fetchShippingInProgressOrders">Shipping In Progress</button>
					<button class="btn btn-block btn-warning" @click="fetchOrders">Paid Awaiting Confirmation & Shipping</button>

				</div>
			</div>
			<div class="col-md-9">
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
					      </td>
					      <td>
					      	<div class="btn-group">
					      		<Button size="small" type="info" @click="verifyPayment(order, o)">
					      			Details
					      		</Button>
					      	</div>
					      </td>				      
					      <td>					      	
					      		<Button size="small" type="error" @click="calculateTotal(order.products, o)">
					      			Amount Payable
					      		</Button>					      
					      </td>		      
					    </tr>				    
					  </tbody>
					</table>
			</div>		
		</div>
		<Modal v-model="verifyPaymentModal":width="90" :closable="false" :mask-closable="false">
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
					    <div class="col-4" v-for="(product, p) in verifyPaymentment_data.products">
					    	<p>Name: {{product.name}},</p>
					    	<p>Price: {{product.price}} /=</p>	
					    	<p>Quantity: {{product.pivot.quantity}}</p>
					    	<p>Size: {{product.size}}</p>
					    	<p>Color: {{product.color}}</p>
					    	<img v-bind:src="'/product_images/'+product.image_url" alt="Slider Image" class="img-thumbnail w-100" />
					    </div>	
					    <!-- <div class="col-8">	
					    	
					    </div> -->      	    
					</div>
				</div>


				<div class="col-12 mb-2">
					<h5>Payment Data</h5>
					<p>Payment Mode: {{verifyPaymentment_data.payment_method}}</p>
					<p>Payment Code: {{paymentData.code}}</p>
					<p>Amount Received: {{paymentData.amount}}</p>
					<p>Balance: {{Number(verifyPaymentment_data.totalPrice)-Number(paymentData.amount)}} </p>
					<div class="space"></div>
					<div><p class="alert-info">All the Order details shown here are correct, 
						<div v-if="verifyPaymentment_data.status=='Paid, Awaiting Confirmation'">
							package the order, and update the order status to shipping by
							<Button :loading="isWorking" :disabled="isWorking" @click="updateToShipping(verifyPaymentment_data.id)">{{isWorking ? 'Updating...' : 'clicking me'}}</Button>
						</div>
						<div v-if="verifyPaymentment_data.status=='Shipping In Progress'">
							Close the Order By marking it as complete by
							<Button :loading="isWorking" :disabled="isWorking" @click="updateToComplete(verifyPaymentment_data.id)">{{isWorking ? 'Updating...' : 'clicking me'}}</Button>
						</div>
					</p></div>
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
				isWorking:false,
				index:-1,
				isPaying:false,
				products:[],
				totalPrice:0,
				element:0,
				address:[],
				verifyPaymentment_data:[],
				verifyPaymentModal:false,
				verifyPaymentment:{
					order:'',
					amount:'',
					code:''
				},
				paymentData:[],
			}
		},
		methods:{
		async updateToComplete(order){
			this.isWorking = true
			const u = await this.callApi('post','/seller/orders/closeOrders/'+order)
			if (u.status == 200) {
				this.isWorking = false
				this.s('Order Status has been marked as  complete')
				this.orders.splice(this.index, 1)
				this.verifyPaymentModal = false
				this.verifyPaymentment_data = ''
				this.paymentData = ''

			}else{
				this.isWorking = false
				this.e('Problem Occured Updating Status')
			}
		},	
		async updateToShipping(order){
			this.isWorking = true
			const u = await this.callApi('post','/seller/orders/getOrders/'+order)
			if (u.status == 200) {
				this.isWorking = false
				this.s('Order Status has been updated to "Shipping In Progress"')
				this.orders.splice(this.index, 1)
				this.verifyPaymentModal = false
				this.verifyPaymentment_data = ''
				this.paymentData = ''

			}else{
				this.isWorking = false
				this.e('Problem Occured Updating Status')
			}
		},		
			async fetchOrders(){
				this.start()
				const res = await this.callApi('get','/seller/orders/getOrders')
				if (res.status == 200) {
					this.orders = res.data.data
					this.stop()
					this.products = this.orders.products
				}else{
					this.showError()
				}
			},
			async fetchCompleteOrders(){
				this.start()
				const res = await this.callApi('get','/seller/orders/fetchCompleteOrders')
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
				const res = await this.callApi('get','/seller/orders/fetchShippingInProgressOrders')
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
			calculateTotal(products, index){
				alert(this.orders[index].totalPrice)				
			},
		},

		created(){
			this.fetchOrders()
		}
	}
</script>