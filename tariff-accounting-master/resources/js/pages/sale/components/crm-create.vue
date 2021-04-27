<template>
	<div>
		<header id="el-drawer__title" class="el-drawer__header">
			<span> {{ $t('message.new') }} {{ $t('message.sale') | lowerFirst }}</span>
			<el-button v-can="['sales.create']" type="success" size="small" class="mr-1" :loading="waiting"
				@click="submit(false)"> {{ $t('message.save') }}</el-button>
			<el-button v-can="['sales.create']" type="primary" size="small" class="mr-1" :loading="waiting"
				@click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
			<el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
			</el-button>
		</header>
		<el-main class="pt-1">
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
								<el-table size="medium" :data="items" style="width: 100%" :summary-method="getProductSummeries" show-summary class="crm-el-table">
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
											<el-input v-model="item.row.quantity" type="number" size="mini"></el-input>
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.measurement')">
										<template slot-scope="item">
											{{ item.row.product ? item.row.product.measurement ? item.row.product.measurement.name : '' : '' }}
										</template>
									</el-table-column>
									<el-table-column :label="$t('message.delete')">
										<template slot-scope="item">
											<el-button @click="removeProduct(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
										</template>
									</el-table-column>
								</el-table>
								<el-col :span="12" class="mt-1">
									<products @append="appendProduct" :plc="$t('message.product_select_plc')" :filter="{production_type: 'production', production: 1}"></products>
								</el-col>
							</el-tab-pane>
							<el-tab-pane>
								<span slot="label">
									<i class="el-icon-circle-plus-outline"></i> {{ $t('message.additional_materials')}}
								</span>
								<el-table size="medium" :data="additional_materials" style="width: 100%" class="crm-el-table" :summary-method="getMaterialSummeries" show-summary>
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
											<el-input type="number" v-model="item.row.quantity" size="mini"></el-input>
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
											<el-button @click="removeAdditionalMaterial(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
										</template>
									</el-table-column>
								</el-table>
								<el-col :span="12" class="mt-1">
									<materials @append="appendAdditionalMaterial" :plc="$t('message.product_select_plc')"></materials>
								</el-col>
							</el-tab-pane>
						</el-tabs>
					</el-card>
				</el-tab-pane>
				<el-tab-pane :label="$t('message.expense_rate')" name="crm-report">
					<crm-sale-report ref="crm-sale-report" :products="items" :costs="[]"
						:additional_materials="additional_materials" :is_order="false" :is_edit="false" :order_id="null"
						:sale_id="null"></crm-sale-report>
				</el-tab-pane>
				<el-tab-pane :label="$t('message.employees')" name="fourth">
					<crm-employee :old_employee_groups="[]" :is_edit="false" @crm-change="changeEmployee"></crm-employee>
				</el-tab-pane>
			</el-tabs>
		</el-main>
	</div>
</template>
<script>
    import { mapGetters, mapActions } from "vuex";
    import form from '@/utils/mixins/form';
    import production from '@/utils/mixins/models/production';
    import drawer from '@/utils/mixins/includes/drawer';

    export default {
		mixins:[form,production,drawer],
		data() {
			return {
				is_new: true
			};
		},
		methods: {
			...mapActions({
				save: "sales/store",
                getLastId: 'sales/getLastId'
			}),
			afterOpen(){
				this.form = this.getForm;
				this.items = [];
				this.additional_materials = [];
				if (!this.lastId) {
					this.getLastId().then(res => { this.form.number = res.last_id})
				}else{
					this.form.number = this.lastId;
				}
        	},
			controlDate() {
				this.dateIsEmpty({
						begin_date: this.form.begin_date,
						end_date: this.form.end_date,
						production_type: 'production',
					});
			},
		}
    };
</script>
