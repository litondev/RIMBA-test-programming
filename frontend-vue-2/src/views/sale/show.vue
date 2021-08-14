<template>
	<div class="container mt-4">
		<loading :active.sync="loadingContent" :is-full-page="true" />

		<div class="clearfix mb-3">
			<div class="float-left">
				Detail Sale
			</div>
			<div class="float-right">
				<router-link to="/sale">Kembali</router-link>
			</div>
		</div>

		<table class="table table-hover">
			<tr>
				<td>Tanggal Transaksi</td>
				<td>{{form.tanggal_transaksi}}</td>
			</tr>
			<tr>
				<td>Kode Transaksi</td>
				<td>
					<input type="text" v-model="form.code_transaksi" class="form-control">
				</td>
			</tr>
			<tr>
				<td>Customer</td>
				<td>
					<input type="text" v-model="form.customer.nama" class="form-control">
				</td>
			</tr>
			<tr>
				<td>Item</td>
				<td>
					<span v-for="item in form.item_sales"
						class="badge badge-success">
						{{item.item.nama_item}}
					</span>				
				</td>
			</tr>
			<tr>
				<td>Qty</td>
				<td><input type="text" v-model="form.qty" class="form-control"></td>
			</tr>
			<tr>
				<td>Total Diskon</td>
				<td>
					<input type="text" v-model="form.total_diskon" class="form-control">
				</td>
			</tr>
			<tr>
				<td>Total Harga</td>
				<td>
					<input type="text" v-model="form.total_harga" class="form-control">
				</td>
			</tr>
			<tr>
				<td>Total Bayar</td>
				<td>
					<input type="text" v-model="form.total_bayar" class="form-control">
				</td>
			</tr>				
		</table>		
	</div>	
</template>

<script>
export default{
	mounted(){	
		document.title = "Sale Detail";					
		this.onLoad();	
	},

	data(){
		return{	
			loadingContent : true,
			form : {
				code_transaksi : '',							
				qty : 1,
				total_diskon : 0,
				total_harga : 0,
				total_bayar : 0,
				item_sales : [],
				customer : {}
			},
		}
	},

	methods : {		
		onLoad(){
			this.$axios.get("/sale/"+this.$route.params.id)
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
		}	
	}
}
</script>