<template>
  <div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
        <div class="crm-content-header-left d-flex w-50">
            <div class="crm-content-header-left-item mr-3" style="width:200px">
                <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.warehouse_materials")  }}</h5>
                <crm-refresh @c-click="refresh()"></crm-refresh>
            </div>
        </div>
        <div class="crm-content-header-right d-flex w-50 justify-content-end">
             <!-- <div class="crm-content-header-right-item">
                <el-button size="mini" @click="drawerCreateComing = true" icon="el-icon-circle-plus-outline">
                    {{ $t('message.back_from_reworking')}}
                </el-button>
            </div> -->
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
                <crm-th :sort="sort" :column="columns.id" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.name" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.code" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.sku" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.remainder" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.booked" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.critical_weight" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.measurement_id" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.buy_price" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.total_buy_price" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.price" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.total_price" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.settings" @c-change="updateSort"></crm-th>
            </tr>
            <tr>
                <th v-if="columns.id.show" style="width:40px"></th>
                <th v-if="columns.name.show">
                    <materials v-model="filterForm.id" size="mini"></materials>
                </th>
                <th v-if="columns.code.show">
                    <el-input clearable size="mini" v-model="filterForm.code" :placeholder="columns.code.title">
                    </el-input>
                </th>
                <th v-if="columns.sku.show">
                    <el-input clearable size="mini" v-model="filterForm.sku" :placeholder="columns.sku.title">
                    </el-input>
                </th>
                <th v-if="columns.remainder.show">
                    <el-input disabled size="mini"></el-input>
                </th>
                <th v-if="columns.booked.show">
                    <el-input disabled size="mini"></el-input>
                </th>
                <th v-if="columns.critical_weight.show">
                    <el-input clearable size="mini" v-model="filterForm.critical_weight"
                        :placeholder="columns.critical_weight.title"></el-input>
                </th>
                <th v-if="columns.measurement_id.show">
                    <measurements v-model="filterForm.measurement_id" size="mini"></measurements>
                </th>
                <th v-if="columns.buy_price.show">
                    <el-input disabled size="mini"></el-input>
                </th>
                <th v-if="columns.total_buy_price.show">
                    <el-input disabled size="mini"></el-input>
                </th>
                <th v-if="columns.price.show">
                    <el-input disabled size="mini"></el-input>
                </th>
                <th v-if="columns.total_price.show">
                    <el-input disabled size="mini"></el-input>
                </th>
                <th v-if="columns.settings.show"></th>
            </tr>
        </thead>
        <transition-group name="flip-list" tag="tbody">
            <tr v-for="material in list" :key="'material'+material.id" class="cursor-pointer" :class="(parseFloat(material.remainder) < parseFloat(material.critical_weight)) ? 'table-danger' : ''">
                <td v-if="columns.id.show">{{ material.id }}</td>
                <td v-if="columns.name.show">{{ material.name }}</td>
                <td v-if="columns.code.show">{{ material.code }}</td>
                <td v-if="columns.sku.show">{{ material.sku }}</td>
                <td v-if="columns.remainder.show">{{ parseFloat(material.remainder) | formatNumber }} {{ material | addMeasurement(material.remainder)}}</td>
                <td v-if="columns.booked.show">{{ parseFloat(material.booked) | formatNumber }} {{ material | addMeasurement(material.booked)}}</td>
                <td v-if="columns.critical_weight.show">{{ material.critical_weight }}</td>
                <td v-if="columns.measurement_id.show">{{ (material.measurement) ? material.measurement.name : '' }}</td>
                <td v-if="columns.buy_price.show">{{ material.buy_price | formatMoney(2) }}</td>
                <td v-if="columns.total_buy_price.show">{{ material.total_buy_price | formatMoney(4) }}</td>
                <td v-if="columns.price.show">{{ material.price | formatMoney(2) }}</td>
                <td v-if="columns.total_price.show">{{ material.total_price | formatMoney(4) }}</td>
                <td>
                    <el-button size="mini" type="success" @click="show(material)" icon="el-icon-notebook-2 el-icon-left">{{ $t('message.details')}} </el-button>
                </td>
            </tr>
        </transition-group>
    </table>
    <el-drawer :with-header="false" :visible.sync="drawerComing" size="90%" ref="drawerComing" @closed="drawerClosed('drawerComingChild')" @opened="drawerOpened('drawerComingChild')">
        <crm-coming drawer="drawerComing" :open="drawerComing" :material="selectedItem" ref="drawerComingChild"></crm-coming>
    </el-drawer>
    <el-drawer :with-header="false" :visible.sync="drawerCreateComing" size="98%" ref="drawerCreateComing" @closed="drawerClosed('drawerCreateComingChild')" @opened="drawerOpened('drawerCreateComingChild')">
        <crm-create-coming drawer="drawerCreateComing" ref="drawerCreateComingChild"></crm-create-coming>
    </el-drawer>
</div>
</template>
<script>
    import { mapGetters, mapActions } from "vuex";
    import CrmComing from "./components/crm-coming";
    import CrmCreateComing from "./components/crm-create-coming";
    import list from '@/utils/mixins/list';
    import materials from '@inventory/crm-material-select';
    import measurements from '@inventory/crm-measurement-select';

    export default {
        mixins: [list],
        components: { CrmComing, CrmCreateComing, materials, measurements },
        name: "WarehouseMaterialList",
        data() {
            return {
                drawerComing: false,
                drawerCreateComing: false,
                reopenComing: false,
            };
        },
        computed:{
            ...mapGetters({
                list: 'warehouseMaterials/list',
                columns: "warehouseMaterials/columns",
                pagination: "warehouseMaterials/pagination",
                filter: "warehouseMaterials/filter",
                sort: "warehouseMaterials/sort",
            }),
        },
        methods: {
            ...mapActions({
                updateList: 'warehouseMaterials/index',
                updateSort: "warehouseMaterials/updateSort",
                updateFilter: "warehouseMaterials/updateFilter",
                updateColumn: "warehouseMaterials/updateColumn",
                updatePagination: "warehouseMaterials/updatePagination",
                refreshData: 'warehouseMaterials/refreshData',
            }),
            show(material) {
                this.selectedItem = material;
                this.drawerComing = true;
            }
        }
    };
</script>
