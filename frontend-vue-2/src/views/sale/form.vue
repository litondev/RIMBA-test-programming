<template>
	<div class="container mt-4">
		<loading :active.sync="loadingContent" :is-full-page="true" />

		<div class="clearfix mb-3">
			<div class="float-left">
				<span v-if="!isEdit">
					Tambahkan Sale
				</span>
				<span v-else>
					Edit Sale
				</span>
			</div>
			<div class="float-right">
				<router-link to="/sale">Kembali</router-link>
			</div>
		</div>

		<form @submit="onSubmit" id="sale-form">				
			<table class="table table-hover">
				<tr>
					<td>Kode Transaksi</td>
					<td>
						<input type="text" v-model="form.code_transaksi" class="form-control" name="code_transaksi">
					</td>
				</tr>
				<tr>
					<td>Customer</td>
					<td>
						<multi-select 
							v-model="form.customer_id" 
							:multiple="false"
							placeholder="Type to search"
							open-direction="bottom"
							:options="list_customers"							
							:searchable="true"
							:loading="loadingListCustomer"
							:internal-search="false"
							:clear-on-select="false"
							:close-on-select="true"
							:options-limit="20"
							:limit="3"
							:max-height="600"
							:show-no-results="false"
							:hide-selected="true"
							:custom-label="customLabelListCustomer"				
							@search-change="asyncFindCustomer"
							@select="onSelectCustomer">									
							<span slot="noResult">Oops! No Result</span>					
						</multi-select>		
					</td>
				</tr>
				<tr>
					<td>Item</td>
					<td>
						<multi-select 
							v-model="form.item" 
							:multiple="true"
							placeholder="Type to search"
							open-direction="bottom"
							:options="list_items"							
							:searchable="true"
							:loading="loadingListItem"
							:internal-search="false"
							:clear-on-select="false"
							:close-on-select="false"
							:options-limit="20"
							:limit="3"
							:max-height="600"
							:show-no-results="false"
							:hide-selected="true"
							:custom-label="customLabelListItem"				
							@search-change="asyncFindItem"
							@select="onSelectItem">									
							<span slot="noResult">Oops! No Result</span>					
						</multi-select>						
					</td>
				</tr>
				<tr>
					<td>Qty</td>
					<td><input type="text" v-model="form.qty" class="form-control" name="qty" @keyup="reQty"></td>
				</tr>
				<tr>
					<td>Total Diskon</td>
					<td>
						<input type="text" v-model="form.total_diskon" class="form-control" name="total_diskon" readonly>
					</td>
				</tr>
				<tr>
					<td>Total Harga</td>
					<td>
						<input type="text" v-model="form.total_harga" class="form-control" name="total_harga" readonly>
					</td>
				</tr>
				<tr>
					<td>Total Bayar</td>
					<td>
						<input type="text" v-model="form.total_bayar" class="form-control" name="total_bayar">
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
import multiSelect from "vue-multiselect";
import 'vue-multiselect/dist/vue-multiselect.min.css';

export default{
	components: {
		multiSelect
	},

	mounted(){
		if(this.$route.params.id != 0){
			document.title = "Sale Edit";					
			this.onLoad();		
		}else{
			document.title = "Sale Add";
		}
	},

	data(){
		return{
			isEdit : this.$route.params.id != 0 ? true : false,
			loadingContent : this.$route.params.id != 0 ? true : false,
			loadingSubmit : false,
			form : {
				code_transaksi : '',
				customer_id : [],
				item : [],
				qty : 1,
				total_diskon : 0,
				total_harga : 0,
				total_bayar : 0			
			},

			list_items : [],
			loadingListItem :  false,

			list_customers : [],
			loadingListCustomer : false
		}
	},

	methods : {
		reQty(){
			if(parseInt(this.form.qty) > 0){
				this.form.total_harga = 0;
				for(let i=0;i<this.form.item.length;i++){
					this.form.total_harga += this.form.qty * this.form.item[i].harga_satuan;
				}
			}
		},

		customLabelListItem({nama_item}){
			return nama_item;
		},

		asyncFindItem(query){		
			if(query.length){
				this.loadingListItem = true;

				this.$axios.get("/item?search="+query).then(res => {
					this.list_items = res.data;
				}).catch(err => {
					this.$toaster.error("Terjadi Kesalahan");			
				})
				.finally(() => {
					this.loadingListItem = false;
				});
			}
		},

		onSelectItem(option){		
			this.form.total_harga = 0;	

			if(!this.form.item.length){
				this.form.total_harga += this.form.qty * option.harga_satuan;
			}else{
				for(let i=0;i<this.form.item.length;i++){
					this.form.total_harga += this.form.qty * this.form.item[i].harga_satuan;
				}				
			}
		},

		asyncFindCustomer(query){
			if(query.length){
				this.loadingListCustomer = true;

				this.$axios.get("/customer?search="+query).then(res => {
					this.list_customers = res.data;
				}).catch(err => {
					this.$toaster.error("Terjadi Kesalahan");			
				})
				.finally(() => {
					this.loadingListCustomer = false;
				});
			}
		},

		onSelectCustomer(option){
			if(option.type_diskon != 'none'){
				this.form.total_diskon =  option.diskon;
			}
		},

		customLabelListCustomer({nama}){
			return nama;
		},


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
		},		
		onSubmit(evt){
			evt.preventDefault();

			if(this.loadingSubmit){
				return false;
			}

			this.loadingSubmit = true;
			
			let axios = '';

			if(!this.isEdit){				
				axios = this.$axios.post("/sale",this.form);
			}else{						
				axios = this.$axios.post("/sale/"+this.$route.params.id,{
					...this.form,
					"_method" : "PUT"
				});
			}

			axios.then(res => {
				if(!this.isEdit){
					this.$toaster.success("Berhasil Menambah Data");
					this.$router.push("/sale");
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
		}
	}
}
</script>