<template>
	<div class="container-fluid">
		<div class="row justify-content-center">
			<div class="col-md-11">
						<p class="_title0">Categories <Button @click="addModal=true">Add Category <Icon type="md-add" /> </Button></p>
					
					
						<table class="table table-hover table-responsive-md">
						  <thead>
						    <tr class="bg-info">
						      <th scope="col">#</th>
						      <th scope="col">Category Name</th>
						      <th scope="col">Image</th>
						      <th scope="col">Created at</th>
						      <th scope="col">Action</th>
						    </tr>
						  </thead>
						  <tbody>
						    <tr v-for="(category, i) in categories" :key="i" v-if="categories.length">
						      <th scope="row">{{category.id}}</th>
						      <td>{{category.categoryName}}</td>
						      <td class="table_image">
						      	<img :src="`/category_images/${category.iconImage}`"/>
						      </td>						      
						      <td>{{category.created_at}}</td>
						      <td>
						      	<Button type="primary" size="small" @click="showEditModal(category, i)">Edit</Button>
						      	<Button type="error" size="small" @click="deleteCategory(category, i)" :loading="category.isDeleting">Delete</Button>
						      </td>
						    </tr>				    
						  </tbody>
						</table>
			</div>
		</div>

		<Modal
		        v-model="addModal"
		        title="Add Category Dialog box"
		        :mask-closable='false'
		        :closable="false"
		        >
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
		          action="/seller/categories/uploadImage"
		        >
		          <div style="padding: 20px 0">
		            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
		            <p>Click or drag files here to upload</p>
		          </div>
		        </Upload>
		        <div class="space">
		        	<div class="demo-upload-list" v-if="data.iconImage">
		        		<img :src="`/category_images/${data.iconImage}`">
		        		<div class="demo-upload-list-cover">
		        			<Icon type="ios-trash-outline" @click="deleteIconImage"></Icon>
		        		</div>
		        	</div>
		        </div>
		        <div class="space"></div>
		       <input v-model="data.categoryName" placeholder="Enter a category name" class="form-control" >
		        <div slot='footer'>
		        	<Button type="error" @click="addModal=false">Close</Button>
					<Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding..' : 'Add Category'}}</Button>
		        </div>
		</Modal>
		<Modal v-model="editModal" title="Edit category" :mask-closable="false" :closable="false">
		  <Input v-model="editData.categoryName" placeholder="Add category name" />
		  <div class="space"></div>
		  <Upload
		    v-show="isIconImageNew"
		    ref="editDataUploads"
		    type="drag"
		    :headers="{'x-csrf-token' : token, 'X-Requested-With' : 'XMLHttpRequest'}"
		    :on-success="handleSuccess"
		    :on-error="handleError"
		    :format="['jpg','jpeg','png']"
		    :max-size="2048"
		    :on-format-error="handleFormatError"
		    :on-exceeded-size="handleMaxSize"
		    action="/seller/categories/uploadImage"
		  >
		    <div style="padding: 20px 0">
		      <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
		      <p>Click or drag files here to upload</p>
		    </div>
		  </Upload>
		  <div class="demo-upload-list" v-if="editData.iconImage">
		    <img :src="`${editData.iconImage}`" />
		    <div class="demo-upload-list-cover">
		      <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
		    </div>
		  </div>

		  <div slot="footer">
		    <Button type="default" @click="closeEditModal">Close</Button>
		    <Button
		      type="primary"
		      @click="editCategory"
		      :disabled="isAdding"
		      :loading="isAdding"
		    >{{isAdding ? 'Editing..' : 'Edit Category'}}</Button>
		  </div>
		</Modal>


	</div>
</template>

<script>
	export default {
		data (){
			return {
				data: {					
					iconImage:'',
					categoryName:'',
				},
				addModal:false,
				isAdding:false,
				isEditingItem:false,
				categories:[],
				editModal : false,
				editData: {
				        iconImage: "",
				        categoryName: ""
				      },
			    index : -1, 
			    token: '',
			    isIconImageNew: false,
			    isEditingItem: false,
			}
		},		
		methods : {
			async addCategory(){
				if (this.data.categoryName.trim()=='') {
					return this.e('Category Name is required')
				}
				if (this.data.iconImage.trim()=='') {
					return this.e('Category image is required')
				}				
				const res = await this.callApi('post','/seller/categories/new', this.data)
				if (res.status===201) {					
					this.categories.unshift(res.data);
					this.s('Category Added successfully')
					this.addModal=false
					this.data.categoryName=''
					this.iconImage=''
					this.$refs.uploads.clearFiles();
				}else{
					if(res.status==422){
					if(res.data.errors.categoryName){
						this.e(res.data.errors.categoryName[0])
					}
					if(res.data.errors.iconImage){
						this.e(res.data.errors.iconImage[0])
					}
					
				}else{
					this.swr()
				}
				}
			},

			async editCategory() {
			  if (this.editData.categoryName.trim() == "")
			    return this.e("Category name is required");
			  if (this.editData.iconImage.trim() == "")
			    return this.e("Icon image is required");
			  const res = await this.callApi(
			    "post",
			    "seller/categories/edit_category",
			    this.editData
			  );
			  if (res.status === 200) {
			    this.categories[
			      this.index
			    ].categoryName = this.editData.categoryName;
			    this.s("Category has been edited successfully!");
			    this.editModal = false;
			  } else {
			    if (res.status == 422) {
			      if (res.data.errors.categoryName) {
			        this.e(res.data.errors.categoryName[0]);
			      }
			      if (res.data.errors.iconImage) {
			        this.e(res.data.errors.iconImage[0]);
			      }
			    } else {
			      this.swr();
			    }
			  }
			},
		showEditModal(category, index) {
		      this.editData = category;
		      this.editModal = true;
		      this.index = index;
		      this.isEditingItem = true;
		    },
		async deleteCategory(category, i)
		{
			if(!confirm('Are you sure you want to delete this Category?')) return
				 this.$set(category, 'isDeleting', true)
				const res = await this.callApi('delete', '/seller/categories/deleteCategories', category)
				if (res.status==200) {
					this.$Loading.finish()
					this.categories.splice(i,1)
					this.s('Category Deleted successfully')
				}
				else{
					 this.$set(category, 'isDeleting', false)
					this.e('Something Went Wrong')
				}
		},
		      handleSuccess (res, file) {
		                this.data.iconImage = res
		                this.s(res)
		            },

		            handleError(res, file)
		            {
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
		            async deleteIconImage()
		            {
		            	let image = this.data.iconImage
		            	this.data.iconImage = ''
		            	this.$refs.uploads.clearFiles();
		            	const res = await this.callApi('post', '/seller/categories/deleteImage', {imageName:image})
		            	if (res.status==200) {
		            		this.$Loading.finish()
		            		this.i('Image deleted from the server')		            		
		            	}else{
		            		this.data.iconImage = image	
		            		this.swr('Image deleting exited with status code 401')
		            	}
		            },
		            async deleteImage(isAdd = true) {
		              let image;
		              if (!isAdd) {
		                // for editing....
		                this.isIconImageNew = true;
		                image = this.editData.iconImage;
		                this.editData.iconImage = "";
		                this.$refs.editDataUploads.clearFiles();
		              } else {
		                image = this.data.iconImage;
		                this.data.iconImage = "";
		                this.$refs.uploads.clearFiles();
		              }

		              const res = await this.callApi("post", "app/delete_image", {
		                imageName: image
		              });
		              if (res.status != 200) {
		                this.data.iconImage = image;
		                this.swr();
		              }
		            },	
		            closeEditModal() {
		              this.isEditingItem = false;
		              this.editModal = false;
		            }		
			
		},
		async created(){
				this.token = window.Laravel.csrfToken;
				const res = await this.callApi('get','/seller/categories/all')
				if (res.status===200) {
					this.$Loading.finish()
					this.s('Load complete')
					this.categories = res.data
				}else{
					this.swr()
				}

			}
	}
</script>

<style>
    .demo-upload-list{
        display: inline-block;
        width: 120px;
        height: 120px;
        text-align: center;
        line-height: 60px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-right: 4px;
    }
    .demo-upload-list img{
        width: 100%;
        height: 100%;
    }
    .demo-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .demo-upload-list:hover .demo-upload-list-cover{
        display: block;
    }
    .demo-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
</style>