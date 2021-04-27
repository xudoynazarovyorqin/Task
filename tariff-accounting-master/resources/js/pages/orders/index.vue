<template>
	<div class="row table-sm mr-0 ml-0 p-0">
		<div class="crm-content-header d-flex w-100 mb-2">
			<div class="crm-content-header-left d-flex w-50">
				<div class="crm-content-header-left-item mr-3" style="width:380px">
					<h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.orders") }}</h5>
					<crm-refresh @c-click="refresh()"></crm-refresh>
				</div>
				<div class="crm-content-header-left-item">
					<el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search" clearable></el-input>
				</div>
				<div class="crm-content-header-left-item">
					<el-date-picker v-model="filterForm.from_date" type="date" :format="date_format" :value-format="date_format" size="mini" :placeholder="$t('message.from')"></el-date-picker>
				</div>
				<div class="crm-content-header-left-item">
					<el-date-picker v-model="filterForm.to_date" type="date" :format="date_format" :value-format="date_format" size="mini" :placeholder="$t('message.to')"></el-date-picker>
				</div>
			</div>
			<div class="crm-content-header-right d-flex w-50 justify-content-end">
				<div class="crm-content-header-right-item">
					<el-button v-can="'orders.create'" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
				</div>
				<div class="crm-content-header-right-item">
					<crm-edit-dropdown v-can="'orders.delete'" :items="selectedItems" @delete="multipleDelete">
					</crm-edit-dropdown>
				</div>
				<div class="crm-content-header-right-item">
					<export-excel v-can="'orders.excel'" :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.orders')" :name="$t('message.orders')">
						<el-button size="mini">
							<i class="el-icon-document-delete"></i> {{ $t('message.download_excel') }}
						</el-button>
					</export-excel>
				</div>
				<div class="crm-content-header-right-item">
					<crm-column-settings :columns="columns" @c-change="updateColumn"></crm-column-settings>
				</div>
			</div>
		</div>
		<div class="w-100 text-center mb-1 crm">
			<el-tag class="border-0" effect="plain">{{ $t('message.total_amount') }}: {{ totalAmount | formatMoney(2) }}</el-tag>
			<el-tag class="border-0" effect="plain" type="success">{{ $t('message.paid') }}: {{ totalPaid | formatMoney(2) }}</el-tag>
			<el-tag class="border-0" effect="plain" type="danger">{{ $t('message.not_paid') }}: {{ (totalAmount - totalPaid) | formatMoney(2) }}</el-tag>
		</div>
		<table class="table table-bordered table-hover" v-loading="loadingData"	:element-loading-text="$t('message.loading')" element-loading-spinner="el-icon-loading">
			<crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
			<thead>
				<tr>
					<th></th>
					<crm-th :column="columns.id" :sort="sort" @c-change="updateSort"></crm-th>
					<crm-th :column="columns.datetime" :sort="sort" @c-change="updateSort"></crm-th>
					<crm-th :column="columns.client_id" :sort="sort" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.begin_date" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.end_date" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.contract_client_id" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.amount" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.paid" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.not_paid" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.state_id" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.priority_id" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.production_type" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
					<crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
				</tr>
				<tr>
					<th>
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input" v-model="checkAll" id="checkAll" @change="handleCheckAllChange" />
							<label class="custom-control-label cursor-pointer" for="checkAll"></label>
						</div>
					</th>
					<th v-if="columns.id.show">
						<el-input clearable size="mini" class="id_input" v-model="filterForm.id" :placeholder="columns.id.title"></el-input>
					</th>
					<th v-if="columns.datetime.show">
						<el-date-picker v-model="filterForm.datetime" :placeholder="columns.datetime.title" size="mini" :value-format="date_format" :format="date_format">
						</el-date-picker>
					</th>
					<th v-if="columns.client_id.show">
                    	<clients size="mini" v-model="filterForm.client_id"></clients>
					</th>
					<th v-if="columns.begin_date.show">
						<el-date-picker v-model="filterForm.begin_date" :placeholder="columns.begin_date.title" size="mini" :format="date_format" :value-format="date_format"></el-date-picker>
					</th>
					<th v-if="columns.end_date.show">
						<el-date-picker v-model="filterForm.end_date" :placeholder="columns.end_date.title" size="mini" :format="date_format" :value-format="date_format"></el-date-picker>
					</th>
					<th v-if="columns.contract_client_id.show">
	                    <contracts v-model="filterForm.contract_client_id" size="mini" :client_id="filterForm.client_id"></contracts>
					</th>
					<th v-if="columns.amount.show">
						<el-input v-model="filterForm.amount" size="mini" :placeholder="columns.amount.title" clearable></el-input>
					</th>
					<th v-if="columns.paid.show">
						<el-input v-model="filterForm.paid" size="mini" :placeholder="columns.paid.title" clearable></el-input>
					</th>
					<th v-if="columns.not_paid.show">
						<el-input size="mini" :placeholder="columns.not_paid.title" disabled clearable></el-input>
					</th>
					<th v-if="columns.state_id.show">
		                <states v-model="filterForm.state_id" size="mini" ></states>
					</th>
					<th v-if="columns.priority_id.show">
		                <priorities v-model="filterForm.priority_id" size="mini"></priorities>
					</th>
					<th v-if="columns.production_type.show">
						<el-select filterable clearable :placeholder="columns.production_type.title" size="mini" v-model="filterForm.production_type">
							<el-option :label="$t('message.sale')" value="production"></el-option>
							<el-option :label="$t('message.assembly')" value="assembly"></el-option>
						</el-select>
					</th>
					<th v-if="columns.created_at.show">
						<el-date-picker v-model="filterForm.created_at" :placeholder="columns.created_at.title" size="mini" :value-format="date_format" :format="date_format">
						</el-date-picker>
					</th>
					<th v-if="columns.updated_at.show">
						<el-date-picker v-model="filterForm.updated_at" :placeholder="columns.updated_at.title" size="mini" :value-format="date_format" :format="date_format">
						</el-date-picker>
					</th>
					<th v-if="columns.settings.show"></th>
				</tr>
			</thead>
			<transition-group name="flip-list" tag="tbody">
				<tr v-for="order in list" :key="'orderItem-'+order.id" class="cursor-pointer" :class="{'table-active': (selectedItems.indexOf(order) > -1), 'table-secondary font-style-italic' : order.is_child}" @dblclick="show(order)" title="Дважды нажмите, чтобы посмотреть">
					<td>
						<div class="custom-control custom-checkbox d-inline-block">
							<input type="checkbox" class="custom-control-input" v-model="selectedItems" :value="order" :id="'customCheck'+order.id" @change="handleCheckedItemsChange" />
							<label class="custom-control-label cursor-pointer" :for="'customCheck'+order.id"></label>
						</div>
					</td>
					<td v-if="columns.id.show">{{ order.id }}</td>
					<td v-if="columns.datetime.show">{{ order.datetime }}</td>
					<td v-if="columns.client_id.show"> {{ order.client_id }} </td>
					<td v-if="columns.begin_date.show">{{ order.begin_date }}</td>
					<td v-if="columns.end_date.show">{{ order.end_date }}</td>
					<td v-if="columns.contract_client_id.show"> {{ order.contract_client_id }}</td>
					<td v-if="columns.amount.show">{{ order.amount | formatMoney }}</td>
					<td v-if="columns.paid.show">{{ order.paid | formatMoney }}</td>
					<td v-if="columns.not_paid.show">{{ order.not_paid | formatMoney }}</td>
					<td v-if="columns.state_id.show">{{ (order.state) ? (order.state.state) : '' }}</td>
					<td v-if="columns.priority_id.show">{{ (order.priority) ? (order.priority.name) : '' }}</td>
					<td v-if="columns.production_type.show">{{ order.production_type }} </td>
					<td v-if="columns.created_at.show">{{ order.created_at | dateFormat }}</td>
					<td v-if="columns.updated_at.show">{{ order.updated_at | dateFormat }}</td>
					<td v-if="columns.settings.show" class="settings-td">
						<crm-setting-dropdown name="orders" :model="order" :actions="actions" @edit="edit" @delete="destroy" @print="print" @show="show" @destroy="destroy" @comments="comments"></crm-setting-dropdown>
					</td>
				</tr>
			</transition-group>
		</table>
		<el-drawer :with-header="false" :visible.sync="drawerCreate" size="90%" ref="drawerCreate" @closed="drawerClosed('drawerCreateChild')" @opened="drawerOpened('drawerCreateChild')">
			<crm-create drawer="drawerCreate" ref="drawerCreateChild"></crm-create>
		</el-drawer>
		<el-drawer :with-header="false" :visible.sync="drawerUpdate" size="90%" ref="drawerUpdate" @closed="drawerClosed('drawerUpdateChild')"  @opened="drawerOpened('drawerUpdateChild')">
			<crm-update drawer="drawerUpdate" :open="drawerUpdate" :order="selectedItem" ref="drawerUpdateChild"></crm-update>
		</el-drawer>
		<el-drawer :with-header="false" :visible.sync="drawerShow" size="90%" ref="drawerShow" @closed="drawerClosed('drawerShowChild')" @opened="drawerOpened('drawerShowChild')">
			<crm-show drawer="drawerShow" :open="drawerShow" :model="selectedItem" ref="drawerShowChild"></crm-show>
		</el-drawer>
		<el-drawer :title="$t('message.comments')" :visible.sync="drawerComments" size="85%" :drawer="drawerComments">
			<crm-comments @crm-save="commentStore" :drawer="drawerComments" :model="selectedItem" :comments="commentsList" :loading="loadingComments" :title="$t('message.orders')"></crm-comments>
		</el-drawer>
	</div>
</template>
<script>
	import CrmCreate from "./components/crm-create";
	import CrmUpdate from "./components/crm-update";
	import CrmShow from "./components/crm-show";
	import CrmComments from "@/includes/crm-comments";
	import clients from '@inventory/crm-client-select';
	import states from '@inventory/crm-state-select';
	import priorities from '@inventory/crm-priority-select';
	import contracts from '@inventory/crm-contract-client-select';
	import { mapActions, mapGetters } from "vuex";
	import list from '@/utils/mixins/list';

	export default {
		mixins: [list],
		components: {CrmCreate,CrmUpdate,CrmShow,CrmComments,clients,states,contracts,priorities},
		data() {
			return {
				drawerComments: false,
			};
		},
  		computed: {
			...mapGetters({
				list: "orders/list",
				columns: "orders/columns",
				pagination: "orders/pagination",
				filter: "orders/filter",
				sort: "orders/sort",
				commentsList: 'orders/comments'
			}),
			actions: function() {
				return ['show','edit', 'print', 'comments', 'delete'];
			},
			totalAmount: function() {
				return _.sumBy(this.list, 'amount')
			},
			totalPaid: function() {
				return _.sumBy(this.list, 'paid')
			}
		},
		watch: {
			list: {
				handler: function() {
					this.checkAll = false;
					this.selectedItems = [];
				},
				deep: true
			}
		},
		methods: {
			...mapActions({
				updateSort: "orders/updateSort",
				updateFilter: "orders/updateFilter",
				updateColumn: "orders/updateColumn",
				updateList: "orders/index",
				updatePagination: "orders/updatePagination",
				printModel: "orders/print",
				delete: "orders/destroy",
				refreshData: "orders/refreshData",
				multiDelete: "orders/multiDelete",
				empty: "orders/empty",
				loadComments: 'orders/loadComments',
				postComment: 'orders/commentStore'
			}),
			async show(model) {
				this.selectedItem = model;
				this.drawerShow = true;
			},
			async comments(model) {
				this.selectedItem = model;
				this.loadComments({order_id: model.id})
				this.drawerComments = true;
			},
			commentStore(form){
				this.loadingComments = true;
				this.postComment(form)
					.then(res => {
						this.loadingComments = false;
						this.$alert(res)
						this.loadComments({order_id: this.selectedItem.id})
					})
					.catch(err => {
						this.loadingComments = false;
						this.$alert(err)
					})
			},
			controlExcelData() {
				this.excel_fields = {};
				for (let key in this.columns) {
					if (this.columns.hasOwnProperty(key)) {
					let column = this.columns[key];
					if (column.show && column.column !== "settings") {
						switch (column.column) {
						case "state_id":
							this.excel_fields[column.title] = "state.state";
							break;
						case "priority_id":
							this.excel_fields[column.title] = "priority.name";
							break;
						default:
							this.excel_fields[column.title] = column.column;
							break;
						}
					}
					}
				}
			},
			multipleDelete(items) {
				this.multiDelete({
					items: items.map(item => {
						return item.id;
					})
				})
				.then(res => {
					this.$alert(res);
					this.fetchData();
				})
				.catch(err => {
					this.$alert(err);
				});
			},
			handleCheckAllChange() {
				this.selectedItems = this.checkAll
					? (this.selectedItems = this.list)
					: [];
				this.handleCheckedItemsChange();
			},
			handleCheckedItemsChange() {
				this.checkAll = this.selectedItems.length === this.list.length;
			}
		}
	};
</script>
