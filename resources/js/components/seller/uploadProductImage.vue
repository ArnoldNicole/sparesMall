<template>
 <div class="container">
     <Upload
		ref="ImagePhotoUploads"
		type="drag"
		:headers="{'x-csrf-token' : token , 'X-Requested-With' : 'XMLHttpRequest'}"
		:on-success="handleSuccess"
		:on-error="handleError"
		:format="['jpg','jpeg','png']"
		:max-size="1024"
		:on-format-error="handleFormatError"
		:on-exceeded-size="handleMaxSize"	          
		action="/seller/product_images/upload"							
	>
    <div style="padding: 20px 0">
		<Icon type="ios-cloud-upload" size="26" style="color: #3399ff"></Icon>
			<p>Click or drag files here to upload</p>
	</div>
    </Upload>
	<div class="demo-upload-list" v-if="data.image_url!=''">
		<img :src="`/product_photos/${data.image_url}`">
		<div class="demo-upload-list-cover">
			<Icon type="ios-trash-outline" @click="deleteImage"></Icon>
		</div>
	</div>
 </div>
</template>
<script>
export default {
    data(){
        return {
            data:{
                image_url:this.image,
            }
        }
    },
    props:['token','image'],
    methods:{
        async deleteImage() {
			 if( this.data.image_url===''){
                  this.$refs.ImagePhotoUploads.clearFiles();
             }else{
                let image;			  
                image = this.data.image_url;
                this.data.image_url = "";                
                const res = await this.callApi("post", "/seller/product_images/deleteImage", {imageName: image});
                if (res.status == 200) {
                    this.$refs.ImagePhotoUploads.clearFiles();                    
                }else{
                   this.data.image_url = image;
                   this.swr();
                }
             }
		},
        handleSuccess (res){
			this.data.image_url = res
            //emit the event to parent.
            this.$emit('image_uploaded', res)
			this.s(res)
		},
		handleError(file){
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
    }
}
</script>