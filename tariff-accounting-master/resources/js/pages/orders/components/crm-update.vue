<template>
	<div>
		<header id="el-drawer__title" class="el-drawer__header">
			<span>{{ $t('message.edit') }} {{ $t('message.order') | lowerFirst }} <small class="ml-5">
					<el-badge class="item mr-4" :value="(order_products.length + old_order_products.length)" type="success">
						<i class="el-icon-shopping-cart-2"></i></el-badge> <b>{{ totalAmount | formatMoney }}</b>
				</small> </span>
			<el-button v-can="['orders.update']" type="success" size="small" class="mr-1" :loading="waiting"
				@click="submit(false)"> {{ $t('message.save') }}</el-button>
			<el-button v-can="['orders.update']" type="primary" size="small" class="mr-1" :loading="waiting"
				@click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
			<el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
			</el-button>
		</header>
		<el-main class="pt-1" v-loading="loading">
			<el-tabs v-model="activeTab" @tab-click="handleTabClick">
				<el-tab-pane :label="$t('message.about_off_order')" name="main">
					<el-card shadow="never" class="crm-card-pt-1">
						<el-form ref="form" :model="form" :rules="rules" label-width="100px">
							<el-col>
								<el-form-item size="small" prop="number" class="d-inline-flex crm-document-number">
	                                <span  class="document-title"> {{ form.number }} </span>
									<template slot="label">
										<span class="document-title">{{ $t('message.order') }}
											{{ $t('message.n') }}</span>
									</template>
								</el-form-item>
								<el-form-item label-width="20px" size="small" prop="datetime" class="d-inline-flex">
									<el-date-picker prefix-icon="el-icon-date" v-model="form.datetime" type="datetime"
										:format="date_time_format" :value-format="date_time_format">
									</el-date-picker>
									<template slot="label">
										<span class="document-title">{{ $t('message.from') | lowerFirst }}</span>
									</template>
								</el-form-item>
								<el-form-item label-width="0" size="small" class="d-inline-flex ml-2">
									<el-switch v-model="form.production_type" disabled active-value="assembly"
										inactive-value="production" :active-text="$t('message.assembly')"
										:inactive-text="$t('message.sale')" active-color="#13ce66"
										inactive-color="#2132F0" @change="controlDate()"></el-switch>
								</el-form-item>
								<span class="el-dropdown-link float-right p-4">
									<span class="text-secondary">{{ $t('message.owner') }}:</span> <span> {{ auth_name }}</span>
								</span>
							</el-col>
							<el-col :span="8">
								<clients v-model="form.client_id" :client_id="form.client_id"
									:label="$t('message.client')"></clients>
								<contracts v-model="form.contract_client_id" :client_id="form.client_id"
									:contract_id="form.contract_client_id"></contracts>
							</el-col>
							<el-col :span="8">
								<states v-model="form.state_id" :state_id="form.state_id"></states>
								<priorities v-model="form.priority_id" :priority_id="form.priority_id"></priorities>
							</el-col>
							<el-col :span="8">
								<el-form-item :label="columns.begin_date.title" size="small">
									<el-date-picker v-model="form.begin_date" @change="controlDate()" type="date"
										:placeholder="columns.begin_date.title" :format="date_format"
										:value-format="date_format"></el-date-picker>
								</el-form-item>
								<el-form-item :label="columns.end_date.title" size="small">
									<el-date-picker v-model="form.end_date" @change="controlDate()" type="date"
										:placeholder="columns.end_date.title" :format="date_format"
										:value-format="date_format">></el-date-picker>
								</el-form-item>
							</el-col>
						</el-form>
					</el-card>
					<el-card shadow="never" class="mt-2">
						<el-tabs>
							<el-tab-pane>
								<span slot="label">
									<i class="el-icon-s-goods"></i> {{ $t('message.products')}}
								</span>
								<el-table size="medium" :data="[...old_order_products,...order_products]" style="width: 100%" class="crm-el-table">
									<template slot="empty">
										<span></span>
									</template>
									<el-table-column :label="$t('message.name')" :min-width="150">
										<template slot-scope="item">
											<b>{{ (item.row.product ? item.row.product.name : '') | truncate }}</b>
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.quantity')">
										<template slot-scope="item">
											<template v-if="item.row.id">
												{{ item.row.quantity | formatNumber }}
											</template>
											<template v-else>
												<el-input v-model="item.row.quantity" type="number" size="mini"></el-input>
											</template>
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.measurement')">
										<template slot-scope="item">
											{{ item.row.product ? item.row.product.measurement ? item.row.product.measurement.name : '' : '' }}
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.selling_price')">
										<template slot-scope="item">
											<template v-if="item.row.id">
												{{ item.row.price | formatNumber(2) }} {{ item.row.currency ? item.row.currency.symbol : '' }}
											</template>
											<template v-else>
		                                       <product-price v-model="item.row.price" :old="item.row.price" size="mini"></product-price>
											</template>
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.currency')">
										<template slot-scope="item">
											<template v-if="item.row.id">
												{{ (item.row.currency ? item.row.currency.name : '') | truncate}}
											</template>
											<template v-else>
												<currencies size="mini" v-model="item.row.currency_id" :currency_id="item.row.currency_id" @c-change="updateCurrency(item.row)"></currencies>
											</template>
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.rate')">
										<template slot-scope="item">
											<template v-if="item.row.id">
												<crm-rate :row="item.row"></crm-rate>
											</template>
											<template v-else>
												<el-input :hidden="item.row.currency && item.row.currency.active" type="number" v-model="item.row.rate" size="mini"></el-input>
											</template>
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.total_amount')">
										<template slot-scope="item">
	                                        {{ (item.row.quantity * item.row.price) | formatNumber }} {{ item.row.currency ? item.row.currency.symbol : '' }}
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.delete')">
										<template slot-scope="item">
											<el-button @click="item.row.id ? deleteProduct(item.row) : removeProduct(item.row)" type="danger"
												icon="el-icon-delete" size="mini" circle></el-button>
										</template>
									</el-table-column>
								</el-table>
								<el-col :span="12" class="mt-1">
									<products @append="appendProduct" :plc="$t('message.product_select_plc')"
										:filter="{production_type: form.production_type, production: 1}"></products>
								</el-col>
								<el-col :span="6" :offset="2" class="p-2">
									<h5 class="float-left font-weight-bold">{{ $t('message.total') }}: </h5>
									<h5 class="float-right font-weight-bold">{{ totalAmount | formatMoney(2) }}</h5>
								</el-col>
							</el-tab-pane>
							<el-tab-pane>
								<span slot="label">
									<i class="el-icon-circle-plus-outline"></i> {{ $t('message.additional_materials')}}
								</span>
								<el-table size="medium" :data="[...old_additional_materials,...additional_materials]"
									style="width: 100%" class="crm-el-table">
									<template slot="empty">
										<span></span>
									</template>
									<el-table-column :label="$t('message.name')">
										<template slot-scope="item">
											<b>{{ (item.row.material ? item.row.material.name : '') | truncate }}</b>
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.quantity')">
										<template slot-scope="item">
											<template v-if="item.row.id">
												{{ item.row.quantity | formatNumber }}
											</template>
											<template v-else>
												<el-input type="number" v-model="item.row.quantity" size="mini"></el-input>
											</template>
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.measurement')">
										<template slot-scope="item">
											{{ item.row.material ? item.row.material.measurement ? item.row.material.measurement.name : '' : '' }}
											{{ item.row.material | addMeasurement(item.row.quantity)}}
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.delete')">
										<template slot-scope="item">
											<el-button @click="item.row.id ? deleteAdditionalMaterial(item.row) : removeAdditionalMaterial(item.row)" type="danger"
												icon="el-icon-delete" size="mini" circle></el-button>
										</template>
									</el-table-column>
								</el-table>
								<el-col :span="12" class="mt-1">
									<materials @append="appendAdditionalMaterial"
										:plc="$t('message.product_select_plc')"></materials>
								</el-col>
							</el-tab-pane>
							<el-tab-pane>
								<span slot="label">
									<i class="el-icon-s-shop"></i> {{ $t('message.additional_costs')}}
								</span>
								<el-table size="medium" :data="[...old_order_costs,...order_costs]" style="width: 100%" class="crm-el-table">
									<template slot="empty">
										<span></span>
									</template>
									<el-table-column :label="$t('message.name')">
										<template slot-scope="item">
											<b>{{ (item.row.cost ? item.row.cost.name : '') | truncate }}</b>
										</template>
									</el-table-column>
	                                <el-table-column :label="$t('message.amount')">
										<template slot-scope="item">
											<template v-if="item.row.id">
												{{ item.row.amount | formatNumber }} {{ item.row.currency ? item.row.currency.symbol : '' }}
											</template>
											<template v-else>
												<cost-price v-model="item.row.amount" :old="item.row.amount" size="mini"></cost-price>
											</template>
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.currency')">
										<template slot-scope="item">
											<template v-if="item.row.id">
												{{ (item.row.currency ? item.row.currency.name : '') | truncate }}
											</template>
											<template v-else>
												<currencies size="mini" v-model="item.row.currency_id" :currency_id="item.row.currency_id" @c-change="updateCurrency(item.row)"></currencies>
											</template>
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.rate')">
										<template slot-scope="item">
											<template v-if="item.row.id">
												<crm-rate :row="item.row"></crm-rate>
											</template>
											<template v-else>
												<el-input :hidden="item.row.currency && item.row.currency.active" type="number" v-model="item.row.rate" size="mini"></el-input>
											</template>
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.delete')">
										<template slot-scope="item">
											<el-button @click="item.row.id ? deleteCost(item.row) : removeCost(item.row)" type="danger" icon="el-icon-delete"
												size="mini" circle></el-button>
										</template>
									</el-table-column>
								</el-table>
								<el-col :span="12" class="mt-1">
									<costs @append="appendCost" :plc="$t('message.cost')"></costs>
								</el-col>
							</el-tab-pane>
						</el-tabs>
					</el-card>
					<!-- <crm-audit :created_audit="created_audit"></crm-audit> -->
				</el-tab-pane>
				<el-tab-pane :label="$t('message.expense_rate')" name="crm-report-show">
					<crm-assembly-show-report v-if="form.production_type == 'assembly'" ref="crm-assembly-show-report" :costs="[...old_order_costs]" :is_order="true" :order_id="form.id" :assembly_id="null"></crm-assembly-show-report>
					<crm-sale-show-report v-if="form.production_type == 'production'" ref="crm-sale-show-report" :costs="[...old_order_costs]" :is_order="true" :order_id="form.id" :sale_id="null"></crm-sale-show-report>
				</el-tab-pane>
            	<el-tab-pane v-if="order_products.length > 0" :label="$t('message.Consumption rate for new products')" name="crm-report">
					<crm-assembly-report v-if="form.production_type == 'assembly'" ref="crm-assembly-report" :products="order_products" :costs="[...order_costs]" :additional_materials="additional_materials" :is_order="true" :is_edit="true" :order_id="form.id" :assembly_id="null"></crm-assembly-report>
					<crm-sale-report v-if="form.production_type == 'production'" ref="crm-sale-report" :products="order_products" :costs="[...order_costs]" :additional_materials="additional_materials" :is_order="true" :is_edit="true" :order_id="form.id" :sale_id="null"></crm-sale-report>
				</el-tab-pane>
				<el-tab-pane :label="$t('message.employees')" name="employees">
					<crm-employee :old_employee_groups="oldEmployeeGroups" :is_edit="true" @crm-change="changeEmployee"></crm-employee>
				</el-tab-pane>
			</el-tabs>
		</el-main>
	</div>
</template>
<script>
    import form from '@/utils/mixins/form';
    import drawer from '@/utils/mixins/includes/drawer';
    import order from '@/utils/mixins/models/order';
    import CrmAudit from "@/includes/crm-audit";
    import { mapGetters, mapActions } from "vuex";
    import  CrmAssemblyShowReport from '@/includes/report/crm-assembly-show-report';
    import  CrmSaleShowReport from '@/includes/report/crm-sale-show-report';

    export default {
        mixins:[form,order,drawer],
        props:['order','open'],
        components: {CrmAudit,CrmAssemblyShowReport,CrmSaleShowReport},
        data() {
            return {
              is_new: false,
            };
        },
        computed: {
			...mapGetters({
				created_audit: 'orders/created_audit'
			}),
        },
        methods: {
			...mapActions({
				save: "orders/update",
				edit: 'orders/edit',
			}),
			afterOpen(){
				this.form = this.getForm;
				this.order_products = [];
				this.additional_materials = [];
				this.order_costs = [];
				this.activeTab = 'main';
				this.load();
			},
			load(){
				if (!this.loading && this.order) {
					this.changeLoading(true);
					this.edit(this.order.id)
						.then(res => {
							this.form = this.getForm;
							this.changeLoading(false);
						})
						.catch(err => {
							this.changeLoading(false)
						})
				}
			},
			controlDate() {
				this.dateIsEmpty({
					begin_date: this.form.begin_date,
					end_date: this.form.end_date,
					production_type: this.form.production_type,
					checkOrder: true,
					order_id: this.form.id
				});
			},
			deleteProduct(order_product){
				 this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
                        confirmButtonText: this.$t('message.yes'),
                        cancelButtonText: this.$t('message.cancel'),
                        type: 'warning'
                    }).then(() => {
						this.changeLoading(true);
						this.$store.dispatch("orders/deleteProduct",{order_id: this.form.id, order_product_id: order_product.id})
							.then(res => { this.changeLoading(false); this.$alert(res); this.load() })
							.catch(err => { this.changeLoading(false); this.$alert(err) })
					}).catch(() => {});
			},
			deleteAdditionalMaterial(additional_material){
				 this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
                        confirmButtonText: this.$t('message.yes'),
                        cancelButtonText: this.$t('message.cancel'),
                        type: 'warning'
                    }).then(() => {
							this.changeLoading(true);
							this.$store.dispatch("orders/deleteAdditionalMaterial",{order_id: this.form.id, additional_material_id: additional_material.id})
							.then(res => { this.changeLoading(false); this.$alert(res); this.load() })
							.catch(err => { this.changeLoading(false); this.$alert(err) })
					}).catch(() => {});
			},
			deleteCost(order_cost){
				 this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
                        confirmButtonText: this.$t('message.yes'),
                        cancelButtonText: this.$t('message.cancel'),
                        type: 'warning'
                    }).then(() => {
						this.changeLoading(true);
						this.$store.dispatch("orders/deleteCost",{order_id: this.form.id, order_cost_id: order_cost.id})
						.then(res => { this.changeLoading(false); this.$alert(res); this.load() })
						.catch(err => { this.changeLoading(false); this.$alert(err) })
					}).catch(() => {});
			},
			afterLeave(){
				/**
				 * Write code here is run after drawer closed.
				 */
			}
        }
    };
</script>
