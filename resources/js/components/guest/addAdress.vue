<template>
	<div>
		    <Button type="info" @click="AddAdressModal=true">
		    	<Icon class="md-add"></Icon>
		   	New Address
			</Button>

		<Modal :mask-closable="false"	v-model="AddAdressModal" :closable="false">
			<div slot="header">Add Adress Modal</div>
			<div class="checkout-inner mb-1">
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
			<div slot="footer">
				<Button type="error" @click="AddAdressModal=false">
					Close
				</Button>
			</div>
		</Modal>
	</div>
</template>
<script>
	export default{
		data(){
			return {
				AddAdressModal:false,
				data:{
					phone_number:'',
					Address:'',
					additional_information:'',
					region:'',
					city:'',
				},
				isSaving:false
			}
		},
		methods:{
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
					//this.address.unshift(saveAdressRequest.data)
					//this.hasAdresses = true
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
		}
	}
</script>