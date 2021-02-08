<template>
	<div class="container-fluid">
		<div class="card">
			<div class="card-header">
				<h3 class="float-left text-info font-weight-bold">PRODUCTS</h3>
				<button @click="newProductModal=true" class="btn btn-info float-right"><Icon type=md-add> </Icon>New Product</button>
			</div>
			<div class="card-body">
				<table class="table table-hover table-responsive-md">
				  <thead>
				    <tr class="bg-info">
				      <th scope="col">#</th>
				      <th scope="col">Product Name</th>
				      <th scope="col">Year</th>
				      <th scope="col">Category</th>
				      <th scope="col">Status</th>
				      <th scope="col">Created at</th>
				      <th scope="col">Action</th>
				    </tr>
				  </thead>
				  <tbody>
				    <tr v-for="(product, p) in products" :key="product.id" v-if="products.length">
				      <td>{{product.id}}</td>
				      <td>{{product.name}}</td>
				      <td>{{product.year_of_manufacture}}</td>
				      <td>{{product.category}}</td>
				      <td>{{product.status}}</td>					      
				      <td>{{product.created_at}}</td>
				      <td>
				      	<Button type="primary" size="small" @click="showEditModal(product, p)">Edit</Button>
				      	<router-link :to="'/customer/product/description/'+product.id">
				      	<Button type="primary" size="small">Description</Button>
				      </router-link>
				      	<Button type="error" size="small" @click="deleteProduct(product, i)" :loading="product.isDeleting">Delete
				      	</Button>
				      	<Button v-if="!product.featured" :loading="product.isChanging" :disabled="product.isChanging" type="info" @click="makeFeatured(product, p)" size="small">
				      		{{product.isChanging ? 'Featuring...' : 'Feature'}}
				      	</Button>
				      	<Button v-else type="info" :loading="product.isChanging" :disabled="product.isChanging" @click="makeFeatured(product, p)" size="small">
				      		{{product.isChanging ? 'Unfeaturing..' : 'Unfeature'}}
				      	</Button>

				      </td>
				    </tr>				    
				  </tbody>
				</table>
				<br>
				<Modal
				        v-model="newProductModal"				        
				        :mask-closable='false'
				        :closable="false"
				        :fullscreen="true"
				        >
				        <div slot="header">
				        	<p class="text-center text-success">Fill the form Below to add a product</p>
				        	<button class="float-right btn btn-danger btn-sm" @click="newProductModal=false">
				        		<span>&times;</span>
				        	</button>
				        </div>
				        
				        <div class="row justify-content-center mb-3">
				        	<div class="col-md-8">
				        		<Upload
				        		  ref="uploads"
				        		  type="drag"
				        		  :headers="{'x-csrf-token' : token , 'X-Requested-With' : 'XMLHttpRequest'}"
				        		  :on-success="handleSuccess"
				        		  :on-error="handleError"
				        		  :format="['jpg','jpeg','png']"
				        		  :max-size="2048"
				        		  :on-format-error="handleFormatError"
				        		  :on-exceeded-size="handleMaxSize"	          
				        		  action="/seller/products/uploadImage"
				        		>
				        		  <div style="padding: 20px 0">
				        		    <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
				        		    <p>Click or drag files here to upload</p>
				        		  </div>
				        		</Upload>
				        		<span class="text-danger">Product Images once saved Cant be modified.</span>
				        		<div class="space">
				        			<div class="demo-upload-list" v-if="data.image_url">
				        				<img :src="`/product_images/${data.image_url}`">
				        				<div class="demo-upload-list-cover">
				        					<Icon type="ios-trash-outline" @click="deleteImage"></Icon>
				        				</div>
				        			</div>
				        		</div>
				        	</div>
				        </div>

				        <div class="row justify-content-center">
				        	<div class="col-md-8">
				        		<Form :model="data" label-position="left" :label-width="100">
				        		        <FormItem label="Product Name">
				        		            <Input v-model="data.name"></Input>
				        		        </FormItem>
				        		        <FormItem label="Year Of Manufacture">
				        		            <Input v-model="data.year_of_manufacture"></Input>
				        		        </FormItem>

				        		        <FormItem label="Selling Price">
				        		            <Input v-model="data.price"></Input>
				        		        </FormItem>

				        		        <FormItem label="Country of Make">
				        		            <Input v-model="data.country"></Input>
				        		        </FormItem>

				        		        <FormItem label="Manufacturer">
				        		            <Input v-model="data.manufacturer"></Input>
				        		        </FormItem>

				        		        <FormItem label="Color">
				        		            <Input v-model="data.color"></Input>
				        		        </FormItem>

				        		        <FormItem label="Size">
				        		            <Input v-model="data.size"></Input>
				        		        </FormItem>

				        		        <FormItem label="Category">
				        		            <select v-model="data.category_id" style="width:100%">
				        		            	<option value="">Select Category</option>
				        		            	<option v-for="(category, c) in categories" :value="category.id" :key="category.id">{{ category.id }} {{ category.categoryName }}
				        		            	</option>
				        		               </select>
				        		        </FormItem>				                

				        		        <FormItem label="Description">
				        		            <Input v-model="data.description" type="textarea" :autosize="{minRows: 5,maxRows: 10}" placeholder="Enter Product Description">	      	
				        		            </Input>
				        		        </FormItem>    	     
				        		    </Form>
				        	</div>
				        </div>
				            
				            			        
				       
				        <div slot='footer'>
				        	<Button type="error" @click="newProductModal=false">Close</Button>
							<Button type="primary" @click="addProduct" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Saving..' : 'Add Product'}}</Button>
				        </div>
				</Modal>
			</div>
		</div>
	</div>
</template>
<script>
	export default{
		data(){
			return {
				data: {
					image_url:'',
					name:'',
					year_of_manufacture:'',
					price:'',
					country:'',
					manufacturer:'',
					color:'',
					size:'',
					category_id:'',
					description:'',
				},
				categories:[],
				products:[],
				newProductModal:false,
				isAdding:false,
				token:'',
				isChanging:false,
				index:-1
				}
		},
		methods: {
			showEditModal(){

			},
			async deleteProduct(product, index){

				if(!confirm('Are you sure you want to delete this Product?')) return
					 this.$set(product, 'isDeleting', true)
					const deleteProductRequest = await this.callApi('delete', '/seller/products/deleteProduct', product)
					if (deleteProductRequest.status==200) {						
						this.products.splice(index,1)
						this.s('Product Deleted successfully')
					}
					else{
						this.$set(product, 'isDeleting', false)
						this.e('Something Went Wrong')
					}
			},
			async makeFeatured(product, index){
				this.$set(product, 'isChanging', true)
				this.index = index
				const swapRequest = await this.callApi('patch','/seller/products/makeFeatured', product)
				if (swapRequest.status == 200) {
					this.products[
					  this.index
					].featured = swapRequest.data.featured
				this.$set(product, 'isChanging', false)
				this.s('Product Featured State Updated');
				}
				else{
				this.e('Unknown Error Occured. Product Not Altered.')
				this.$set(product, 'isChanging', false)

				}

			},
			async addProduct(){
				console.log(this.data)
				//validate data
				this.isAdding = true
				if (this.data.name.trim()=='') {
					this.isAdding = false
					return this.e('Product Name is required')
				}
				if (this.data.image_url.trim()=='') {
					this.isAdding = false
					return this.e('Image Is Required')
				}
				if (this.data.year_of_manufacture.trim()=='') {
					this.isAdding = false
					return this.e('Product Year of Manufacture is Required')
				}
				if (this.data.price.trim()=='') {
					this.isAdding = false
					return this.e('Price is Required')
				}
				if (this.data.country.trim()=='') {
					this.isAdding = false
					return this.e('Country Is Required')
				}
				if (this.data.manufacturer.trim()=='') {
					this.isAdding = false
					return this.e('Manufacturer is required')
				}
				if (this.data.color.trim()=='') {
					this.isAdding = false
					return this.e('Enter a valid Color')
				}
				if (this.data.size.trim()=='') {
					this.isAdding = false
					return this.e('Enter Product Unit Size, EG Large, XL XS')
				}
				if (this.data.category_id=='') {
					this.isAdding = false
					return this.e('Select a valid Category')
				}
				if (this.data.description.trim()=='') {
					this.isAdding = false
					return this.e('Enter a valid product Description')
				}

				this.start()
				const createProduct = await this.callApi('post', '/seller/products/new', this.data)
				 if (createProduct.status == 201) {
				 	this.stop()
				 	this.data=''
				 	this.s('Product Added Successfully')
				 	this.isAdding = false				 	
				 	this.products.unshift(createProduct.data.data)
				 	this.newProductModal=false
				 }else{
				 	this.showError()				 	
				 	if (createProduct.status==422) {
				 		this.isAdding=false
				 		for (let i in createProduct.data.errors) {
								this.e(createProduct.data.errors[i][0])
						}
				 	}
				 	else{
				 		this.swr('Please Try Again')
				 		this.isAdding=false
				 	}
				 }

			},
			async deleteImage(isAdd = true) {
			  let image;			  
			   image = this.data.image_url;
			   this.data.image_url = "";
			    this.$refs.uploads.clearFiles();
			  const res = await this.callApi("post", "/seller/products/deleteImage", {
			    imageName: image
			  });
			  if (res.status != 200) {
			    this.data.image_url = image;
			    this.swr();
			  }
			},
			handleSuccess (res, file){
				this.data.image_url = res
				this.s(res)
			},
			handleError(res, file){
				this.$Notice.warning({
				title: 'Invalid file',
				desc: `${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
				})
			},
			handleFormatError (file) {
				this.$Notice.warning({
				title: 'The file format is incorrect',
				desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
				});
			},
			handleMaxSize (file) {
				this.$Notice.warning({
				title: 'Exceeding file size limit',
				desc: 'File ' + file.name + ' is too large, no more than 2M.'
				});
			},

		},
		async created(){
			this.token = window.Laravel.csrfToken;
			this.start()
			const res = await this.callApi('get','/seller/products/all')
			if (res.status == 200) {
				this.products = res.data.data
				this.stop()
			}else{
				this.showError();
			}

			this.start()
			const fetchCategories = await this.callApi('get','/seller/categories/all')
			if (fetchCategories.status == 200) {
				this.categories = fetchCategories.data
				this.stop()
			}else{
				this.showError();
			}
		}
	}
</script>