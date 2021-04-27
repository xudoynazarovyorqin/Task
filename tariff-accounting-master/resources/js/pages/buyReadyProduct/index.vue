<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
                <div class="crm-content-header-left-item mr-3" style="width:260px">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.buy_ready_products") }}</h5>
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
                    <el-button v-can="'buyReadyProducts.create'" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
                </div>
                <div class="crm-content-header-right-item">
                    <crm-edit-dropdown v-can="'buyReadyProducts.delete'" :items="selectedItems" @delete="multipleDelete">
                    </crm-edit-dropdown>
                </div>
                <div class="crm-content-header-right-item">
                    <export-excel :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.buys')" :name="$t('message.buys')">
                        <el-button size="mini">
                            <i class="el-icon-document-delete"></i> {{ $t('message.download_excel') }}
                        </el-button>
                    </export-excel>
                </div>
                <div class="crm-content-header-right-item">
                    <router-link :to="{name: 'buyReadyProductNotifications.index'}">
                        <el-button size="mini">
                            <i class="el-icon-message-solid"></i>
                            <span class="messageCount">{{ messageCount }}</span>
                        </el-button>
                    </router-link>
                </div>
                <div class="crm-content-header-right-item">
                    <crm-column-settings :columns="columns" @c-change="updateColumn"></crm-column-settings>
                </div>
            </div>
        </div>
        <div class="w-100 text-center mb-1 crm">
            <el-tag class="border-0" effect="plain">{{ $t('message.total_amount') }}: {{ total | formatMoney(2) }}</el-tag>
            <el-tag class="border-0" effect="plain" type="success">{{ $t('message.paid') }}: {{ totalPaid | formatMoney(2) }}</el-tag>
            <el-tag class="border-0" effect="plain" type="danger">{{ $t('message.not_paid') }}: {{ (total - totalPaid) | formatMoney(2) }}</el-tag>
        </div>
        <table class="table table-bordered table-hover" v-loading="loadingData" :element-loading-text="$t('message.loading')" element-loading-spinner="el-icon-loading">
            <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
            <thead>
                <tr>
                    <th></th>
                    <crm-th :sort="sort" :column="columns.id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.datetime" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.user_id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.provider_id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.contract_provider_id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.products" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.paid" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.date" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.total_price" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.paid_price" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.not_paid" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.status_id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.comment" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.object_id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.object_type" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.is_warehouse" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
                    <crm-th v-can="'buyReadyProducts.coming'" :sort="sort" :column="columns.success" @c-change="updateSort"></crm-th>
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
                        <el-date-picker v-model="filterForm.datetime" :placeholder="columns.datetime.title" size="mini"  :value-format="date_format">
                        </el-date-picker>
                    </th>
                    <th v-if="columns.user_id.show">
                        <users v-model="filterForm.user_id" size="mini"></users>
                    </th>
                    <th v-if="columns.provider_id.show">
                        <providers v-model="filterForm.provider_id" size="mini" ></providers>
                    </th>
                    <th v-if="columns.contract_provider_id.show">
                        <contracts v-model="filterForm.contract_provider_id" :provider_id="this.filterForm.provider_id" size="mini"></contracts>
                    </th>
                    <th v-if="columns.products.show">
                        <el-input size="mini" disabled :placeholder="columns.products.title" clearable></el-input>
                    </th>
                    <th v-if="columns.paid.show">
                        <el-select filterable clearable :placeholder="columns.paid.title" size="mini" v-model="filterForm.paid">
                            <el-option :label="$t('message.paid')" :value="1"></el-option>
                            <el-option :label="$t('message.not_paid')" :value="0"></el-option>
                        </el-select>
                    </th>
                    <th v-if="columns.date.show">
                        <el-date-picker v-model="filterForm.date" :placeholder="columns.date.title" size="mini"></el-date-picker>
                    </th>
                    <th v-if="columns.total_price.show">
                        <el-input size="mini" v-model="filterForm.total_price" :placeholder="columns.total_price.title" clearable></el-input>
                    </th>
                    <th v-if="columns.paid_price.show">
                        <el-input size="mini" v-model="filterForm.paid_price" :placeholder="columns.paid_price.title" clearable></el-input>
                    </th>
                    <th v-if="columns.not_paid.show">
                        <el-input size="mini" :placeholder="columns.not_paid.title" disabled></el-input>
                    </th>
                    <th v-if="columns.status_id.show">
                        <statues v-model="filterForm.status_id" size="mini"></statues>
                    </th>
                    <th v-if="columns.comment.show">
                        <el-input size="mini" v-model="filterForm.comment" :placeholder="columns.comment.title" clearable>
                        </el-input>
                    </th>
                    <th v-if="columns.object_id.show">
                        <el-input size="mini" v-model="filterForm.object_id" :placeholder="columns.object_id.title" clearable></el-input>
                    </th>
                    <th v-if="columns.object_type.show">
                        <el-select filterable clearable :placeholder="columns.object_type.title" size="mini" v-model="filterForm.object_type">
                            <el-option :label="$t('message.assembly')" :value="'assemblies'"></el-option>
                        </el-select>
                    </th>
                    <th v-if="columns.is_warehouse.show">
                        <el-select filterable clearable :placeholder="columns.is_warehouse.title" size="mini" v-model="filterForm.is_warehouse">
                            <el-option :label="$t('message.yes')" :value="1"></el-option>
                            <el-option :label="$t('message.no')" :value="0"></el-option>
                        </el-select>
                    </th>
                    <th v-if="columns.created_at.show">
                        <el-date-picker v-model="filterForm.created_at" :placeholder="columns.created_at.title" size="mini"  :value-format="date_format">
                        </el-date-picker>
                    </th>
                    <th v-if="columns.updated_at.show">
                        <el-date-picker v-model="filterForm.updated_at" :placeholder="columns.updated_at.title" size="mini"  :value-format="date_format">
                        </el-date-picker>
                    </th>
                    <th v-can="'buyReadyProducts.coming'" v-if="columns.success.show"></th>
                    <th v-if="columns.settings.show"></th>
                </tr>
            </thead>
            <transition-group name="flip-list" tag="tbody">
                <tr v-for="buy in list" :key="buy.id" :class="{'table-active': (selectedItems.indexOf(buy) > -1)}" class="cursor-pointer" @dblclick="show(buy)" :title="$t('message.Double tap to view')">
                    <td>
                        <div class="custom-control custom-checkbox d-inline-block">
                            <input type="checkbox" class="custom-control-input" v-model="selectedItems" :value="buy" :id="'customCheck'+buy.id" @change="handleCheckedItemsChange" />
                            <label class="custom-control-label cursor-pointer" :for="'customCheck'+buy.id"></label>
                        </div>
                    </td>
                    <td v-if="columns.id.show">{{ buy.id }}</td>
                    <td v-if="columns.datetime.show">{{ buy.datetime }}</td>
                    <td v-if="columns.user_id.show">{{ (buy.user) ? (buy.user.name) : '' }}</td>
                    <td v-if="columns.provider_id.show">{{ (buy.provider) ? buy.provider.name : '' }}</td>
                    <td v-if="columns.contract_provider_id.show">{{ buy.contract_provider_id }}</td>
                    <td v-if="columns.products.show">
                        <span @click="showItems(buy)" style="cursor: pointer; color: #3490dc;">{{ $t('message.view')}}</span>
                    </td>
                    <td v-if="columns.paid.show">{{ buy.paid }}</td>
                    <td v-if="columns.date.show">{{ buy.date }}</td>
                    <td v-if="columns.total_price.show">{{ buy.total_price | formatMoney }}</td>
                    <td v-if="columns.paid_price.show">{{ buy.paid_price | formatMoney }}</td>
                    <td v-if="columns.not_paid.show">{{ buy.not_paid | formatMoney }}</td>
                    <td v-if="columns.status_id.show">{{ buy.status ? buy.status.state : '' }}</td>
                    <td v-if="columns.comment.show">{{ buy.comment }}</td>
                    <td v-if="columns.object_id.show">{{ buy.object_id }}</td>
                    <td v-if="columns.object_type.show">{{ buy.object_type }}</td>
                    <td v-if="columns.is_warehouse.show">{{ buy.is_warehouse }}</td>
                    <td v-if="columns.created_at.show">{{ buy.created_at | dateFormat }}</td>
                    <td v-if="columns.updated_at.show">{{ buy.updated_at | dateFormat }}</td>
                    <td v-can="'buyReadyProducts.coming'" v-if="columns.success.show" class="settings-td">
                        <el-button size="mini" round :type="receiveBtnType(buy)" @click="coming(buy)">Приход ( {{ Math.ceil(100 - (100 * (buy.waiting_items_count / buy.items_count))) }}%)</el-button>
                    </td>
                    <td v-if="columns.settings.show" class="settings-td">
                        <crm-setting-dropdown :model="buy" :name="'buyReadyProducts'" :actions="actions" @edit="edit" @print="print" @delete="destroy" @show="show"></crm-setting-dropdown>
                    </td>
                </tr>
            </transition-group>
        </table>
        <el-dialog :title="$t('message.products')" :visible.sync="visibleItems" width="80%">
            <table class="table" v-loading="loadingItems">
                <thead>
                    <tr class="crm-table-header-border-0">
                        <th> {{ $t('message.name') }}</th>
                        <th> {{ $t('message.quantity') }} </th>
                        <th> {{ $t('message.receive_quantity') }}</th>
                        <th>{{ $t('message.measurements') }}</th>
                        <th> {{ $t('message.buy_price') }}</th>
                        <th> {{ $t('message.total_amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in items" :key="index">
                        <td>{{ (item.product) ? item.product.name : '' }}</td>
                        <td>{{ item.qty_weight | formatNumber }}</td>
                        <td>{{ (item.qty_weight - item.not_enough) | formatNumber }}</td>
                        <td>{{ (item.product && item.product.measurement) ? item.product.measurement.name : '' }}</td>
                        <td>{{ item.buy_price | formatNumber }} {{ (item.currency) ? item.currency.symbol : '' }}</td>
                        <td>{{ (item.qty_weight * item.buy_price) | formatNumber }} {{ (item.currency) ? item.currency.symbol : '' }}</td>
                    </tr>
                </tbody>
            </table>
        </el-dialog>
        <el-dialog :title="$t('message.buy_detail')" :visible.sync="visibleShow" width="80%" top="5vh">
            <crm-show :buy="selectedItem" :open="visibleShow"></crm-show>
        </el-dialog>
        <el-drawer v-can="'buyReadyProducts.create'" :with-header="false" :visible.sync="drawerCreate" size="90%" ref="drawerCreate" @closed="drawerClosed('drawerCreateChild')"  @opened="drawerOpened('drawerCreateChild')">
            <crm-create drawer="drawerCreate" ref="drawerCreateChild"></crm-create>
        </el-drawer>
        <el-drawer v-can="'buyReadyProducts.edit'" :with-header="false" :visible.sync="drawerUpdate" size="90%" ref="drawerUpdate" @closed="drawerClosed('drawerUpdateChild')"  @opened="drawerOpened('drawerUpdateChild')">
            <crm-update drawer="drawerUpdate" :buy="selectedItem" ref="drawerUpdateChild"></crm-update>
        </el-drawer>
        <el-drawer v-can="'buyReadyProducts.coming'" :with-header="false" :visible.sync="drawerReceive" size="90%" ref="drawerReceive" @closed="drawerClosed('drawerReceiveChild')" @opened="drawerOpened('drawerReceiveChild')">
            <receive drawer="drawerReceive" :buy="selectedItem" ref="drawerReceiveChild"></receive>
        </el-drawer>
    </div>
</template>
<script>
	import CrmCreate from "./components/crm-create";
	import CrmUpdate from "./components/crm-update";
	import CrmShow from "./components/crm-show";
	import receive from "./components/crm-receive";
	import { mapActions, mapGetters } from "vuex";
    import providers from '@inventory/crm-provider-select';
    import users from '@inventory/crm-user-select';
    import contracts from '@inventory/crm-contract-provider-select';
    import statues from '@inventory/crm-state-select';
    import list from "@/utils/mixins/list";

	export default {
		name: "BuyReadyProductsList",
		mixins: [list],
		components: {
			CrmCreate,
			CrmUpdate,
			CrmShow,
			receive,
            providers,
            users,
            contracts,
            statues
		},
		data() {
			return {
				drawerReceive: false,
				items: [],
                visibleItems: false,
                loadingItems: false,
                visibleShow: false,
                messageCount: 0,
			};
		},
		computed: {
			...mapGetters({
				list: "buyReadyProducts/list",
				columns: "buyReadyProducts/columns",
				pagination: "buyReadyProducts/pagination",
				filter: "buyReadyProducts/filter",
				sort: "buyReadyProducts/sort",
			}),
			actions: function() {
				return ["show", "print", "edit", "delete"];
			},
			total: function() {
				return _.sumBy(this.list,'total_price')
			},
			totalPaid: function() {
				return _.sumBy(this.list,'paid_price')
            },
		},
		async mounted() {
			this.fetchMessages();
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
				updateList: "buyReadyProducts/index",
				updateSort: "buyReadyProducts/updateSort",
				updateFilter: "buyReadyProducts/updateFilter",
				updateColumn: "buyReadyProducts/updateColumn",
				updatePagination: "buyReadyProducts/updatePagination",
				refreshData: "buyReadyProducts/refreshData",
				delete: "buyReadyProducts/destroy",
				multiDelete: "buyReadyProducts/multiDelete",
				printModel: "buyReadyProducts/print",
                loadItems: 'buyReadyProducts/items',
                empty: 'buyReadyProducts/empty',
            }),
			fetchMessages() {
				this.$store.dispatch("buyReadyProductNotifications/count")
					.then(res => {
                        this.messageCount = res.count;
					})
					.catch(err => {
						this.$alert(err);
					});
			},
			async show(buy) {
                this.selectedItem = buy;
                this.visibleShow = true;
			},
			async coming(buy) {
                this.selectedItem = buy;
                this.drawerReceive = true;
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
			controlExcelData() {
				this.excel_fields = {};
				for (let key in this.columns) {
					if (this.columns.hasOwnProperty(key)) {
					let column = this.columns[key];
					if (column.show && (column.column !== "settings" && column.column !== "success" && column.column !== "products")) {
						switch (column.column) {
						case "user_id":
							this.excel_fields[column.title] = "user.name";
							break;
						case "provider_id":
							this.excel_fields[column.title] = "provider.name";
							break;
						case "status_id":
							this.excel_fields[column.title] = "status.name";
							break;
						default:
							this.excel_fields[column.title] = column.column;
							break;
						}
					}
					}
				}
			},
			showItems(model) {
                this.visibleItems = true;
                this.loadingItems = true;
                this.loadItems({id: model.id})
                .then(res => {
                    this.items = (res) ? res.items : [];
                    this.loadingItems = false;
                })
                .catch(err => {
                    this.loadingItems = false;
                    this.$alert(err)
                })
            },
            receiveBtnType(buy){
                if (buy.waiting_items_count == 0) {
                    return 'success';
                }else{
                    if (buy.waiting_items_count == buy.items_count) {
                        return 'danger';
                    }else{
                        return 'warning';
                    }
                }
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