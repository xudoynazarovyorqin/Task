<template>
    <div class="row table-sm mr-0 ml-0">
        <div class="col-12">
            <el-tabs v-model="activeTab">
                <el-tab-pane label="История " name="first">
                    <!-- <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>{{columns.sale_id.title}}</th>
                                <th>{{columns.quantity.title}}</th>
                                <th>{{columns.date.title}}</th>
                                <th>{{columns.created_at.title}}</th>
                            </tr>
                        </thead>
                        <transition-group name="flip-list" tag="tbody">
                            <tr v-for="defect_product in prodcut_history" :key="'defect_products-'+defect_product.id" class="cursor-pointer">
                                <td>{{ (defect_product.sale) ? defect_product.sale.id : ''}}</td>
                                <td>{{ defect_product.quantity | formatNumber }}</td>
                                <td>{{ defect_product.date }}</td>
                                <td>{{ defect_product.created_at | dateFormat }}</td>
                            </tr>
                        </transition-group>
                    </table> -->

                    <el-table :data="prodcut_history" style="width: 100%">
                        <el-table-column type="expand">
                          <template slot-scope="props">
                            <p v-for="item in props.row.defect_product_reasons">
                                {{ (item.reason) ? item.reason.name : '' }}: {{ item.quantity }}
                            </p>
                          </template>
                        </el-table-column>
                        <el-table-column :label="columns.defectable_type.title" prop="defectable_name"></el-table-column>
                        <el-table-column :label="columns.defectable_id.title" prop="defectable_id"></el-table-column>
                        <el-table-column :label="columns.quantity.title" prop="quantity"></el-table-column>
                        <el-table-column :label="columns.date.title" prop="date"></el-table-column>
                        <el-table-column :label="columns.created_at.title" prop="created_at"></el-table-column>
                    </el-table>
                </el-tab-pane>
            </el-tabs>
        </div>
        
    </div>
</template>
<script>
    import {mapGetters,mapActions} from 'vuex'
    export default {
        data(){
            return {
                activeTab: 'first',
            }
        },
        computed: {
            ...mapGetters({
                prodcut_history: 'defectProduct/history',
                columns: 'defectProduct/columns',
            })
        }
    }
</script>
