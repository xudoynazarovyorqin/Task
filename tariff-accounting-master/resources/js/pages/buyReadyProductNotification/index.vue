<template>
    <div class="row table-sm mr-0 ml-0 p-0">
        <div class="crm-content-header d-flex w-100 mb-2">
            <div class="crm-content-header-left d-flex w-50">
                <div class="crm-content-header-left-item mr-3" style="width:200px">
                    <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.notifications") }}</h5>
                    <crm-refresh @c-click="refresh()"></crm-refresh>
                </div>
                <div class="crm-content-header-left-item">
                    <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search" v-model="filterForm.search" clearable></el-input>
                </div>
                <div class="crm-content-header-left-item">
                    <el-date-picker v-model="filterForm.from_date" type="date" format="yyyy-MM-dd" :value-format="date_format" size="mini" :placeholder="$t('message.from')">
                    </el-date-picker>
                </div>
                <div class="crm-content-header-left-item">
                    <el-date-picker v-model="filterForm.to_date" type="date" format="yyyy-MM-dd" :value-format="date_format" size="mini" :placeholder="$t('message.to')">
                    </el-date-picker>
                </div>
            </div>
            <div class="crm-content-header-right d-flex w-50 justify-content-end">
                <div class="crm-content-header-right-item">
                    <crm-column-settings :columns="columns" @c-change="updateColumn"></crm-column-settings>
                </div>
            </div>
        </div>
         <table class="table table-bordered table-hover"
            v-loading="loadingData"
            :element-loading-text="$t('message.loading')"
            element-loading-spinner="el-icon-loading"
        >
            <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
            <thead>
                <tr>
                    <crm-th :sort="sort" :column="columns.id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.buy_ready_product_notificationable_type" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.buy_ready_product_notificationable_id" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.products" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.status" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.body" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                    <crm-th :sort="sort" :column="columns.updated_at" @c-change="updateSort"></crm-th>
                    <crm-th v-can="'buys.create'" :sort="sort" :column="columns.success" @c-change="updateSort"></crm-th>
                    <crm-th v-can="'buys.create'" :sort="sort" :column="columns.cancel" @c-change="updateSort"></crm-th>
                </tr>
                <tr>
                    <th v-if="columns.id.show">
                        <el-input clearable size="mini" class="id_input" v-model="filterForm.id" :placeholder="columns.id.title"></el-input>
                    </th>
                    <th v-if="columns.buy_ready_product_notificationable_type.show">
                        <el-select filterable clearable :placeholder="columns.buy_ready_product_notificationable_type.title" size="mini" v-model="filterForm.buy_ready_product_notificationable_type">
                            <el-option :label="$t('message.sale')" :value="'sales'"></el-option>
                            <el-option :label="$t('message.assembly')" :value="'assemblies'"></el-option>
                        </el-select>
                    </th>
                    <th v-if="columns.buy_ready_product_notificationable_id.show">
                        <el-input size="mini" v-model="filterForm.buy_ready_product_notificationable_id" :placeholder="columns.buy_ready_product_notificationable_id.title" clearable></el-input>
                    </th>
                    <th v-if="columns.products.show"></th>
                    <th v-if="columns.status.show">
                        <el-select filterable clearable :placeholder="columns.status.title" size="mini" v-model="filterForm.status">
                            <el-option :label="'Новый'" :value="'created'"></el-option>
                            <el-option :label="'Ожидание'" :value="'waiting'"></el-option>
                            <el-option :label="'Выполнен'" :value="'completed'"></el-option>
                            <el-option :label="'Отказан'" :value="'canceled'"></el-option>
                        </el-select>
                    </th>
                    <th v-if="columns.body.show">
                        <el-input size="mini" v-model="filterForm.body" :placeholder="columns.body.title" clearable></el-input>
                    </th>
                    <th v-if="columns.created_at.show">
                        <el-date-picker v-model="filterForm.created_at" :placeholder="columns.created_at.title" size="mini" :value-format="date_format">
                        </el-date-picker>
                    </th>
                    <th v-if="columns.updated_at.show">
                        <el-date-picker v-model="filterForm.updated_at" :placeholder="columns.updated_at.title" size="mini" :value-format="date_format">
                        </el-date-picker>
                    </th>
                    <th v-can="'buys.create'" v-if="columns.success.show"></th>
                    <th v-can="'buys.create'" v-if="columns.cancel.show"></th>
                </tr>
            </thead>
            <transition-group name="flip-list" tag="tbody">
                <tr v-for="buy_notification in list" :key="buy_notification.id" :class="'cursor-pointer column '+buy_notification.status">
                    <td v-if="columns.id.show">{{ buy_notification.id }}</td>
                    <td v-if="columns.buy_ready_product_notificationable_type.show">
                        <template v-if="buy_notification.buy_ready_product_notificationable_type == 'sales'">Производство</template>
                        <template v-if="buy_notification.buy_ready_product_notificationable_type == 'assemblies'">Сборка</template>
                    </td>
                    <td v-if="columns.buy_ready_product_notificationable_id.show">{{ buy_notification.buy_ready_product_notificationable_id }}</td>
                    <td v-if="columns.products.show">
                        <span @click="showItems(buy_notification)" style="cursor: pointer; color: #3490dc;">{{ $t('message.view')}}</span>
                    </td>
                    <td v-if="columns.status.show">
                        <template v-if="buy_notification.status == 'created'">Новый</template>
                        <template v-if="buy_notification.status == 'waiting'">Ожидание</template>
                        <template v-if="buy_notification.status == 'completed'">Выполнен</template>
                        <template v-if="buy_notification.status == 'canceled'">Отказан</template>
                    </td>
                    <td v-if="columns.body.show">{{ buy_notification.body }}</td>
                    <td v-if="columns.created_at.show">{{ buy_notification.created_at | dateFormat }}</td>
                    <td v-if="columns.updated_at.show">{{ buy_notification.updated_at | dateFormat }}</td>
                    <td v-if="columns.success.show" class="settings-td" v-can="'buys.create'">
                        <el-button v-if="buy_notification.is_click_buttons" size="mini" @click="showNotify(buy_notification)" type="success" plain>Принять</el-button>
                        <el-button size="mini" v-else disabled="disabled" type="success" plain>Принять</el-button>
                    </td>
                    <td v-if="columns.cancel.show" class="settings-td" v-can="'buys.create'">
                        <el-button size="mini" v-if="buy_notification.is_click_buttons" @click="cancelReason(buy_notification)" type="danger" plain>Отказать</el-button>
                        <el-button size="mini" v-else disabled="disabled" type="danger" plain>Отказать</el-button>
                    </td>
                </tr>
            </transition-group>
        </table>
        <el-dialog :title="$t('message.products')" :visible.sync="visibleItems" width="50%" top="5vh">
            <table class="table" v-loading="loadingItems">
                <thead>
                    <tr class="crm-table-header-border-0">
                        <th> {{ $t('message.name') }}</th>
                        <th>{{ $t('message.quantity') }} </th>
                        <th>{{ $t('message.measurements') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item ,index) in items" :key="index">
                        <td>{{ (item.product) ? item.product.name : '' }}</td>
                        <td>{{ item.quantity | formatNumber }}</td>
                        <td>
                            {{ (item.product && item.product.measurement) ? item.product.measurement.name : '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </el-dialog>
        <el-dialog :title="'Отказать (ID Типа:' + (selectedItem ? selectedItem.id : '') +')'" :visible.sync="reasonVisible">
            <el-form ref="reasonForm" :model="form">
                <el-form-item label="Причина" prop="reason" :rules="[
                            { required: true, message: 'Пожалуйста, введите причину', trigger: 'blur' },
                        ]">
                    <el-input type="textarea" :rows="6" placeholder="Причина" v-model="form.reason">
                    </el-input>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="reasonVisible = false">{{ $t('message.cancel') }}</el-button>
                <el-button type="primary" @click="canceledStatus()">{{ $t('message.save') }}</el-button>
            </span>
        </el-dialog>
        <el-drawer :with-header="false" :visible.sync="drawerCreate" size="90%" ref="drawerCreate" @closed="drawerClosed('drawerCreateChild')" @opened="drawerOpened('drawerCreateChild')">
            <create drawer="drawerCreate" ref="drawerCreateChild" :notify="selectedItem" :open="drawerCreate"></create>
        </el-drawer>
    </div>
</template>

<script>
	import create from "@/pages/buyReadyProduct/components/crm-create";
	import { mapActions, mapGetters } from "vuex";
	import list from "@/utils/mixins/list";

	export default {
		mixins:[list],
		name: "BuyNotification",
		components: { create },
		data() {
			return {
				items: [],
				reasonVisible: false,
				visibleItems: false,
				loadingItems: false,
				form:{
					reason: '',
					buy_notification_id: ''
				}
			};
		},
		computed: {
			...mapGetters({
				list: "buyReadyProductNotifications/list",
				columns: "buyReadyProductNotifications/columns",
				pagination: "buyReadyProductNotifications/pagination",
				filter: "buyReadyProductNotifications/filter",
				sort: "buyReadyProductNotifications/sort"
			})
		},
		methods: {
			...mapActions({
				updateList: "buyReadyProductNotifications/index",
				updateSort: "buyReadyProductNotifications/updateSort",
				updateFilter: "buyReadyProductNotifications/updateFilter",
				updateColumn: "buyReadyProductNotifications/updateColumn",
				updatePagination: "buyReadyProductNotifications/updatePagination",
				refreshData: "buyReadyProductNotifications/refreshData",
				empty: "buyReadyProductNotifications/empty",
				show: 'buyReadyProductNotifications/show',
				cancel: "buyReadyProductNotifications/cancel",
			}),
			showItems(model) {
				this.visibleItems = true;
                this.loadingItems = true;
                this.show(model.id)
					.then(res => {
						if (res.data && res.data.result) {
							this.items = res.data.result.not_enough_products;
						}
						this.loadingItems = false;
					})
					.catch(err => {
						this.loadingItems = false;
						this.$alert(err)
					});
			},
			cancelReason(model) {
				this.selectedItem = model;
				this.reason = '';
				this.reasonVisible = true;
			},
			canceledStatus() {
				let self = this;
				this.$refs['reasonForm'].validate((valid) => {
						if (valid) {
							this.form.buy_notification_id = this.selectedItem.id;
							this.cancel(this.form)
								.then(res => {
									this.$alert(res);
									this.reasonVisible = false;
									this.fetchData();
								})
								.catch(err => {
									this.$alert(err);
								});
						} else {
							return false;
						}
				});
			},
			showNotify(model) {
				this.selectedItem = model;
				this.drawerCreate = true;
			},
		}
	};
</script>

<style>
.column.waiting {
  background-color: #95ea95;
}

.column.completed {
  background-color: #a4d1ff;
}

.column.canceled {
  background-color: #fd7b7b;
}
</style>