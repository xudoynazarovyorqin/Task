<template>
<div>
    <header id="el-drawer__title" class="el-drawer__header">
        <span>{{ $t('message.edit') }} {{ $t('message.sale') | lowerFirst }} № {{ form.id }}</span>
        <el-button v-can="['sales.update']" type="success" size="small" class="mr-1" :loading="waiting"
            @click="submit(false)"> {{ $t('message.save') }}</el-button>
        <el-button v-can="['sales.update']" type="primary" size="small" class="mr-1" :loading="waiting"
            @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
        <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
        </el-button>
    </header>
    <el-main v-loading="loading">
        <el-tabs v-model="activeTab" @tab-click="handleTabClick">
            <el-tab-pane :label="$t('message.about_sale')" name="main">
                <el-card shadow="never" class="crm-card-pt-1">
                    <el-form ref="form" :model="form" :rules="rules" label-width="100px" size="small">
                        <el-col>
                            <el-form-item size="small" prop="number" class="d-inline-flex crm-document-number">
                                <span  class="document-title"> {{ form.number }} </span>
                                <template slot="label">
                                    <span class="document-title">{{ $t('message.sale') }} {{ $t('message.n') }}</span>
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
                            <span class="el-dropdown-link float-right p-4">
                                <span class="text-secondary">{{ $t('message.owner') }}:</span> <span> {{ auth_name }}</span>
                            </span>
                        </el-col>
                        <el-col :span="8">
                            <el-form-item :label="columns.begin_date.title">
                                <el-date-picker v-model="form.begin_date" type="date"
                                    :placeholder="columns.begin_date.title" :format="date_format"
                                    :value-format="date_format" @change="controlDate()"></el-date-picker>
                            </el-form-item>
                            <el-form-item :label="columns.end_date.title">
                                <el-date-picker v-model="form.end_date" type="date"
                                    :placeholder="columns.end_date.title" :format="date_format"
                                    :value-format="date_format" @change="controlDate()"></el-date-picker>
                            </el-form-item>
                        </el-col>
                        <el-col :span="8">
                            <states v-model="form.state_id" :state_id="form.state_id" prop=""></states>
                            <priorities v-model="form.priority_id" :priority_id="form.priority_id" prop=""></priorities>
                        </el-col>
                        <el-col :span="8">
                            <levels v-model="form.level_id" :level_id="form.level_id"></levels>
                        </el-col>
                    </el-form>
                </el-card>
                <el-card class="mt-2" shadow="never">
                    <el-tabs>
                        <el-tab-pane>
                            <span slot="label">
                                <i class="el-icon-s-goods"></i> {{ $t('message.products')}}
                            </span>
                            <el-table size="medium" :data="[...old_items,...items]" style="width: 100%"
                                :summary-method="getProductSummeries" show-summary class="crm-el-table">
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
                                <el-table-column :label="$t('message.delete')">
                                    <template slot-scope="item">
                                        <el-button
                                            @click="item.row.id ? deleteProduct(item.row) : removeProduct(item.row)"
                                            type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <el-col :span="12" class="mt-1">
                                <products @append="appendProduct" :plc="$t('message.product_select_plc')"
                                    :filter="{production_type: 'production', production: 1}"></products>
                            </el-col>
                        </el-tab-pane>
                        <el-tab-pane>
                            <span slot="label">
                                <i class="el-icon-circle-plus-outline"></i> {{ $t('message.additional_materials')}}
                            </span>
                            <el-table size="medium" :data="[...old_additional_materials,...additional_materials]"
                                style="width: 100%" class="crm-el-table" :summary-method="getMaterialSummeries"
                                show-summary>
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
                                            <el-input v-model="item.row.quantity" type="number" size="mini"></el-input>
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
                                        <el-button
                                            @click="item.row.id ? deleteAdditionalMaterial(item.row) : removeAdditionalMaterial(item.row)"
                                            type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <el-col :span="12" class="mt-1">
                                <materials @append="appendAdditionalMaterial" :plc="$t('message.product_select_plc')">
                                </materials>
                            </el-col>
                        </el-tab-pane>
                    </el-tabs>
                </el-card>
                <crm-history :is_edit="true" @crm-change="levelChanged()"></crm-history>
            </el-tab-pane>
            <el-tab-pane :label="$t('message.expense_rate')" name="crm-report-show">
                <report ref="crm-sale-show-report" :costs="[]" :is_order="false" :order_id="null" :sale_id="sale.id"></report>
            </el-tab-pane>
            <el-tab-pane v-if="items.length > 0" :label="$t('message.Consumption rate for new products')" name="crm-report">
                <crm-sale-report ref="crm-sale-report" :products="items" :costs="[]"
                    :additional_materials="additional_materials" :is_order="false" :order_id="null"
                    :sale_id="form.id"></crm-sale-report>
            </el-tab-pane>
            <el-tab-pane :label="$t('message.employees')" name="employee">
                <crm-employee :old_employee_groups="oldEmployeeGroups" :is_edit="true" @crm-change="changeEmployee">
                </crm-employee>
            </el-tab-pane>
        </el-tabs>
    </el-main>
</div>
</template>
<script>
	import { mapGetters, mapActions } from "vuex";
	import CrmHistory from './include/crm-history';
    import form from '@/utils/mixins/form';
    import production from '@/utils/mixins/models/production';
    import drawer from '@/utils/mixins/includes/drawer';
    import report from '@/includes/report/crm-sale-show-report';

    export default {
		mixins:[form,production,drawer],
		props:['sale','open'],
		components:{CrmHistory,report},
		data() {
			return {
				is_new: false,
			};
		},
		computed: {
			...mapGetters({
		        oldEmployeeGroups: 'sales/employeeGroups',
			})
		},
		methods: {
			...mapActions({
				save: "sales/update",
				edit: 'sales/edit',
			}),
			afterOpen(){
				this.form = this.getForm;
				this.activeTab = 'main';
				this.items = [];
				this.additional_materials = [];
				this.load();
			},
			load(){
              if (!this.loading && this.sale) {
					this.changeLoading(true);
					this.edit(this.sale.id)
						.then(res => {
							this.form = this.getForm;
							this.changeLoading();
							this.reportChanged = true;
						})
						.catch(err => {
							this.changeLoading();
						})
              	}
			},
			deleteProduct(item){
				 this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
                        confirmButtonText: this.$t('message.yes'),
                        cancelButtonText: this.$t('message.cancel'),
                        type: 'warning'
                    }).then(() => {
						this.changeLoading(true);
						this.$store.dispatch("sales/deleteProduct",{sale_id: this.form.id, item_id: item.id})
							.then(res => { this.changeLoading(false); this.$alert(res); this.load(); this.reportChanged = true; })
							.catch(err => { this.changeLoading(false); this.$alert(err); this.reportChanged = true; })
					}).catch(() => {});
        	},
			deleteAdditionalMaterial(additional_material){
				 this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
                        confirmButtonText: this.$t('message.yes'),
                        cancelButtonText: this.$t('message.cancel'),
                        type: 'warning'
                    }).then(() => {
						this.changeLoading(true);
						this.$store.dispatch("sales/deleteAdditionalMaterial",{sale_id: this.form.id, additional_material_id: additional_material.id})
							.then(res => { this.changeLoading(false); this.$alert(res); this.load();this.reportChanged = true; })
							.catch(err => { this.changeLoading(false); this.$alert(err); this.reportChanged = true; })
					}).catch(() => {});
			},
			controlDate() {
				this.dateIsEmpty({
					begin_date: this.form.begin_date,
					end_date: this.form.end_date,
					production_type: 'production',
					sale_id: this.form.id
				});
			},
			levelChanged(){
				this.listChanged();
				this.load();
			}
		}
    };
</script>
