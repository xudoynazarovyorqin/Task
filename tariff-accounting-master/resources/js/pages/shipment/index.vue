<template>
	<div class="row table-sm mr-0 ml-0 p-0">
		<div class="crm-content-header d-flex w-100 mb-2">
			<div class="crm-content-header-left d-flex w-50">
				<div class="crm-content-header-left-item mr-3" style="width:300px">
					<h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.shipments") }}</h5>
					<crm-refresh @c-click="refresh()"></crm-refresh>
				</div>
				<div class="crm-content-header-left-item">
					<el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search" clearable></el-input>
				</div>
				<div class="crm-content-header-left-item">
					<el-date-picker v-model="filterForm.from_date" type="date" :value-format="date_format" size="mini" :placeholder="$t('message.from')">
					</el-date-picker>
				</div>
				<div class="crm-content-header-left-item">
					<el-date-picker v-model="filterForm.to_date" type="date" :value-format="date_format" size="mini" :placeholder="$t('message.to')">
					</el-date-picker>
				</div>
			</div>
			<div class="crm-content-header-right d-flex w-50 justify-content-end">
				<div class="crm-content-header-right-item">
					<el-button v-can="'shipments.create'" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
				</div>
				<div class="crm-content-header-right-item">
					<crm-edit-dropdown v-can="'shipments.delete'" :items="selectedItems" @delete="multipleDelete"></crm-edit-dropdown>
				</div>
				<div class="crm-content-header-right-item">
					<crm-column-settings :columns="columns" @c-change="updateColumn"></crm-column-settings>
				</div>
			</div>
		</div>
		<table class="table table-bordered table-hover" v-loading="loadingData"
				:element-loading-text="$t('message.loading')"
				element-loading-spinner="el-icon-loading">
			<crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
			<thead>
				<tr>
					<th></th>
					<crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
					<crm-th :column="columns.datetime" :sort="sort" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.user_id" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.products" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.shipmentable_type" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.shipmentable_id" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
				</tr>
				<tr>
					<th>
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input" v-model="checkAll" id="checkAll" @change="handleCheckAllChange">
							<label class="custom-control-label cursor-pointer" for="checkAll"></label>
						</div>
					</th>
					<th v-if="columns.id.show">
						<el-input clearable size="mini" class="id_input" v-model="filterForm.id" :placeholder="columns.id.title"></el-input>
					</th>
					<th v-if="columns.datetime.show">
						<el-date-picker v-model="filterForm.datetime" :placeholder="columns.datetime.title" size="mini" :value-format="date_format"></el-date-picker>
					</th>
					<th v-if="columns.user_id.show">
						<users size="mini" v-model="filterForm.user_id"></users>
					</th>
					<th v-if="columns.products.show">
						<el-input size="mini" disabled :placeholder="columns.products.title" clearable></el-input>
					</th>
					<th v-if="columns.shipmentable_type.show">
						<shipmentable-types v-model="filterForm.shipmentable_type" size="mini"></shipmentable-types>
					</th>
					<th v-if="columns.shipmentable_id.show">
						<el-input size="mini" v-model="filterForm.shipmentable_id" :placeholder="columns.shipmentable_id.title" clearable></el-input>
					</th>
					<th v-if="columns.comment.show">
						<el-input size="mini" v-model="filterForm.comment" :placeholder="columns.comment.title" clearable></el-input>
					</th>
					<th v-if="columns.created_at.show">
						<el-date-picker v-model="filterForm.created_at" :placeholder="columns.created_at.title" size="mini" :value-format="date_format">
						</el-date-picker>
					</th>
					<th v-if="columns.updated_at.show">
						<el-date-picker v-model="filterForm.updated_at" :placeholder="columns.updated_at.title" size="mini" :value-format="date_format">
						</el-date-picker>
					</th>
					<th v-if="columns.settings.show"></th>
				</tr>
			</thead>
			<transition-group name="flip-list" tag="tbody">
				<tr v-for="item in list" :key="item.id" :class="{'table-active': (selectedItems.indexOf(item) > -1)}" class="cursor-pointer">
					<td>
						<div class="custom-control custom-checkbox d-inline-block">
							<input type="checkbox" class="custom-control-input" v-model="selectedItems" :value="item" :id="'customCheck'+item.id" @change="handleCheckedItemsChange">
							<label class="custom-control-label cursor-pointer" :for="'customCheck'+item.id"></label>
						</div>
					</td>
					<td v-if="columns.id.show">{{ item.id }}</td>
					<td v-if="columns.datetime.show">{{ item.datetime }}</td>
					<td v-if="columns.user_id.show">{{ item.user ? item.user.name : '' }}</td>
					<td v-if="columns.products.show">
						<span @click="showItems(item)" style="cursor: pointer; color: #3490dc;">
							{{ $t('message.view')}}
						</span>
					</td>
					<td v-if="columns.shipmentable_type.show">{{ item.shipmentable_type ? $t('message.' + item.shipmentable_type) : '' }}</td>
					<td v-if="columns.shipmentable_id.show">{{ item.shipmentable_id | formatNumber }}</td>
					<td v-if="columns.comment.show">{{ item.comment | truncate }}</td>
					<td v-if="columns.created_at.show">{{ item.created_at }}</td>
					<td v-if="columns.updated_at.show">{{ item.updated_at }}</td>
					<td v-if="columns.settings.show" class="settings-td">
						<crm-setting-dropdown :model="item" name="shipments" :actions="actions" @edit="edit" @delete="destroy"></crm-setting-dropdown>
					</td>
				</tr>
			</transition-group>
		</table>
		<el-dialog :title="$t('message.products')" :visible.sync="visibleItems" width="80%">
			<table class="table" v-loading="loadingItems">
				<thead>
					<tr class="crm-table-header-border-0">
						<th>{{ $t('message.name') }}</th>
						<th>{{ $t('message.quantity') }} </th>
						<th>{{ $t('message.Armored Exemptions') }} </th>
						<th>{{ $t('message.measurements') }}</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(item ,index) in items" :key="index">
						<td>{{ (item.product) ? item.product.name : '' }}</td>
						<td>{{ item.quantity | formatNumber }}</td>
						<td>{{ item.issued_from_booked | formatNumber }}</td>
						<td>
							{{ (item.product && item.product.measurement) ? item.product.measurement.name : '' }}
						</td>
					</tr>
				</tbody>
			</table>
		</el-dialog>
		<el-drawer :with-header="false" :visible.sync="drawerCreate" size="90%" ref="drawerCreate" @opened="drawerOpened('drawerCreateChild')"  @closed="drawerClosed('drawerCreateChild')">
			<crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
		</el-drawer>
		<el-drawer :with-header="false" :visible.sync="drawerUpdate" size="90%" ref="drawerUpdate" @opened="drawerOpened('drawerUpdateChild')"  @closed="drawerClosed('drawerUpdateChild')">
			<crm-update :shipment="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
		</el-drawer>
	</div>
</template>
<script>
	import { mapActions, mapGetters } from "vuex";
    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import list from "@/utils/mixins/list";
    import users from '@inventory/crm-user-select';
    import shipmentableTypes from '@inventory/crm-shipmentable-type-select';

	export default {
		name: "ShipmentsList",
        mixins: [list],
        components: { CrmCreate , CrmUpdate, users, shipmentableTypes},
		data(){
			return {
				visibleItems: false,
                loadingItems: false,
                items: [],
			}
		},
		computed: {
			...mapGetters({
				list: "shipments/list",
				columns: "shipments/columns",
				pagination: "shipments/pagination",
				filter: "shipments/filter",
				sort: "shipments/sort",
			}),
			actions: function() {
				return ["edit", "delete"];
			}
		},
		methods: {
			...mapActions({
				updateList: "shipments/index",
				updateSort: "shipments/updateSort",
				updateFilter: "shipments/updateFilter",
				updateColumn: "shipments/updateColumn",
				updatePagination: "shipments/updatePagination",
				refreshData: "shipments/refreshData",
				empty: "shipments/empty",
				delete: "shipments/destroy",
				multiDelete: 'shipments/multiDelete',
				printModel: 'shipments/print',
                loadItems: 'shipments/items'
			}),
			print(model){
				this.printModel({'shipment_id': model.id})
					.then(res => {
						const WinPrint = window.open('', '', 'left=0,top=0,toolbar=0,scrollbars=0,status=0');
						WinPrint.document.write(res.data);
						WinPrint.document.close();
						WinPrint.focus();
						WinPrint.print();
						WinPrint.close();
					})
					.catch(err => {
						this.$alert(err)
					})
			},
			showItems(model) {
                this.visibleItems = true;
                this.loadingItems = true;
                this.loadItems({shipment_id: model.id})
                .then(res => {                    
                    this.items = res.data ? res.data.shipment_products : [];
                    this.loadingItems = false;
                })
                .catch(err => {
                    this.loadingItems = false;
                    this.$alert(err)
                })
            },
			multipleDelete(items){
				this.multiDelete({items: items.map((item) => {return item.id})})
				.then(res => {
					this.$alert(res);
					this.fetchData()
				})
				.catch(err => {
					this.$alert(err);
				})
			},
			handleCheckAllChange() {
				this.selectedItems = (this.checkAll) ? this.selectedItems = this.list : [];
				this.handleCheckedItemsChange()
			},
			handleCheckedItemsChange() {
				this.checkAll = this.selectedItems.length === this.list.length;
			}
		}
	};
</script>
