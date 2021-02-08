<template>
	 <div class="row">
		 <div class="col-md-6 mb-1"   v-for="(adres, a) in address" :key="adres.id">
		    <h5>Adress {{adres.id}}</h5>
		    <p>{{adres.Address}},  {{adres.city}}, {{adres.region}}</p>
		    <p>Mobile: {{adres.phone_number}}</p>
		    <p v-html="adres.additional_information"></p>
		</div>
	</div>
</template>
<script>
	export default{
		data(){
			return {								
				address:[],
			}
		},		
		async created()
		{	//get default address
			this.start()
			const res = await this.callApi('get','/customer/product/account_data')
			if (res.status==200) {
				this.address = res.data				
				this.stop()
			}
			else{
				if (res.status == 404) {					
					this.stop()
				}else{
					this.showError()
				}
			}
			
		}
	}
</script>