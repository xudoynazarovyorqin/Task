<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>{{ $t('message.buys')}} #  {{ model.id }} (от {{ model.created_at | date }}  по Дог. № {{ ((model.contract_provider) ? (model.contract_provider.number) : '') }}  от  {{ (model.contract_provider ? model.contract_provider.begin_date : '') | date }} )</span>
            <el-button v-can="['buys.coming']" type="primary" size="small" class="mr-1" :loading="waiting" @click="save(true)"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main v-loading="loading">
            <el-col :span="24" class="mt-2">
                <el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span> {{ $t('message.coming_history') }} </span>
                    </div>
                    <el-table ref="filterTable" :data="warehouse_materials" size="medium" class="crm-mini-table" style="width: 100%">
                        <el-table-column :label="$t('message.name')">
                            <template slot-scope="props">
                                {{ (props.row.material ? props.row.material.name : '') | truncate }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.receive_quantity')">
                            <template slot-scope="props">
                                {{ props.row.total_coming }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.remainder')">
                            <template slot-scope="props">
                                {{ props.row.remainder }}
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
                                {{ props.row.buy_price | formatNumber(2) }} {{ props.row.currency ? props.row.currency.symbol : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.selling_price')">
                            <template slot-scope="props">
                                {{ props.row.price | formatNumber(2) }} {{ props.row.currency ? props.row.currency.symbol : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.rate')">
                            <template slot-scope="props">
                                <crm-rate :row="props.row"></crm-rate>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.date')">
                            <template slot-scope="props">
                                {{ props.row.created_at }}
                            </template>
                        </el-table-column>
                    </el-table>
                </el-card>
            </el-col>
            <el-col :span="24" class="mt-2">
                <el-card class="box-card pb-2">
                    <el-table size="medium" :data="items" style="width: 100%" class="crm-el-table">
                        >
                        <template slot="empty">
                            <span></span>
                        </template>
                        <el-table-column :label="$t('message.name')">
                            <template slot-scope="item">
                                <b>{{ (item.row.material ? item.row.material.name : '') | truncate }}</b>
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
                                <el-input-number type="number" v-model="item.row.total_coming" size="mini" :min="0" :max="item.row.not_enough"></el-input-number>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.measurement')">
                            <template slot-scope="item">
                                {{ item.row.material ? item.row.material.measurement ? item.row.material.measurement.name : '' : '' }}
                                {{ item.row.material | addMeasurement(item.row.qty_weight)}}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.warehouse')">
                            <template slot-scope="item">
                                <warehouses v-model="item.row.warehouse_id" :warehouse_id="item.row.warehouse_id" :type_id="item.row.material ? item.row.material.warehouse_type_id : null" size="mini"></warehouses>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.buy_price')">
                            <template slot-scope="item">
                                {{ item.row.price | formatNumber }} {{ (item.row.currency) ? item.row.currency.symbol : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.selling_price')">
                            <template slot-scope="item">
                                <material-price v-model="item.row.selling_price" :old="item.row.selling_price" size="mini"></material-price>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.rate')">
                            <template slot-scope="item">
                                <crm-rate :row="item.row"></crm-rate>
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.delete')">
                            <template slot-scope="item">
                                <el-button @click="removeMaterial(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                    <el-col :span="12" class="mt-1">
                        <el-select @change="appendMaterial" v-model="current_material" filterable size="small" reserve-keyword :placeholder="$t('message.product_select_plc')" class="d-block">
                            <el-option v-for="(item,index) in materials" :key="index" :label="item.material.name" :value="item"></el-option>
                        </el-select>
                    </el-col>
                </el-card>
            </el-col>
        </el-main>
    </el-col>
</template>
<script>
	import { mapGetters, mapActions } from "vuex";
    import drawer from '@/utils/mixins/includes/drawer';
    import warehouses from '@inventory/crm-warehouse-select';
    import MaterialPrice from '@inputs/crm-material-price-input';

	export default {
		mixins: [drawer],
		props:['buy'],
		components: {warehouses,MaterialPrice},
		data() {
			return {
				items: [],
				current_material: null,
				waiting: false,
			};
		},
		computed: {
			...mapGetters({
				model: "buys/model",
				buy_materials: "buys/buy_materials",
				buy_material: "buys/buy_material",
				warehouse_materials: "buys/warehouse_materials",
                warehouses: 'warehouses/inventory',
			}),
			materials: function() {
				return this.buy_materials.filter((item) => { return (item.not_enough > 0 && !_.find(this.items, ['warehouse_materialable_id',item.id]))})
			}
		},
		methods: {
			...mapActions({
				receive: "buys/receive",
				show: 'buys/show',
				loadWarehouseInventory: 'warehouses/inventory',
				loadWarehouseMaterials: 'buys/warehouse_materials'
            }),
            async afterOpen(){
                this.items = [];
                if (this.warehouses && this.warehouses.length === 0) await  this.loadWarehouseInventory();
                this.loadModel();
            },
            afterLeave(){},
			loadModel() {
				if (!this.loading && this.buy) {
                    this.changeLoading(true);
                    this.loadWarehouseMaterials({id: this.buy.id});
                    this.show(this.buy.id)
                        .then(res => {
                            this.changeLoading();
                            this.buy_materials.forEach(element => {
                                this.pushMaterial(element)
                            });
                        })
                        .catch(err => {
                            this.changeLoading();
                        });
                }
            },
            pushMaterial(buy_material){
                if (buy_material.not_enough > 0) {
                    let item = {};
                    item.warehouse_materialable_id = buy_material.id;
                    item.material = buy_material.material;
                    item.qty_weight = buy_material.qty_weight;
                    item.total_coming = buy_material.not_enough;
                    item.not_enough = buy_material.not_enough;
                    item.currency = buy_material.currency;
                    item.rate = buy_material.rate;
                    item.price = buy_material.price;
                    item.selling_price = buy_material.price;
                    let  w = _.find(this.warehouses,['warehouse_type_id',(buy_material.material) ? buy_material.material.warehouse_type_id : '']);
                    if (!w) {
                        w = _.head(this.warehouses)
                    }
                    item.warehouse_id = w ? w.id : null;
                    this.items.push(item);
                }
            },
			appendMaterial() {
                this.pushMaterial(this.current_material);
				this.current_material = null;
			},
			save(close = true) {
				 if (_.find(this.items,function(o) {
                    return !_.isInteger(o.warehouse_id)
                })) {
                    this.$alert({
                        success: false,
                        message: this.$t('message.warehouse_can_not_be_empty')
                    })
                    return;
                }
                const form = {
                    buy_id: this.model.id,
                    warehouse_materials: _.map(this.items,function(item) {
                        return {
                            warehouse_materialable_id: item.warehouse_materialable_id,
                            material_id: item.material.id,
                            receive: item.total_coming,
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
                        this.items = [];
                        this.close()
                    })
                    .catch(err => {
                        this.waitingStop();
                        this.$alert(err)
					})
			},
			waitingStop() {
				setTimeout(() => {
					this.waiting = false
				}, 500);
			},
			removeMaterial(line) {
				this.items.splice(this.items.indexOf(line), 1);
			}
		}
	};
</script>
