<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.return_from_processing') }}
            </span>
            <el-button type="primary" size="small" class="mr-1" :loading="waiting" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main>
            <el-col :span="24">
                <h5>Сырьё</h5>
                <el-card class="box-card">
                    <form class="mt-2 ml-1 width-100 row" @submit.prevent="submit">
                        <div class="col-12 p-0 mt-2">
                            <table class="table">
                                <thead>
                                    <tr class="d-flex">
                                        <th class="col-3 text-center">Наименование сырья</th>
                                        <th class="col-1 text-center">Ед. изм.</th>
                                        <th class="col-2 text-center">Склад</th>
                                        <th class="col-1 text-center">Количество</th>
                                        <th class="col-1 text-center">Валюта</th>
                                        <th class="col-1 text-center">Курс</th>
                                        <th class="col-1 text-center">Закупочная цена</th>
                                        <th class="col-1 text-center">Цена продажи</th>
                                        <th class="col-1 text-center">Удалить</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(warehouse_material, index) in warehouse_materials" :key="index" class="d-flex">
                                        <td  class="input-group-sm col-sm-3">
                                            <b>{{ warehouse_material.material_name }}</b>
                                        </td>
                                        <td class="input-group-sm col-sm-1 text-center">
                                            {{ warehouse_material.measurement }}
                                        </td>

                                        <td class="input-group-sm col-sm-2 text-center">
                                            <el-select v-model="warehouse_material.warehouse_id" size="mini" filterable clearable>
                                              <el-option
                                                v-for="item in warehouse_material.warehouses"
                                                :key="'warehousescreatecoming'+item.id"
                                                :label="item.name"
                                                :value="item.id"
                                              ></el-option>
                                            </el-select>
                                        </td>

                                        <td class="input-group-sm col-sm-1">
                                            <el-input type="number" v-model="warehouse_material.quantity" size="mini"></el-input>
                                        </td>
                                        <td class="input-group-sm col-sm-1" v-if="multi_currency">
                                            <el-select v-model="warehouse_material.currency_id" size="mini" filterable clearable @change="updateRate(index)">
                                                <el-option v-for="(item, index) in currencies" :key="index + 'currency' + item.id" :label="item.name" :value="item.id"></el-option>
                                            </el-select>
                                        </td>
                                        <td class="input-group-sm col-sm-1" v-if="multi_currency">
                                            <el-input type="number" v-model="warehouse_material.rate" size="mini"></el-input>
                                        </td>
                                        <td class="input-group-sm col-sm-1">
                                            <div class="el-input el-input--mini">
                                                <money
                                                v-model="warehouse_material.buy_price"
                                                v-bind="money_material"
                                                class="el-input__inner"
                                                ></money>
                                            </div>
                                        </td>
                                        <td class="input-group-sm col-sm-1">
                                            <div class="el-input el-input--mini">
                                                <money
                                                v-model="warehouse_material.price"
                                                v-bind="money_material"
                                                class="el-input__inner"
                                                ></money>
                                            </div>
                                        </td>
                                        <td class="text-center col-sm-1">
                                            <el-button @click="removeMaterial(warehouse_material)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                                        </td>
                                    </tr>

                                    <tr class="d-flex">
                                        <td  class="input-group-sm col-sm-5 select_product">
                                            <el-select
                                                @change="appendMaterial"
                                                v-model="current_material"
                                                filterable
                                                remote
                                                size="mini"
                                                reserve-keyword
                                                placeholder="Введите наименование, код или артикул"
                                                :remote-method="searchMaterial"
                                                :loading="loadingMaterials">
                                                <el-option
                                                    v-for="item in reworking_materials"
                                                    :key="item.id"
                                                    :label="item.name"
                                                    :value="item">
                                                </el-option>
                                            </el-select>
                                        </td>
                                        <td class="col-sm-9"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
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
        props: ['drawer'],
        data() {
            return {
                warehouse_materials: [],
                current_material: '',
                loadingMaterials: false,
                waiting: false,
            }
        },
        computed: {
            ...mapGetters({
                warehouse_material: 'warehouseMaterials/warehouse_material',
                warehouses: 'warehouses/inventory',
                materials: 'materials/inventory',
                currencies: 'currencies/list',
                money_material: 'money_material',
            }),
            reworking_materials: function () {
                return this.materials.filter((material) => {return material.is_reworking == 1})
            }
        },

        async mounted(){
            if (this.materials && this.materials.length === 0)
            {
              await this.updateMaterialsList();
            }
            if (this.warehouses && this.warehouses.length === 0)
            {
              await  this.loadWarehouses();
            }
            if (this.currencies && this.currencies.length === 0)
            {
              await  this.loadCurrencies();
            }
        },
        methods: {
            ...mapActions({
                createComing: 'warehouseMaterials/createComing',
                updateMaterialsList: 'materials/inventory',
                loadWarehouses: "warehouses/inventory",
                loadCurrencies: "currencies/index",
            }),

            submit(close = true){
                for (let key in this.warehouse_materials)
                {
                    if (this.warehouse_materials.hasOwnProperty(key))
                    {
                        let warehouse_material = this.warehouse_materials[key];
                        if( warehouse_material.warehouse_id == null || warehouse_material.warehouse_id == undefined || warehouse_material.warehouse_id == "" )
                        {
                            this.$alert({
                                success: false,
                                message: 'Склад не может быть пустым'
                            })
                            return;
                        }
                    }
                }

                if( this.validateMaterials() )
                {
                    this.waiting = false;
                    this.createComing({warehouse_materials: this.warehouse_materials})
                        .then(res => {
                            this.$alert(res);
                            this.warehouse_materials = [];
                            this.waitingStop();
                            this.listChanged();
                            if (close) {
                                this.close();
                            }
                        })
                        .catch(err => {
                            this.$alert(err)
                        })
                }
            },

            searchMaterial(search){
                this.loadingMaterials = true;
                this.updateMaterialsList({search: search})
                    .then(res => {
                        this.loadingMaterials = false;
                    })
                    .catch(err => {
                        this.loadingMaterials = false;
                        this.$alert(err)
                    })
            },

            appendMaterial(){
                let warehouse_material = JSON.parse( JSON.stringify( this.warehouse_material ));
                warehouse_material.material_id = this.current_material.id;
                warehouse_material.material_name = this.current_material.name;
                warehouse_material.measurement = (this.current_material.measurement) ? this.current_material.measurement.name : '';
                warehouse_material.warehouses = (this.current_material.warehouse_type) ? (this.warehouses.filter((item) => {return item.warehouse_type_id === this.current_material.warehouse_type.id})) : [];
                warehouse_material.warehouse_id = null;
                warehouse_material.quantity = 1;
                warehouse_material.currency_id = 1;
                warehouse_material.rate = 1;
                warehouse_material.buy_price = 0;
                warehouse_material.price = 0;
                warehouse_material.warehouse_type_id = parseInt(this.current_material.warehouse_type_id);
                this.warehouse_materials.push(warehouse_material);
                this.current_material = '';
            },

            updateRate(index) {
                var current_currency = this.currencies.find((item) => {return item.id === this.warehouse_materials[index].currency_id});
                this.warehouse_materials[index].rate = (current_currency && current_currency.rate && current_currency.rate != null) ? parseFloat(current_currency.rate) : 1;
            },

            removeMaterial (line) {
                if (!this.blockRemoval) this.warehouse_materials.splice(this.warehouse_materials.indexOf(line), 1)
            },

            validateMaterials() {
               if (this.warehouse_materials.length === 0)
               {
                   this.$message({
                       message: 'Сырья пусти',
                       type: 'warning'
                   });
                   return false;
               }
               return true;
            },

            waitingStop() {
                setTimeout(() => {
                    this.waiting = false
                }, 500);
            },
            listChanged() {
                this.parent().listChanged()
            },
        },
    }
</script>
