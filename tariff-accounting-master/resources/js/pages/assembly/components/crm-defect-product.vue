<template>
  	<div>
		<header id="el-drawer__title" class="el-drawer__header">
			<span title="Новый заказ">{{ $t('message.defect_product') }}   | {{ $t('message.assembly') }} № {{ assembly.id }}</span>
			<el-button v-can="['assemblies.defect_product']" :loading="loading" type="success" size="small" class="mr-1" @click="submit(false)"> {{ $t('message.save') }}</el-button>
			<el-button v-can="['assemblies.defect_product']" :loading="loading" type="primary" size="small" class="mr-1" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
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
					<el-col :span="12">
						<el-select
							@change="appendProduct"
							filterable
							size="mini"
							class="d-block"
							reserve-keyword
							:placeholder="$t('message.choose')"
							v-model="selectedAssemblyItem"
							>
							<el-option
								v-for="item in items"
								:key="'assembly_items'+item.product.id"
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
		props:['assembly','open'],
		components:{
			'crm-reason-drawer': AppendReason
		},
		data() {
			return {
				defect_products: [],
				selectedAssemblyItem: '',
				selectedDefectProduct: '',
				drawerReasons: false
			};
		},
		computed: {
			...mapGetters({
				money_product: 'money_product',
				old_defect_products: 'assembly/defect_products',
				assembly_items: 'assembly/assembly_items',
	            warehouses: 'warehouses/list',
			}),
			items: function() {
				return this.assembly_items.filter((item) => { return (item.product != null && !_.find(this.defect_products, ['assembly_item_id',item.id]))})
			}
		},
		mounted() {
			if (this.assembly_items && this.assembly_items.length === 0) this.loadAssemblyItems({assembly_id: (this.assembly) ?  this.assembly.id : null});
		},
	    methods: {
			...mapActions({
				loadAssemblyItems: 'assembly/getAssemblyItems',
				loadDefectProducts: 'assembly/getDefectProducts',
				defectStore : 'assembly/defectStore',
				deleteDefectProduct : 'assembly/deleteDefectProduct',
			}),
			afterOpen(){
				this.load();
			},
			load(){
				if (!this.loading && this.assembly) {
					const filter = {
						assembly_id: this.assembly.id
					};
					this.changeLoading(true);
					this.loadDefectProducts(filter)
					.then(res => {this.changeLoading()})
					.catch(err => {this.changeLoading()});
					this.loadAssemblyItems(filter)
				}
			},
			submit(close = true){
				let defect_products = this.defect_products.map((item) => {
					return {
						assembly_item_id: item.assembly_item_id,
						product_id: item.product.id,
						quantity: item.quantity,
						date: item.date,
						reasons: item.reasons.map((reason) => {
							return {
								reason_id: reason.reason.id,
								quantity: reason.quantity
							}
						})
					}
				});
				if (defect_products.length > 0) {
					this.defectStore({defect_products: defect_products})
					.then(res => {
						this.$alert(res);
						this.load();
						this.defect_products = [];
						if (close === true) {
							this.close();
						}
					})
					.catch(err => {
						this.$alert(err);
					})
				}
			},
			destroy(model){
					this.deleteDefectProduct({id: model.id})
					.then(res => {
						this.$alert(res)
						this.load()
					})
					.catch(err => {
						this.$alert(err)
					})
			},
			appendProduct(){
                let defect_product = {};
                defect_product.assembly_item_id = this.selectedAssemblyItem.id;
                defect_product.product = this.selectedAssemblyItem.product;
				defect_product.quantity = this.selectedAssemblyItem.quantity;
			 	defect_product.date = new Date();
				defect_product.reasons = [];
                this.defect_products.push(defect_product);
                this.selectedAssemblyItem = '';
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
