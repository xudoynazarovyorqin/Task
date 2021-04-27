<template>
  	<div>
		<header id="el-drawer__title" class="el-drawer__header">
			<span>{{ $t('message.ready_products') }}   | {{ $t('message.sale') }} № {{ sale.id }}</span>
			<el-button v-can="['sales.manufactured']" type="success" size="small" class="mr-1" :loading="loading" @click="submit(false)"> {{ $t('message.save') }}</el-button>
			<el-button v-can="['sales.manufactured']" type="primary" size="small" class="mr-1" :loading="loading" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
			<el-button type="warning" @click="close()" icon="el-icon-close" size="small">{{ $t('message.close') }}</el-button>
		</header>
		<el-main v-loading="loading">
			<el-card class="box-card">
				<div slot="header" class="clearfix">
					<span> {{ $t('message.history') }} </span>
				</div>
				<el-table :data="old_manufactured_products" class="crm-el-table">
					<template slot="empty"><span></span></template>
					<el-table-column :label="$t('message.name')">
						<template slot-scope="item">
							{{ (item.row.product ? item.row.product.name  : '')  | truncate}}
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.warehouse')">
						<template slot-scope="item">
							{{ (item.row.warehouse ? item.row.warehouse.name  : '')  | truncate}}
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.quantity')">
						<template slot-scope="item">
							{{ item.row.receive | formatNumber }}
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.measurement')">
						<template slot-scope="item">
							{{ (item.row.product) ? (item.row.product.measurement)  ? item.row.product.measurement.name   : '' : '' }}
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.selling_price')">
						<template slot-scope="item">
							{{ item.row.selling_price | formatNumber(2) }} {{ item.row.currency ? item.row.currency.symbol : ''}}
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.rate')">
						<template slot-scope="item">
							<crm-rate :row="item.row"></crm-rate>
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.datetime')">
						<template slot-scope="item">
							{{ item.row.created_at }}
						</template>
					</el-table-column>
				</el-table>
			</el-card>
			<el-card class="box-card mt-2">
				<div slot="header" class="clearfix">
					<span> {{ $t('message.ready_products') }} </span>
				</div>
				<el-table :data="manufactured_products" class="crm-el-table">
					<template slot="empty"><span></span></template>
					<el-table-column :label="$t('message.name')">
						<template slot-scope="item">
							{{ (item.row.product ? item.row.product.name  : '')  | truncate}}
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.warehouse')">
						<template slot-scope="item">
			                <warehouses v-model="item.row.warehouse_id" :type_id="item.row.warehouse_type_id" size="mini"></warehouses>
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.not_enough')">
						<template slot-scope="item">
							{{ item.row.not_produced | formatNumber}}
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.quantity')">
						<template slot-scope="item">
 	 						<el-input-number v-model="item.row.quantity" size="mini" :min="0" :max="item.row.not_produced"></el-input-number>
						</template>
					</el-table-column>
					<el-table-column :label="$t('message.measurement')">
						<template slot-scope="item">
							{{ (item.row.product) ? (item.row.product.measurement)  ? item.row.product.measurement.name   : '' : '' }}
						</template>
					</el-table-column>
                        <el-table-column :label="$t('message.selling_price')">
                            <template slot-scope="item">
                                <product-price v-model="item.row.selling_price" :old="item.row.selling_price" size="mini"></product-price>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.currency')">
                            <template slot-scope="item">
                                <currencies size="mini" v-model="item.row.currency_id" :currency_id="item.row.currency_id" @c-change="updateCurrency(item.row)"></currencies>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.rate')">
                            <template slot-scope="item">
                                <el-input v-model="item.row.rate" :hidden="item.row.currency && item.row.currency.active" type="number" size="mini"></el-input>
                            </template>
                        </el-table-column>
					<el-table-column :label="$t('message.delete')">
						<template slot-scope="item">
							<el-button @click="removeProduct(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
						</template>
					</el-table-column>
				</el-table>
				<el-row class="mt-2">
					<el-col :span="12">
						<el-select
							@change="appendProduct"
							v-model="selectedSaleProduct"
							filterable
							size="mini"
							class="d-block"
							reserve-keyword
							:placeholder="$t('message.product_select_plc')">
							<el-option
								v-for="(item,index) in items"
								:key="index+'-sale_products'"
								:label="item.product ? item.product.name : ''"
								:value="item">
							</el-option>
						</el-select>
					</el-col>
				</el-row>
			</el-card>
		</el-main>
	</div>
</template>
<script>
	import { mapGetters, mapActions } from "vuex";
	import warehouses from '@inventory/crm-warehouse-select';
	import drawerMixin from '@mixins/includes/drawer';
	import currencies from '@inventory/crm-currency-select';
    import ProductPrice from '@inputs/crm-product-price-input';

	export default {
		mixins: [drawerMixin],
		props:['sale','open'],
		components: {warehouses,currencies,ProductPrice},
		data() {
			return {
				manufactured_products: [],
				selectedSaleProduct: '',                
			};
		},
		computed: {
			...mapGetters({
				money_product: 'money_product',
				old_manufactured_products: 'sales/manufactured_products',
				sale_products: 'sales/sale_products',
				currencies: 'currencies/inventory',
			}),
			items: function() {
				return this.sale_products.filter((item) => { return (item.product != null && (item.quantity - item.ready > 0) && !_.find(this.manufactured_products, ['sale_product_id',item.id]))})
			}
		},
		methods: {
			...mapActions({
				loadManufacturedProducts: 'sales/getManufacturedProducts',
				loadSaleProducts: 'sales/getSaleProducts',
				manufacturedStore: 'sales/manufacturedStore'
			}),
			afterOpen(){
				this.manufactured_products = [];
				this.load();
			},
			async load(){
				if (!this.loading && this.sale) {
					const filter = {sale_id: this.sale.id };
					this.changeLoading(true);
					await this.loadManufacturedProducts(filter)
					.then(res => {this.changeLoading()})
					.catch(err => {this.changeLoading()});
					this.loadSaleProducts(filter);
				}
			},
			submit(close = true){
				let manufactured_products = this.manufactured_products.map((item) => {
                    let rate = item.currency ? (item.currency.reverse ? _.round(1 / item.rate, 8) : item.rate) : 1;
					return {
						sale_product_id: item.sale_product_id,
						product_id: item.product.id,
						warehouse_id: item.warehouse_id,
						selling_price: item.selling_price,
						quantity: item.quantity,
						rate: rate,
						currency_id: item.currency_id
					}
				});
				if (manufactured_products.length == 0 || _.find(manufactured_products, function (o) { return (!o.warehouse_id || o.quantity <= 0)})) {
					this.$message({
						message: this.$t('message.product_validation_error'),
						type: "warning"
					});
					return;
				}
				this.changeLoading(true);
				this.manufacturedStore({manufactured_products})
					.then(res => {
						this.changeLoading();
						this.$alert(res);
						this.load();
						this.parent().listChanged();
						this.manufactured_products = [];
						if (close === true) this.close();
					})
					.catch(err => {
						this.changeLoading();
						this.$alert(err);
				})
			},
			updateCurrency(item) {
				if (item) {
					const currency = _.find(this.currencies, ['id', item.currency_id]);
					if (currency) {
						item.currency = currency;
						item.rate = currency.reverse ? currency.reversed_rate : currency.rate;
					}
				}
			},
			appendProduct(){
				let manufactured_product = {};
				const product = this.selectedSaleProduct.product;
				manufactured_product.sale_product_id = this.selectedSaleProduct.id;
				manufactured_product.product = product;
				manufactured_product.warehouse_type_id = product.warehouse_type ? product.warehouse_type.id : null;

				manufactured_product.warehouse_id = null;
				manufactured_product.quantity = this.selectedSaleProduct.not_produced;
				manufactured_product.not_produced = this.selectedSaleProduct.not_produced;
				manufactured_product.selling_price = product.selling_price;

				const currency = product.selling_currency ? product.selling_currency : _.find(this.currencies, 'active');
				if (currency) {
					manufactured_product.currency = currency;
					manufactured_product.rate = currency.reverse ? currency.reversed_rate : currency.rate;
					manufactured_product.currency_id = currency.id;
				}

				this.manufactured_products.push(manufactured_product);
				this.selectedSaleProduct = '';
			},
			removeProduct(line){
				if (this.manufactured_products.length > 0) {
					this.manufactured_products.splice(this.manufactured_products.indexOf(line),1)
				}
			},
			afterLeave(){
				this.manufactured_products = [];
			}
		}
    };
</script>
