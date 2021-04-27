<template>
    <el-tabs :tab-position="tabPosition">
        <el-tab-pane :label="$t('message.The main')">
            <el-card>
                <el-col :span="16">
                    <el-form label-position="left" :model="settings" label-width="340px" size="mini">
                        <el-form-item :label="$t('message.Automatic pay, When payment created')">
                            <el-checkbox v-model="settings.auto_pay" :label="$t('message.Automatic')" name="type"></el-checkbox>
                        </el-form-item>
                        <el-form-item :label="$t('message.Automatic booking material and products')">
                            <el-checkbox v-model="settings.auto_reservation" :label="$t('message.Automatic')" name="type"></el-checkbox>
                        </el-form-item>
                        <el-form-item :label="$t('message.How to get raw materials from stock')">
                            <el-radio-group  v-model="settings.control_materials_sort">
                                <el-radio label="asc">{{ $t('message.FIFO (first in first out)') }}</el-radio>
                                <el-radio label="desc">{{ $t('message.LIFO (last in first out)') }}</el-radio>
                            </el-radio-group>                        
                        </el-form-item>
                        <el-form-item :label="$t('message.Automatic write-off of materials and goods')">
                            <el-checkbox v-model="settings.automatic_write_off" :label="$t('message.Automatic') + ' ( ' +  $t('message.This only works when automatic booking is turned off') + ' )'" name="type"></el-checkbox>
                        </el-form-item>
                        <users v-model="settings.default_warehouse_user_id" :label="$t('message.Default warehouse user')" size="small" :user_id="settings.default_warehouse_user_id"></users>
                        <el-form-item :label="$t('message.How to get a product from stock')">
                                <el-radio-group  v-model="settings.control_products_sort">
                                <el-radio label="asc">{{ $t('message.FIFO (first in first out)') }}</el-radio>
                                <el-radio label="desc">{{ $t('message.LIFO (last in first out)') }}</el-radio>
                            </el-radio-group>                        
                        </el-form-item>
                        <el-form-item :label="$t('message.Room Number for Price (Product)')">
                            <el-input v-model="settings.number_money_product" autocomplete="off" placeholder="2"></el-input>
                        </el-form-item>
                        <el-form-item :label="$t('message.Room Number for the Price (Raw Material)')">
                            <el-input v-model="settings.number_money_material" autocomplete="off" placeholder="2"></el-input>
                        </el-form-item>
                        <el-form-item :label="$t('message.Room Number for Quantity (Product)')">
                            <el-input v-model="settings.number_quantity_product" autocomplete="off" placeholder="2"></el-input>
                        </el-form-item>
                        <el-form-item :label="$t('message.Room Number for Quantity (Raw Material)')">
                            <el-input v-model="settings.number_quantity_material" autocomplete="off" placeholder="2"></el-input>
                        </el-form-item>
                        <el-form-item>
                            <el-button type="primary" round @click="submit">{{ $t('message.save') }}</el-button>
                        </el-form-item>
                    </el-form>
                </el-col>
            </el-card>
        </el-tab-pane>
        <el-tab-pane :label="$t('message.Discounts')">{{ $t('message.Discounts') }}</el-tab-pane>
    </el-tabs>
</template>

<script>
    import {mapActions} from 'vuex';
    import users from '@selects/crm-user';

    export default {
        components:{users},
        data(){
            return {
                tabPosition: 'left',
                settings: {
                    auto_reservation: false,
                    auto_pay: false,
                    automatic_write_off: false,
                    control_materials_sort: '',
                    control_products_sort: '',
                    number_money_product: 2,
                    number_money_material: 2,
                    number_quantity_product: 2,
                    number_quantity_material: 2,
                    default_warehouse_user_id: null,
                }
            }
        },
        created(){
            this.getList()
                .then(res =>{
                    this.settings.auto_reservation = res.data.auto_reservation,
                    this.settings.auto_pay = res.data.auto_pay,
                    this.settings.automatic_write_off = res.data.automatic_write_off,
                    this.settings.control_materials_sort = res.data.control_materials_sort;
                    this.settings.control_materials_sort = res.data.control_materials_sort;
                    this.settings.control_products_sort = res.data.control_products_sort;
                    this.settings.number_money_product = res.data.number_money_product;
                    this.settings.number_money_material = res.data.number_money_material;
                    this.settings.number_quantity_product = res.data.number_quantity_product;
                    this.settings.number_quantity_material = res.data.number_quantity_material;
                    this.settings.default_warehouse_user_id = res.data.default_warehouse_user_id
                })
                .catch(err => {
                    this.$alert(err) 
                })
        },
        computed:{ },
        methods:{
            ...mapActions({
               save: 'settings/store',
                getList: 'settings/index',
            }),
            submit(){
                this.save(this.settings)
                    .then(res => {
                        this.$alert(res)
                    })
                    .catch(err => {
                        this.$alert(err)
                    })
            }
        }
    }
</script>
<style>
    .el-select {
        width: 100% !important;
    }
</style>
