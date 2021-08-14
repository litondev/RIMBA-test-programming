<template>
	<div class="container mt-4">
		<loading :active.sync="loadingContent" :is-full-page="true" />

		<div class="clearfix mb-3">
			<div class="float-left">
				<span v-if="!isEdit">
					Tambahkan Customer
				</span>
				<span v-else>
					Edit Customer
				</span>
			</div>
			<div class="float-right">
				<router-link to="/">Kembali</router-link>
			</div>
		</div>

		<form @submit="onSubmit" id="customer-form">				
			<table class="table table-hover">
				<tr>
					<td>Nama</td>
					<td><input type="text" v-model="form.nama" class="form-control" name="nama"></td>
				</tr>
				<tr>
					<td>Contact</td>
					<td><input type="text" v-model="form.contact" class="form-control" name="contact"></td>
				</tr>
				<tr>
					<td>Alamat</td>
					<td><textarea v-model="form.alamat" class="form-control" name="alamat"></textarea></td>
				</tr>
				<tr>
					<td>Diskon</td>
					<td><input type="text" v-model="form.diskon" class="form-control" name="diskon"></td>
				</tr>
				<tr>
					<td>Type Diskon</td>
					<td>
						<select v-model="form.type_diskon" class="form-control" name="type_diskon">
							<option value="none">Tidak Ada Diskon</option>
							<option value="percent">Persent</option>
							<option value="fix">Nominal</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Ktp</td>
					<td>
						<input type="file" class="form-control" @change="onImageChange" name="ktp">

						<div v-if="isEdit" class="mt-3">
							<img :src="this.form.ktp" style="width:200px;height:200px;object-fit:cover"/>
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
			document.title = "Customer Edit";					
			this.onLoad();		
		}else{
			document.title = "Customer Add";
		}
	},

	data(){
		return{
			isEdit : this.$route.params.id != 0 ? true : false,
			loadingContent : this.$route.params.id != 0 ? true : false,
			loadingSubmit : false,
			form : {
				nama : '',
				contact : '',
				alamat : '',
				diskon : '',
				type_diskon : 'none',
				ktp : ''
			}
		}
	},

	methods : {
		onLoad(){
			this.$axios.get("/customer/"+this.$route.params.id)
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
				axios = this.$axios.post("/customer",new FormData(document.getElementById("customer-form")));
			}else{
				let formData = new FormData(document.getElementById("customer-form"));
				formData.append("_method","PUT");
				axios = this.$axios.post("/customer/"+this.$route.params.id,formData);
			}

			axios.then(res => {
				if(!this.isEdit){
					this.$toaster.success("Berhasil Menambah Data");
					this.$router.push("/");
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