<template>
    <el-row>
        <header id="el-drawer__title" class="el-drawer__header">
            <span> {{ $t('message.edit') }} {{ $t('message.Distribution of costs') + ' ' + ($t('message.warehouse_materials')) | lowerFirst }}</span>
            <el-button v-can="['distribution_costs.update']" type="success" size="small" class="mr-1" :loading="waiting"
                @click="submit(false)"> {{ $t('message.save') }}</el-button>
            <el-button v-can="['distribution_costs.update']" type="primary" size="small" class="mr-1" :loading="waiting"
                @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
            </el-button>
        </header>
        <el-main class="pt-2" v-loading="loading">
            <el-card class="box-card crm-card-pt-1">
                <el-form ref="form" :model="form" :rules="rules" :label-position="'right'" label-width="150px">
                    <el-col>
                        <el-form-item size="small" prop="number" class="d-inline-flex crm-document-number">
                            <span  class="document-title"> {{ form.id }} </span>
                            <template slot="label">
                                <span class="document-title">{{ $t('message.Distribution of costs') }} {{ $t('message.n') }}</span>
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
                    <el-col :span="6">
                        <el-form-item :label="columns.from_date.title" prop="from_date" size="small">
                            <el-date-picker prefix-icon="el-icon-date" v-model="form.from_date" type="date"
                                :format="date_format" :value-format="date_format" :placeholder="columns.from_date.title">
                            </el-date-picker>
                        </el-form-item>
                    </el-col>
                    <el-col :span="6">
                        <el-form-item :label="columns.to_date.title" prop="to_date" size="small">
                            <el-date-picker prefix-icon="el-icon-date" v-model="form.to_date" type="date"
                                :format="date_format" :value-format="date_format" :placeholder="columns.to_date.title">
                            </el-date-picker>
                        </el-form-item>
                    </el-col>
                    <el-col :span="6">
                        <el-form-item :label="$t('message.type_document')" size="small">
                            <el-select v-model="type_document" clearable :placeholder="$t('message.type_document')">
                                <el-option :label="$t('message.buy')" value="buy_materials"></el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="6">
                        <el-button @click="loadMaterials()" icon="el-icon-search" type="primary" size="small"> {{ $t('message.Search') }} </el-button>
                    </el-col>
                </el-form>
            </el-card>

            <el-card class="mt-2" v-loading="loadingMaterials">
                <el-row>
                    <el-col :span="6">
                        <el-button @click="drawerIncludeTransactions = true" type="primary" size="medium"> {{ $t('message.Bind Cost transactions') }} </el-button>
                    </el-col>
                    <el-col :span="4">
                        <span>{{ $t('message.Total amount costs') }}: {{ totalTransactionsAmount | formatMoney(2) }}</span>
                    </el-col>
                    <el-col :span="4">
                        <span>{{ $t('message.Total amount materials') }}: {{ totalMaterialsPrice | formatMoney(2) }}</span>
                    </el-col>
                </el-row>
                <el-table
                    :data="items"
                    size="small"
                    style="width: 100%" class="crm-el-table">
                        <template slot="empty"><span></span></template>
                        <el-table-column
                          type="index"
                          width="50">
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.name')">
                            <template slot-scope="item">
                                {{ item.row.material ? item.row.material.name : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.type_document')">
                            <template slot-scope="item">
                                <template v-if="item.row.warehouse_materialable_type == 'buy_materials'">
                                    {{ $t('message.buy') }}
                                </template>
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.datetime')">
                            <template slot-scope="item">
                                {{ item.row.created_at }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.remainder')">
                            <template slot-scope="item">
                                {{ item.row.remainder | formatNumber }}
                            </template>
                        </el-table-column>
                        
                        <el-table-column
                            :label="$t('message.buy_price')">
                            <template slot-scope="item">
                                {{ item.row.buy_price * item.row.rate | formatMoney(2) }} 
                                <template v-if="item.row.rate != 1">
                                    ({{ item.row.buy_price }} {{ item.row.currency ? item.row.currency.symbol : '' }})
                                </template>
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.total_amount')">
                            <template slot-scope="item">
                                {{ item.row.remainder * item.row.buy_price * item.row.rate | formatMoney(2) }}
                            </template>
                        </el-table-column>                        
                        <el-table-column
                            :label="$t('message.additional') + ' ' + $t('message.buy_price')">
                            <template slot-scope="item">
                                {{ item.row.additional_price | formatMoney(2) }}
                            </template>
                        </el-table-column>                        
                        <el-table-column
                            width="80"
                            :label="$t('message.delete')">
                            <template slot-scope="item">
                                    <el-button type="danger" icon="el-icon-delete" circle size="mini"
                                    @click.native.prevent="deleteRow(item.$index, items)"></el-button>
                            </template>
                        </el-table-column>
                    </el-table>                    
            </el-card>
        </el-main>
        <el-drawer size="90%" :with-header="false" :append-to-body="true" :visible.sync="drawerIncludeTransactions" ref="drawerIncludeTransactions" @closed="drawerClosed('drawerIncludeTransactionsChild')"  @opened="drawerOpened('drawerIncludeTransactionsChild')">
            <transactions :is_edit="true" :distribution_cost_id="form.id" drawer="drawerIncludeTransactions" ref="drawerIncludeTransactionsChild"></transactions>
        </el-drawer>
    </el-row>
</template>

<script>
    import form from '@/utils/mixins/form';
    import drawer from '@/utils/mixins/includes/drawer';
    import warehouse_material from '@/utils/mixins/models/distribution_cost/warehouse-material';
    import { mapGetters, mapActions } from 'vuex';

    export default {
           mixins:[form,drawer,warehouse_material],
           props: ['distribution_cost'],
           computed: {
               ...mapGetters({
                   old_items: 'distributionCosts/items'
               })
           },
           methods: {
                ...mapActions({
                    save: 'distributionCosts/update',
                    edit: 'distributionCosts/show',                    
                }),
                afterOpen(){
                    this.form = this.getForm;
                    this.items = [];
                    this.load();                    
                },
                load(){
                    if (!this.loading && this.distribution_cost) {
                        this.changeLoading(true);
                        this.edit(this.distribution_cost.id)
                        .then(res =>{
                            this.changeLoading(false);
                            this.form = this.getForm;
                            this.setMaterialsToItems();
                        })
                        .catch(err => {
                            this.changeLoading(false);
                            this.$alert(err);
                        })
                    }
                },
                setMaterialsToItems(){
                    this.old_items.forEach(element => {
                        let item = {};
                        let warehouse_material = element.additional_priceable
                        item.id = element.additional_priceable_id;
                        item.material = (warehouse_material) ? warehouse_material.material : '';
                        item.currency = (warehouse_material) ? warehouse_material.currency : '';
                        item.warehouse_materialable_type = (warehouse_material) ? warehouse_material.warehouse_materialable_type : '';
                        item.remainder = (warehouse_material) ? warehouse_material.remainder : 0;
                        item.buy_price = (warehouse_material) ? warehouse_material.buy_price : 0;
                        item.rate = (warehouse_material) ? warehouse_material.rate : 0;
                        item.created_at = (warehouse_material) ? warehouse_material.created_at : '';
                        item.additional_price = element.price;                        
                        this.items.push( item );
                    });
                },
                afterLeave(){
                   this.$store.commit('distributionCosts/SET_ITEMS',[]);
                   this.$store.commit('distributionCosts/SET_TRANSACTIONS',[]);
                   this.$store.commit('distributionCosts/SET_OLD_TRANSACTIONS',[]);
                }
           }
    }
</script>