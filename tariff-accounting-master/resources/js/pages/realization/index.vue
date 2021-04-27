<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
                <div class="crm-content-header-left-item mr-3" style="width:220px">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.Realizations") }}</h5>
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
                    <el-button v-can="'realizations.create'" size="mini" @click="drawerCreate = true" icon="el-icon-circle-plus-outline"> {{ $t('message.create') }} </el-button>
                </div>
                <div class="crm-content-header-right-item">
                    <crm-edit-dropdown v-can="'realizations.delete'" :items="selectedItems" @delete="multipleDelete">
                    </crm-edit-dropdown>
                </div>
                <div class="crm-content-header-right-item">
                    <export-excel :data="list" :fields="excel_fields" @fetch="controlExcelData()" :worksheet="$t('message.Realizations')" :name="$t('message.Realizations')">
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
                    <crm-th :sort="sort" :column="columns.materials" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.realizationable_type" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.realizationable_id" @c-change="updateSort"></crm-th>
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
                        <el-date-picker v-model="filterForm.datetime" :placeholder="columns.datetime.title" size="mini" :value-format="date_format">
                        </el-date-picker>
                    </th>
                    <th v-if="columns.user_id.show">
                        <users size="mini" v-model="filterForm.user_id"></users>
                    </th>
                    <th v-if="columns.materials.show">
                        <el-input size="mini" disabled :placeholder="columns.materials.title" clearable></el-input>
                    </th>
                    <th v-if="columns.realizationable_type.show">
                        <realizationable-types v-model="filterForm.realizationable_type" size="mini"></realizationable-types>
                    </th>
                    <th v-if="columns.realizationable_id.show">
                        <el-input v-model="filterForm.realizationable_id" size="mini" clearable></el-input>
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
                <tr v-for="item in list" :key="item.id" class="cursor-pointer">
                    <td>
                        <div class="custom-control custom-checkbox d-inline-block">
                            <input type="checkbox" class="custom-control-input" v-model="selectedItems" :value="item" :id="'customCheck'+item.id" @change="handleCheckedItemsChange">
                            <label class="custom-control-label cursor-pointer" :for="'customCheck'+item.id"></label>
                        </div>
                    </td>
                    <td v-if="columns.id.show">{{ item.id }}</td>
                    <td v-if="columns.datetime.show">{{ item.datetime }}</td>
                    <td v-if="columns.user_id.show">{{ item.user ? item.user.name : '' }}</td>
                    <td v-if="columns.materials.show">
                        <span @click="showItems(item)" style="cursor: pointer; color: #3490dc;">
                            {{ $t('message.view')}}
                        </span>
                    </td>
                    <td v-if="columns.realizationable_type.show">{{ item.realizationable_type ? $t('message.' + item.realizationable_type) : '' }}</td>
                    <td v-if="columns.realizationable_id.show">{{ item.realizationable_id | formatNumber }}</td>
                    <td v-if="columns.created_at.show">{{ item.created_at | dateFormat }}</td>
                    <td v-if="columns.updated_at.show">{{ item.updated_at | dateFormat }}</td>
                    <td v-if="columns.settings.show" class="settings-td">
                        <crm-setting-dropdown :model="item" name="realizations" :actions="actions" @edit="edit" @delete="destroy"></crm-setting-dropdown>
                    </td>
                </tr>
            </transition-group>
        </table>
        <el-dialog :title="$t('message.materials')" :visible.sync="visibleItems" width="80%">
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
                        <td>{{ (item.material) ? item.material.name : '' }}</td>
                        <td>{{ item.quantity | formatNumber }} {{ item.material | addMeasurement(item.quantity) }}</td>
                        <td>{{ item.issued_from_booked | formatNumber }} {{ item.material | addMeasurement(item.issued_from_booked) }}</td>
                        <td>
                            {{ (item.material && item.material.measurement) ? item.material.measurement.name : '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </el-dialog>
        <el-drawer :with-header="false" :visible.sync="drawerCreate" size="90%" ref="drawerCreate" @opened="drawerOpened('drawerCreateChild')"  @closed="drawerClosed('drawerCreateChild')">
            <crm-create ref="drawerCreateChild" drawer="drawerCreate"></crm-create>
        </el-drawer>
        <el-drawer :with-header="false" :visible.sync="drawerUpdate" size="90%" ref="drawerUpdate" @opened="drawerOpened('drawerUpdateChild')"  @closed="drawerClosed('drawerUpdateChild')">
            <crm-update :realization="selectedItem" ref="drawerUpdateChild" drawer="drawerUpdate"></crm-update>
        </el-drawer>
    </div>
</template>
<script>

    import { mapGetters, mapActions } from "vuex";
    import CrmCreate from "./components/crm-create";
    import CrmUpdate from "./components/crm-update";
    import list from "@/utils/mixins/list";
    import users from '@inventory/crm-user-select';
    import realizationableTypes from '@inventory/crm-realizationable-type-select';

    export default {
        name: "realizations",
        mixins: [list],
        components: { CrmCreate , CrmUpdate, users, realizationableTypes},
        data(){
            return {
                visibleItems: false,
                loadingItems: false,
                items: [],
            }
        },
        computed:{
            ...mapGetters({
                list: 'realizations/list',
                columns: "realizations/columns",
                pagination: "realizations/pagination",
                filter: "realizations/filter",
                sort: "realizations/sort",
            }),
            actions: function() {
              return ['edit','delete'];
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
                updateList: 'realizations/index',
                updateSort: "realizations/updateSort",
                updateFilter: "realizations/updateFilter",
                updateColumn: "realizations/updateColumn",
                updatePagination: "realizations/updatePagination",
   				multiDelete: "realizations/multiDelete",
                empty: 'realizations/empty',
                delete: 'realizations/destroy',
                refreshData: 'realizations/refreshData',
                loadItems: 'realizations/items'
            }),
            showItems(model) {
                this.visibleItems = true;
                this.loadingItems = true;
                this.loadItems({realization_id: model.id})
                .then(res => {                    
                    this.items = res.data ? res.data.realization_materials : [];
                    this.loadingItems = false;
                })
                .catch(err => {
                    this.loadingItems = false;
                    this.$alert(err)
                })
            },
            controlExcelData() {
				this.excel_fields = {};
				for (let key in this.columns) {
					if (this.columns.hasOwnProperty(key)) {
					let column = this.columns[key];
					if (column.show && (column.column !== "settings" && column.column !== "success" && column.column !== "materials")) {
						switch (column.column) {
						case "user_id":
							this.excel_fields[column.title] = "user.name";
							break;
						case "realizationable_type":
							this.excel_fields[column.title] = {
                                    field: 'realizationable_type',
                                    callback: (value) => {
                                        return this.$t('message.' + value);
                                    }
                                };
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
				}).then(res => {
						this.$alert(res);
						this.fetchData();
					})
					.catch(err => {
						this.$alert(err);
					});
			},
            handleCheckAllChange() {
				this.selectedItems = this.checkAll ? this.list : [];
				this.handleCheckedItemsChange();
			},
			handleCheckedItemsChange() {
				this.checkAll = this.selectedItems.length === this.list.length;
			},
        }
    };
</script>
