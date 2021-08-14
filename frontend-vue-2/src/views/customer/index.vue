<template>
	<div>
	    <loading :active.sync="loadingContent" :is-full-page="true" />

	    <div v-if="!loadingContent"
	    	class="container">

	    	<div class="clearfix mb-3">
	    		<div class="float-left">
		    		Kelola Customer
	    		</div>


	    		<div class="float-right">
		    		<router-link to="/form/0" class="btn btn-primary">
	    				Tambah
		    		</router-link>
		    	</div>
		    </div>

			<table class="table table-hover mt-2">
				<tr>
					<td>Id</td>
					<td>Ktp</td>
					<td>Nama</td>
					<td>Contact</td>
					<td>Alamat</td>
					<td>Opsi</td>
				</tr>
				<tr v-for="(item,index) in theData">
					<td>{{item.id}}</td>
					<td>
						<img :src="item.ktp" style="width:200px;height:200px;object-fit:cover">
					</td>
					<td>{{item.nama}}</td>
					<td>{{item.contact}}</td>
					<td>{{item.alamat}}</td>
					<td>
						<router-link :to="'/form/'+item.id" class="btn btn-success">Edit</router-link>
						<button @click="onDelete(item.id)" class="btn btn-danger">
							Delete  
							<span v-if="loadingDelete">
								 . . .
							</span>
						</button>
					</td>
				</tr>
				<tr v-if="theData.length == 0">
					<td colspan="10" class="text-center">
						Data tidak ditemukan
					</td>
				</tr>
			</table>
		</div>
	</div>
</template>

<script>
export default{
	mounted(){
		document.title = "Customer";

		this.onLoad();
	},

	data(){
		return{
			loadingContent : true,
			loadingDelete : false,
			theData : {},			
		}
	},

	methods : {
		onLoad(){
			this.$axios.get("/customer")
			.then(res => {
				this.theData = res.data;				
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
		onDelete(id){
			if(!window.confirm('Anda yakin?')){
				return false;
			}

			if(this.loadingDelete){
				return false;
			}

			this.loadingDelete = true;

			this.$axios.delete("/customer/"+id)
			.then(res => {
				this.$toaster.success("Berhasil Menghapus Data");
				this.onLoad();
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
				this.loadingDelete = false;
			});
		}
	}
}
</script>