<template>
  	<el-col :span="24">
		<header id="el-drawer__title" class="el-drawer__header">
			<span>
			  {{ $t('message.details') }}
			</span>
			<el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
		</header>
		<el-main>
            <el-card class="box-card">
                <div slot="header" class="clearfix">
                    <span> <b> {{ product.name }}</b> </span>
                    <el-button style="float: right; padding: 3px 0" type="text" class="text-secondary"><span> {{ $t('message.code') }}:  {{ product.code }}</span> <span> {{ $t('message.vendor_code') }}: {{ product.vendor_code }}</span></el-button>
                </div>
                <table class="table border-0 text-right">
                    <thead>
                        <tr>
                            <th class="border-0">{{ $t('message.available') }}</th>
                            <th class="border-0">{{ $t('message.Booked') }}</th>
                            <th class="border-0">{{ $t('message.measurements') }}</th>
                            <th class="border-0">{{ $t('message.cost_price') }}</th>
                            <th class="border-0">{{ $t('message.total_buy_price') }}</th>
                            <th class="border-0">{{ $t('message.selling_price') }}</th>
                            <th class="border-0">{{ $t('message.total_selling_price') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border-0">{{ product.remainder | formatNumber }}</td>
                            <td class="border-0">{{ product.booked | formatNumber }}</td>
                            <td class="border-0">{{ (product.measurement) ? product.measurement.name : '' }}</td>
                            <td class="border-0">{{ product.total_cost_price | divide(product.remainder) | formatMoney(2) }}</td>
                            <td class="border-0">{{ product.total_cost_price | formatMoney(2) }}</td>
                            <td class="border-0">{{ product.total_selling_price | divide(product.remainder) | formatMoney(2) }}</td>
                            <td class="border-0">{{ product.total_selling_price | formatMoney(2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </el-card>
            <el-col :span="24" class="mt-2">
				<el-card class="box-card">
                    <div slot="header" class="clearfix">
                        <span> {{ $t('message.cost_price') }} </span>
                    </div>
                    <el-table
                        v-loading="loading"
                        :element-loading-text="$t('message.loading')"
                        element-loading-spinner="el-icon-loading"
                        ref="filterTable"
                        :data="warehouse_products"
                        size="mini"
                        class="crm-mini-table"
                        style="width: 100%">
                        <el-table-column
                            :label="$t('message.warehouse')">
                            <template slot-scope="props">
                                {{ props.row.warehouse ? props.row.warehouse.name : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.type_document')">
                            <template slot-scope="props">
                                {{ props.row.warehouse_productable_type }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.number')">
                            <template slot-scope="props">
                                {{ props.row.warehouse_productable_id }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.available')">
                            <template slot-scope="props">
                                {{ props.row.remainder | formatNumber }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.Booked')">
                            <template slot-scope="props">
                                {{ props.row.booked | formatNumber }}
                            </template>
                        </el-table-column>
                        <el-table-column :label="$t('message.day_in_warehouse')">
                            <template slot-scope="props">
                                {{ props.row.day_in_warehouse }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.rate')">
                            <template slot-scope="props">
                               <crm-rate :row="props.row"></crm-rate>
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.buy_price')">
                            <template slot-scope="props">
                                {{ props.row.buy_price | formatNumber(2) }} {{ (props.row.currency) ? props.row.currency.symbol : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.addit_price')">
                            <template slot-scope="props">
                                {{ props.row.additional_price | | formatMoney(2) }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.selling_price')">
                            <template slot-scope="props">
                                {{ props.row.selling_price | formatNumber(2) }} {{ (props.row.currency) ? props.row.currency.symbol : '' }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            :label="$t('message.date')"
                            >
                            <template slot-scope="props">
                                {{ props.row.created_at }}
                            </template>
                        </el-table-column>
                    </el-table>
				</el-card>
            </el-col>
		</el-main>
  	</el-col>
</template>
<script>
    import { mapGetters, mapActions } from "vuex";
    import drawer from '@/utils/mixins/includes/drawer';

    export default {
        mixins: [drawer],
        props:['open','product', 'warehouse_id'],
        data() {
            return {
                cachedProduct: null,
            }
        },
        computed: {
            ...mapGetters({
                warehouse_products: 'warehouseProducts/comingProducts',
            }),
        },
        methods: {
            ...mapActions({
                comingProducts: 'warehouseProducts/comingProducts',
            }),
            afterOpen(){
                if (!(this.cachedProduct && this.product && this.cachedProduct.id === this.product.id)) {
                    this.loadHistory();
                    this.cachedProduct = JSON.parse( JSON.stringify( this.product ))
                }
            },
            loadHistory(){
                const filter = {product_id: this.product.id, warehouse_id: this.warehouse_id};
                this.changeLoading(true);
                this.comingProducts(filter)
                .then(res => {this.changeLoading();})
                .catch(err => {this.changeLoading();})
            },
            afterLeave(){
                /**
                 * Write code here is run after drawer closed.
                 */
            }
        },
    }
</script>
