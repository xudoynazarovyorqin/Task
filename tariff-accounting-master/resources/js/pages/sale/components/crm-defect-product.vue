<template>
  	<div>
		<header id="el-drawer__title" class="el-drawer__header">
			<span>{{ $t('message.defect_product') }}   | {{ $t('message.sale') }} № {{ sale.id }}</span>
			<el-button v-can="['sales.defect_product']" :loading="loading" type="success" size="small" class="mr-1" @click="submit(false)"> {{ $t('message.save') }}</el-button>
			<el-button v-can="['sales.defect_product']" :loading="loading" type="primary" size="small" class="mr-1" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
			<el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
		</header>
		<el-main :loading="loading">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					<span> {{ $t('message.defect_product') }} </span>
				</div>
				<el-table :data="[...old_defect_products,...defect_products]">
					<el-table-column :label="$t('message.name')">
						<template slot-scope="item">
							{{ (item.row.product ? item.row.product.name  : '')  | truncate}}
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.quantity')">
						<template slot-scope="item">
							<template v-if="item.row.id">
								{{ item.row.quantity | formatNumber }}
							</template>
							<template v-else>
								<el-input-number v-model="item.row.quantity" size="mini"></el-input-number>
							</template>
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.measurement')">
						<template slot-scope="item">
							{{ (item.row.product) ? (item.row.product.measurement)  ? item.row.product.measurement.name   : '' : '' }}
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.datetime')">
						<template slot-scope="item">
							<template v-if="item.row.id">
								{{ item.row.date }}
							</template>
							<template v-else>
								<el-date-picker
									v-model="item.row.date"
									size="mini"
									:format="date_format"
									:value-format="date_format"
								></el-date-picker>
							</template>
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.reason')">
						<template slot-scope="item">
							<template v-if="item.row.id"></template>
							<template v-else>
								<el-button size="mini" type="primary" @click="openReasons(item.row)">{{ $t('message.reasons')}}</el-button>
							</template>
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.datetime')">
						<template slot-scope="item">
							<el-button @click="item.row.id ? destroy(item.row) : removeItem(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
						</template>
					</el-table-column>
				</el-table>
				<el-row>
					<el-col :span="12" class="mt-2">
						<el-select
							@change="appendProduct"
							filterable
							size="mini"
							reserve-keyword
							class="d-block"
							:placeholder="$t('message.choose')"
							v-model="selectedSaleProduct"
							>
							<el-option
								v-for="item in items"
								:key="'sale_products'+item.product.id"
								:label="item.product.name + '  | Кол ' + item.quantity"
								:value="item"
							></el-option>
							</el-select>
					</el-col>
				</el-row>
			</el-card>
		</el-main>
		<el-drawer
			:title="$t('message.reasons')"
			:append-to-body="true"
			:visible.sync="drawerReasons"
			:with-header="false"
			size="60%">
	    	<crm-reason-drawer  @close="responseReasonDrawer" :defect_product="selectedDefectProduct"></crm-reason-drawer>
    	</el-drawer>
	</div>
</template>
<script>
	import { mapGetters, mapActions } from "vuex";
	import AppendReason from '@/includes/crm-defect-product-reason';
	import drawerMixin from '@/utils/mixins/includes/drawer';

    export default {
		mixins: [drawerMixin],
		props:['sale','open'],
		components:{
			  'crm-reason-drawer': AppendReason
		},
		data() {
			return {
				loading: false,
				defect_products: [],
				selectedSaleProduct: '',
				selectedDefectProduct: '',
				drawerReasons: false
			};
		},
		computed: {
			...mapGetters({
				money_product: 'money_product',
				old_defect_products: 'sales/defect_products',
				sale_products: 'sales/sale_products',
			}),
			items: function() {
				return this.sale_products.filter((item) => { return (item.product != null && !_.find(this.defect_products, ['sale_product_id',item.id]))})
			}
		},
	    methods: {
			...mapActions({
				loadSaleProducts: 'sales/getSaleProducts',
				loadDefectProducts: 'sales/getDefectProducts',
				defectStore : 'sales/defectStore',
				deleteDefectProduct : 'assembly/deleteDefectProduct',
			}),
			afterOpen(){
				this.defect_products = [];
				this.load();
			},
			load(){
				if (!this.loading && this.sale) {
					const filter = {
						sale_id: this.sale.id
					};
					this.changeLoading(true)
					this.loadDefectProducts(filter)
					.then(res => { this.changeLoading() })
					.catch(err => {this.changeLoading()})

					this.loadSaleProducts(filter);
				}
			},
			submit(close = true){
				let defect_products = this.defect_products.map((item) => {
					return {
						sale_product_id: item.sale_product_id,
						product_id: item.product.id,
						quantity: item.quantity,
						date: item.date,
						reasons: item.reasons.map((reason) =>{
							return {
								reason_id: reason.reason.id,
								quantity: reason.quantity
							}
						})
					}
				});
				if (!_.find(this.defect_products, function(o) { return (o.quantity <= 0)})) {
					this.defectStore({defect_products: defect_products})
					.then(res => {
						this.$alert(res);
						this.load();
						this.defect_products = [];
						if (close === true) {
							this.close()
						}
					})
					.catch(err => {
						this.$alert(res);
					})
				}else{
					this.$message({
						message: this.$t('message.product_validation_error'),
						type: 'warning'
                	});
				}
			},
			destroy(model){
				 this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
                    confirmButtonText: this.$t('message.yes'),
                    cancelButtonText: this.$t('message.cancel'),
                    type: 'warning'
                }).then(() => {
					this.deleteDefectProduct({id: model.id})
					.then(res => {
						this.$alert(res)
						this.load()
					})
					.catch(err => {
						this.$alert(err)
					})
				}).catch(() => {});
			},
			appendProduct(){
                let defect_product = {};
                defect_product.sale_product_id = this.selectedSaleProduct.id;
                defect_product.product = this.selectedSaleProduct.product;
				defect_product.quantity = 0;
			 	defect_product.date = new Date();
				defect_product.reasons = [];
                this.defect_products.push(defect_product);
                this.selectedSaleProduct = '';
			},
			removeItem(line){
				if (this.defect_products.length > 0) {
					this.defect_products.splice(this.defect_products.indexOf(line),1)
				}
			},
			openReasons(defect_product){
				this.selectedDefectProduct = defect_product;
				this.drawerReasons = true;
			},
			responseReasonDrawer(obj){
				this[obj.drawer] = false
				this.selectedDefectProduct.reasons = obj.reasons ? obj.reasons : []
			},
			afterLeave(){
				this.defect_products = []
			}
      }
    };
</script>
