<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.buy_ready_products') }} <span> #  {{ model.id }} (от {{ model.created_at | date }}  по Дог. № {{ ((model.contract_provider) ? (model.contract_provider.number) : '') }}  от  {{ (model.contract_provider ? model.contract_provider.begin_date : '') | date }} )</span>
            </span>
            <el-button v-can="['buyReadyProducts.coming']" type="primary" size="small" class="mr-1" :loading="waiting" @click="save(true)"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main v-loading="loading">
            <el-col :span="24">
                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span>{{ $t('message.coming_history')}}</span>
                    </div>
                    <el-table ref="filterTable" :data="warehouse_products" size="mini" class="crm-mini-table" style="width: 100%">
                        <el-table-column :label="$t('message.name')">
                            <template slot-scope="props">
                                {{ (props.row.product ? props.row.product.name : '') | truncate }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.receive_quantity')">
                            <template slot-scope="props">
                                {{ props.row.receive | formatNumber }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.remainder')">
                            <template slot-scope="props">
                                {{ props.row.remainder | formatNumber}}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.measurement')">
                            <template slot-scope="props">
                                {{ (props.row.product ? props.row.product.measurement ? props.row.product.measurement.name : '' : '') | truncate }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.warehouse')">
                            <template slot-scope="props">
                                {{ props.row.warehouse ? props.row.warehouse.name : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.day_in_warehouse')">
                            <template slot-scope="props">
                                {{ props.row.day_in_warehouse }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.buy_price')">
                            <template slot-scope="props">
                                {{ props.row.buy_price | formatNumber(4) }} {{ (props.row.currency) ? props.row.currency.symbol : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.selling_price')">
                            <template slot-scope="props">
                                {{ props.row.selling_price | formatNumber(4) }} {{ (props.row.currency) ? props.row.currency.symbol : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.rate')">
                            <template slot-scope="item">
                                <crm-rate :row="item.row"></crm-rate>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.date')">
                            <template slot-scope="props">
                                {{ props.row.created_at }}
                            </template>
                        </el-table-column>
                    </el-table>
                </el-card>
                <el-card class="box-card pb-2 mt-2">
                      <el-table size="medium" :data="items" style="width: 100%" class="crm-el-table">
                        <template slot="empty">
                            <span></span>
                        </template>
                        <el-table-column :label="$t('message.name')">
                            <template slot-scope="item">
                                <b>{{ (item.row.product ? item.row.product.name : '') | truncate }}</b>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.quantity')">
                            <template slot-scope="item">
                                {{ item.row.qty_weight | formatNumber }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.not_enough')">
                            <template slot-scope="item">
                                {{ item.row.not_enough | formatNumber }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.receive_quantity')" min-width="100">
                            <template slot-scope="item">
                                <el-input-number type="number" v-model="item.row.receive" size="mini" :min="0" :max="item.row.not_enough"></el-input-number>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.measurement')">
                            <template slot-scope="item">
                                {{ item.row.product ? item.row.product.measurement ? item.row.product.measurement.name : '' : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.warehouse')">
                            <template slot-scope="item">
                                <warehouses v-model="item.row.warehouse_id" :warehouse_id="item.row.warehouse_id" :type_id="item.row.product ? item.row.product.warehouse_type_id : null" size="mini"></warehouses>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.selling_price')">
                            <template slot-scope="item">
                                <product-price v-model="item.row.selling_price" :old="item.row.selling_price" size="mini"></product-price>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.rate')">
                            <template slot-scope="item">
                                <crm-rate :row="item.row"></crm-rate>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.buy_price')">
                            <template slot-scope="item">
                                {{ item.row.price | formatNumber }} {{ (item.row.currency) ? item.row.currency.symbol : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.delete')">
                            <template slot-scope="item">
                                <el-button @click="removeProduct(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                    <el-col :span="12" class="mt-1">
                        <el-select @change="appendProduct" v-model="current_item" filterable size="small" reserve-keyword :placeholder="$t('message.product_select_plc')" class="d-block">
                            <el-option v-for="(item,index) in products" :key="'items-'+index" :label="item.product.name" :value="item">
                            </el-option>
                        </el-select>
                    </el-col>
                </el-card>
            </el-col>
        </el-main>
    </el-col>
</template>
<script>
    import { mapGetters, mapActions } from "vuex";
    import drawer from "@/utils/mixins/includes/drawer";
  	import warehouses from '@inventory/crm-warehouse-select';
    import ProductPrice from '@inputs/crm-product-price-input';

    export default {
        mixins: [drawer],
        props:['buy'],
        components: {warehouses,ProductPrice},
        data() {
            return {
                items: [],
                current_item: '',
                waiting: false,
            }
        },
        computed: {
            ...mapGetters({
                model: 'buyReadyProducts/model',
                buy_products: 'buyReadyProducts/buy_products',
                buy_product: 'buyReadyProducts/buy_product',
                warehouse_products: 'buyReadyProducts/warehouse_products',
                warehouses: 'warehouses/inventory',
            }),
            products: function() {
   				return this.buy_products.filter((item) => { return (item.not_enough > 0 && !_.find(this.items, ['warehouse_productable_id',item.id]))})
            }
        },
        methods: {
            ...mapActions({
                receive: 'buyReadyProducts/receive',
                loadWarehouseInventory: 'warehouses/inventory',
                loadWarehouseProducts: 'buyReadyProducts/warehouse_products',
                show: "buyReadyProducts/show",
            }),
            async afterOpen(){
                this.items = [];
                if (this.warehouses && this.warehouses.length === 0) await  this.loadWarehouseInventory();
                this.loadModel();
            },
            afterLeave(){},
            loadModel(){
                if (!this.loading && this.buy) {
                    this.changeLoading(true);
                    this.loadWarehouseProducts({id: this.buy.id});
                    this.show(this.buy.id)
                        .then(res => {
                            this.changeLoading();
                            this.buy_products.forEach(element => {
                                this.pushItem(element);
                            });
                        })
                        .catch(err => {
                            this.changeLoading();
                        });
                }
            },
            pushItem(item){
                if (item && item.not_enough > 0) {
                    let warehuse_product = {};
                    warehuse_product.warehouse_productable_id = item.id;
                    warehuse_product.product = item.product;
                    warehuse_product.qty_weight = item.qty_weight;
                    warehuse_product.receive = item.not_enough;
                    warehuse_product.not_enough = item.not_enough;
                    warehuse_product.currency = item.currency;
                    warehuse_product.rate = item.rate;
                    warehuse_product.price = item.price;
                    warehuse_product.selling_price = item.price;

                    let  w = _.find(this.warehouses,['warehouse_type_id',(item.product) ? item.product.warehouse_type_id : '']);
                    if (!w) {
                        w = _.head(this.warehouses)
                    }
                    warehuse_product.warehouse_id = w ? w.id : null;
                    this.items.push(warehuse_product);
                }
            },
            appendProduct(){
                this.pushItem(this.current_item);
                this.current_item = null;
            },
            save(){
                 if (_.find(this.items,function(o) { return !_.isInteger(o.warehouse_id);})) {
                    this.$alert({
                        success: false,
                        message: this.$t('message.warehouse_can_not_be_empty')
                    })
                    return;
                }
                 const form = {
                    buy_id: this.model.id,
                    warehouse_products: _.map(this.items,function(item) {
                        return {
                            warehouse_productable_id: item.warehouse_productable_id,
                            product_id: item.product.id,
                            receive: item.receive,
                            selling_price: item.selling_price,
                            warehouse_id: item.warehouse_id
                        }
                    })
                }
                this.waiting = true;
                this.receive(form)
                    .then(res => {
                        this.$alert(res);
                        this.waitingStop();
                        this.parent().listChanged();
                        this.close()
                    })
                    .catch(err => {
                        this.waitingStop();
                        this.$alert(err)
                    })
            },
            removeProduct (line) {
                if (_.size(this.items)) this.items.splice(this.items.indexOf(line), 1)
            },
            waitingStop() {
                setTimeout(() => {
                    this.waiting = false
                }, 500);
            },
            afterLeave(){
                this.items = [];
            }
        },
    }
</script>
