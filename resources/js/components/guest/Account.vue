<template>
	<div class="my-account">
	    <div class="container-fluid">
	        <div class="row">
	            <div class="col-md-3">
	                <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
	                    <a class="nav-link active" id="dashboard-nav" data-toggle="pill" href="#dashboard-tab" role="tab"><i class="fa fa-tachometer-alt"></i>Dashboard</a>
	                    <a class="nav-link" id="orders-nav" data-toggle="pill" href="#orders-tab" role="tab"><i class="fa fa-shopping-bag"></i>Orders</a>	                    
	                    <a class="nav-link" id="address-nav" data-toggle="pill" href="#address-tab" role="tab"><i class="fa fa-map-marker-alt"></i>address</a>
	                    <a class="nav-link" id="account-nav" data-toggle="pill" href="#account-tab" role="tab"><i class="fa fa-user"></i>Account Details</a>	                   
	                </div>
	            </div>
	            <div class="col-md-9">
	                <div class="tab-content">
	                    <div class="tab-pane fade show active" id="dashboard-tab" role="tabpanel" aria-labelledby="dashboard-nav">
	                        <h4>Dashboard</h4>
	                        <p>
	                            Welcome {{$store.state.user.name}} to <code>Espares Online</code>. We sell products from all known brands of vehicles. 
	                        </p> 
	                    </div>
	                    <div class="tab-pane fade" id="orders-tab" role="tabpanel" aria-labelledby="orders-nav">
	                       <orders/>
	                    </div>
	                    <div class="tab-pane fade" id="address-tab" role="tabpanel" aria-labelledby="address-nav">
	                    	 <h4>Address</h4>
	                    	 <addAddress></addAddress>
	                        <addressList></addressList>
	                    </div>
	                    <div class="tab-pane fade" id="account-tab" role="tabpanel" aria-labelledby="account-nav">
	                        <h4>Account Details</h4>
	                        <div class="row">
	                            <div class="col-md-6">
	                                <input v-model="$store.state.user.name"   class="form-control" type="text" placeholder="First Name">
	                            </div> 
	                            <div class="col-md-6">
	                                <input v-model="$store.state.user.email"class="form-control" type="text" placeholder="Email">
	                            </div>	                            
	                            <!-- <div class="col-md-12">
	                                <button class="btn">Update Account</button>
	                                <br><br>
	                            </div> -->
	                        </div>
	                        <h4>Password change</h4>
	                        <div class="row">
	                            <div class="col-md-12">
	                                <input class="form-control" v-model="data.oldpassword" type="password" placeholder="Current Password">
	                            </div>
	                            <div class="col-md-6">
	                                <input class="form-control" v-model="data.password" type="password" placeholder="New Password">
	                            </div>
	                            <div class="col-md-6">
	                                <input class="form-control" v-model="data.password_confirmation" type="password" placeholder="Confirm Password">
	                            </div>
	                            <div class="col-md-12">
	                                <Button :disabled="isActive" :loading="isActive" class="btn" @click="updatePassword">{{isActive ? 'Saving...' :'Save Changes'}}</Button>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</template>
<script>
	import orders from './allOrders'
	import addressList from './addressList'
	import addAddress from './addAdress'
	export default{
		data(){
			return {
				isActive:false,
				data:{
					oldpassword:'',
					password:'',
					password_confirmation:'',
				}
			}
		},
		methods:{
			async updatePassword(){
				if(!confirm('Are You sure You want to Change Your Password?'))
					return
				if (this.data.oldpassword.trim() == '') {
					this.e('Old Password Is Required')
					return this.isActive = false
				}
				if (this.data.password.trim() == '') {
					this.e('New Password Cant Be Blank')
					return this.isActive = false
				}
				if (this.data.password_confirmation.trim()=='') {
					this.e('Please Confirm Your New Password')
					return this.isActive = false
				}
				this.isActive = true
				const r = await this.callApi('patch','/user/account/updatePassword', this.data)
				if (r.status == 200) {
					this.s('Password Changed, Next Time You Log In use the New Password')
					this.isActive = false
					this.data = ''
				}else{
					this.isActive = false
					if (r.status == 422) {
						for (let i in r.data.errors) {
								this.e(r.data.errors[i][0])
						}
					}else{
						this.e('Unexpected Error Occured')
					}
				}
			}
		},
		components:{
			orders,
			addressList,
			addAddress
		}
	}
</script>