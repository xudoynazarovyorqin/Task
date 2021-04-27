<template>
 <div class="row table-sm mr-0 ml-0 p-0">
    <div class="row table-sm mr-0 ml-0 p-0 mb-3 width-100">
        <div class="col-4 p-0 align-self-center font-weight-bold">
            <h5 class="d-inline mr-2 font-weight-bold">Брак продукции</h5>
            <crm-refresh @c-click="refresh()"></crm-refresh>
        </div>
        <div class="col-8 align-items-center align-self-center text-right pr-0">
            <el-dropdown v-can="'defectProducts.create'" @command="createHandleCommand">
                <el-button size="mini" icon="el-icon-circle-plus-outline">
                    Создать<i class="el-icon-arrow-down el-icon--right"></i>
                </el-button>
                <el-dropdown-menu slot="dropdown">
                    <el-dropdown-item command="drawerCreateFromShipment"> Возврат отгрузки</el-dropdown-item>
                </el-dropdown-menu>
            </el-dropdown>
            <crm-column-settings :columns="columns" @c-change="updateColumn"></crm-column-settings>
        </div>
    </div>
    <table class="table table-bordered table-hover" v-loading="loadingData">
        <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
        <thead>
            <tr>
                <crm-th :sort="sort" :column="columns.product_id" @c-change="updateSort"></crm-th>
                <th>{{ product_columns.code.title }}</th>
                <th>{{ product_columns.vendor_code.title }}</th>
                <th>{{ product_columns.country_id.title }}</th>
                <crm-th :sort="sort" :column="columns.quantity" @c-change="updateSort"></crm-th>
                <th>{{ product_columns.measurement_id.title }}</th>
            </tr>
            <tr>
                <th v-if="columns.product_id.show">
                    <el-input clearable size="mini" v-model="filterForm.product_id"
                        :placeholder="columns.product_id.title"></el-input>
                </th>
                <th></th>
                <th></th>
                <th></th>
                <th v-if="columns.quantity.show"></th>
                <th></th>
            </tr>
        </thead>
        <transition-group name="flip-list" tag="tbody">
            <tr v-for="(defect_product,index) in list" :key="index+'aaaaaaaa-99'" class="cursor-pointer"
                @dblclick="show(defect_product.product)">
                <td v-if="columns.product_id.show">{{ (defect_product.product) ? defect_product.product.name : '' }}
                </td>
                <td>{{ (defect_product.product) ? defect_product.product.code : '' }}</td>
                <td>{{ (defect_product.product) ? defect_product.product.vendor_code : '' }}</td>
                <td>{{ (defect_product.product) ? (defect_product.product.country) ? defect_product.product.country.name :'' : '' }}
                </td>
                <td v-if="columns.quantity.show">{{ defect_product.quantity | formatNumber }}</td>
                <td>{{ (defect_product.product) ? (defect_product.product.measurement) ? defect_product.product.measurement.name :'' : '' }}
                </td>
            </tr>
        </transition-group>
    </table>
    <el-drawer title="История" :visible.sync="drawerShow" size="60%" :drawer="drawerShow" @close="drawerShow = false">
        <div>
            <crm-history></crm-history>
        </div>
    </el-drawer>

    <el-drawer title="Возврат отгрузки" :visible.sync="drawerCreateFromShipment" size="85%"
        :drawer="drawerCreateFromShipment" @open="reopen = true" @close="reopen = false">
        <div>
            <crm-create-from-shipment :reloadModel="reopen" @c-close="closeDrawer"></crm-create-from-shipment>
        </div>
    </el-drawer>
</div>
</template>
<script>

import { mapGetters, mapActions } from "vuex";
import CrmHistory from './include/crm-history';
import CrmCreateFromShipment from './include/crm-create-from-shipment';

export default {
    components:{
        'crm-history': CrmHistory,
        'crm-create-from-shipment': CrmCreateFromShipment
    },
    name: "DefectProducts",
      data() {
        return {
            filterForm: {},
            drawerShow: false,
            drawerCreateFromShipment: false,
            reopen: false,
            loadingData: false
        };
      },
    created() {
        this.filterForm = JSON.parse(JSON.stringify(this.filter));
    },
    mounted(){
        this.fetchData();
    },
    computed:{
        ...mapGetters({
            list: 'defectProduct/list',
            columns: "defectProduct/columns",
            pagination: "defectProduct/pagination",
            filter: "defectProduct/filter",
            sort: "defectProduct/sort",
            product_columns: 'products/columns'
        })
    },
    watch: {
        filterForm: {
            handler: async function(newVal, oldVal) {
                await this.updateFilter(newVal);
                this.fetchData();
            },
            deep: true
        },
        sort: {
            handler: async function(newVal, oldVal) {
                await this.fetchData();
            }
        },
        pagination: {
            handler: async function(newVal, oldVal) {
                await this.fetchData();
            },
            deep: true
        }
    },
    methods: {
      ...mapActions({
          updateList: 'defectProduct/index',
          updateSort: "defectProduct/updateSort",
          updateFilter: "defectProduct/updateFilter",
          updateColumn: "defectProduct/updateColumn",
          updatePagination: "defectProduct/updatePagination",
          refreshData: 'defectProduct/refreshData',
          history: 'defectProduct/history'
      }),

      fetchData() {
          const query = { ...this.filter, ...this.pagination, ...this.sort };
          if (!this.loadingData) {
              this.loadingData = true
                this.updateList(query).then(res => {
              this.loadingData = false
                }).catch(err => {
                                  this.loadingData = false
                });              
          }
      },

      refresh() {
          this.refreshData()
              .then(res => {
                  this.filterForm = JSON.parse( JSON.stringify( this.filter ))
                  this.fetchData();
              })
              .catch(err => {                
              })
      },

      show(product){
          this.history(product.id)
          .then(res => {
              this.drawerShow = true
          })
          .catch(err => {
              this.$alert(err)
          })
      },

      closeDrawer(obj) {
        if (obj.reload) {
          this.fetchData();
        }
        if (obj.drawer) {
          this[obj.drawer] = false;
        }
      },

      createHandleCommand(command){
        this[command] = true;
      }
  }
};
</script>
