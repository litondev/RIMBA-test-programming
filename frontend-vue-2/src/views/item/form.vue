<template>
	<div class="container mt-4">
		<loading :active.sync="loadingContent" :is-full-page="true" />

		<div class="clearfix mb-3">
			<div class="float-left">
				<span v-if="!isEdit">
					Tambahkan Item
				</span>
				<span v-else>
					Edit Item
				</span>
			</div>
			<div class="float-right">
				<router-link to="/item">Kembali</router-link>
			</div>
		</div>

		<form @submit="onSubmit" id="item-form">				
			<table class="table table-hover">
				<tr>
					<td>Nama Item</td>
					<td><input type="text" v-model="form.nama_item" class="form-control" name="nama_item"></td>
				</tr>
				<tr>
					<td>Unit</td>
					<td>
						<select v-model="form.unit" class="form-control" name="unit">					
							<option value="kg">Kg</option>
							<option value="pcs">Pcs</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Stock</td>
					<td><input type="text" v-model="form.stock" class="form-control" name="stock"></td>
				</tr>
				<tr>
					<td>Harga Satuan</td>
					<td><input type="text" v-model="form.harga_satuan" class="form-control" name="harga_satuan"></td>
				</tr>	
				<tr>
					<td>Barang</td>
					<td>
						<input type="file" class="form-control" @change="onImageChange" name="barang">

						<div v-if="isEdit" class="mt-3">
							<img :src="this.form.barang" style="width:200px;height:200px;object-fit:cover"/>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<button class="btn btn-success">
							<span v-if="loadingSubmit">
								... 
							</span>
							<span v-else>
								Kirim
							</span>
						</button>
					</td>
				</tr>
			</table>
		</form>
	</div>	
</template>

<script>
export default{
	mounted(){
		if(this.$route.params.id != 0){
			document.title = "Item Edit";					
			this.onLoad();		
		}else{
			document.title = "Item Add";
		}
	},

	data(){
		return{
			isEdit : this.$route.params.id != 0 ? true : false,
			loadingContent : this.$route.params.id != 0 ? true : false,
			loadingSubmit : false,
			form : {
				nama_item : '',
				unit : 'kg',
				stock : 0,
				harga_satuan : 0,
				barang : ''
			}
		}
	},

	methods : {
		onLoad(){
			this.$axios.get("/item/"+this.$route.params.id)
			.then(res => {
				this.form = res.data;
				this.loadingContent = false;   
			})
			.catch(err => {
				if(err.response && err.response.status == 422){
					this.$toaster.error(err.response.data.message);    			
				}else if(err.response && err.response.status == 500){
					this.$toaster.error(err.response.data.message);
				}else{
					this.$toaster.error("Terjadi Kesalahan");
				}
			});
		},		
		onSubmit(evt){
			evt.preventDefault();

			if(this.loadingSubmit){
				return false;
			}

			this.loadingSubmit = true;
			
			let axios = '';

			if(!this.isEdit){
				axios = this.$axios.post("/item",new FormData(document.getElementById("item-form")));
			}else{
				let formData = new FormData(document.getElementById("item-form"));
				formData.append("_method","PUT");
				axios = this.$axios.post("/item/"+this.$route.params.id,formData);
			}

			axios.then(res => {
				if(!this.isEdit){
					this.$toaster.success("Berhasil Menambah Data");
					this.$router.push("/item");
				}else{
					this.$toaster.success("Berhasil Edit Data");
					this.onLoad();
				}				
			})
			.catch(err => {
				if(err.response && err.response.status == 422){
					this.$toaster.error(err.response.data.message);    			
				}else if(err.response && err.response.status == 500){
					this.$toaster.error(err.response.data.message);
				}else{
					this.$toaster.error("Terjadi Kesalahan");
				}
			})
			.finally(() => {
				this.loadingSubmit = false;
			});
		},
		onImageChange(evt){
			if(evt.target.files[0]){
				if(!['image/png','image/jpg','image/jpeg'].includes(evt.target.files[0].type)){
					this.$toaster.error("Gambar tidak valid");	
					evt.target.value = '';
					return false;
				}
				// this.form.ktp = evt.target.files[0];
				console.log("Upload Oke");
			}	
		}
	}
}
</script>