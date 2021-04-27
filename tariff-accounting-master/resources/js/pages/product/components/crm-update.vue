<template>
<div>
    <header id="el-drawer__title" class="el-drawer__header">
        <span> {{ $t('message.edit') }} {{ $t('message.product') | lowerFirst }} № {{ product.id }}</span>
        <el-button v-can="['products.update']" type="success" size="small" class="mr-1" :loading="waiting"
            @click="submit(false)"> {{ $t('message.save') }}</el-button>
        <el-button v-can="['products.update']" type="primary" size="small" class="mr-1" :loading="waiting"
            @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
        <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
        </el-button>
    </header>
    <el-main class="pt-2" v-loading="loading">
        <el-form ref="form" status-icon :model="form" :rules="rules" :label-position="'right'" label-width="150px" size="mini">
            <el-steps :active="active_step" simple  finish-status="success" v-if="form.production" shadow="always">
                <el-step :title="$t('message.main')" class="cursor-pointer"  icon="el-icon-edit"></el-step>
                <el-step :title="$t('message.materials')" class="cursor-pointer" icon="el-icon-upload" v-if="form.production_type == 'production' || form.production_type == 'assembly'"></el-step>
                <el-step :title="$t('message.semi_products')" class="cursor-pointer" icon="el-icon-set-up" v-if="form.production_type == 'assembly'"></el-step>
            </el-steps>
            <el-col :span="24" class="mt-4" v-if="active_step == 1">
                <el-col :span="24">
                    <el-form-item :label="columns.name.title" prop="name">
                        <el-input v-model="form.name" autocomplete="off"  maxlength="200" show-word-limit></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                    <el-col :span="8">
                        <el-form-item :label="columns.code.title">
                            <el-input v-model="form.code" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.purchase_price.title" prop="purchase_price">
                            <el-col :span="12">
                                <product-price v-model="form.purchase_price" :old="form.purchase_price"></product-price>
                            </el-col>
                            <el-col :span="11" :offset="1" class="ml-1">
                                <currencies v-model="form.purchase_currency_id" :currency_id="form.purchase_currency_id" size="mini"></currencies>
                            </el-col>
                        </el-form-item>
                        <el-form-item :label="columns.selling_price.title" prop="selling_price">
                            <el-col :span="12">
                                <product-price v-model="form.selling_price" :old="form.selling_price"></product-price>
                            </el-col>
                            <el-col :span="11" :offset="1" class="ml-1">
                                <currencies v-model="form.selling_currency_id" size="mini" :currency_id="form.selling_currency_id"></currencies>
                            </el-col>
                        </el-form-item>
                        <measurements v-model="form.measurement_id" :measurement_id="form.measurement_id" size="mini"></measurements>
                        <el-form-item :label="columns.vendor_code.title">
                            <el-input v-model="form.vendor_code" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <categories v-model="form.categories" :old_categories="form.categories" size="mini"></categories>
                        <el-form-item :label="columns.nds.title">
                            <el-input v-model="form.nds" autocomplete="off"></el-input>
                        </el-form-item>
                        <warehouse-types v-model="form.warehouse_type_id" :warehouse_type_id="form.warehouse_type_id" prop="" size="mini"></warehouse-types>
                        <countries v-model="form.country_id" :country_id="form.country_id"  prop="" size="mini"></countries>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item :label="columns.recycled.title">
                            <el-checkbox v-model="form.recycled"></el-checkbox>
                        </el-form-item>
                        <el-form-item :label="columns.production.title">
                            <el-checkbox v-model="form.production"></el-checkbox>
                        </el-form-item>
                        <el-form-item :label="columns.production_type.title">
                            <el-select v-model="form.production_type"
                                :placeholder="$t('message.choose')" filterable clearable>
                                <el-option :label="$t('message.semi_product')" :value="'production'"></el-option>
                                <el-option :label="$t('message.ready_product')" :value="'assembly'"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item :label="columns.description.title">
                            <el-input :rows="3" type="textarea" v-model="form.description" autocomplete="off">
                            </el-input>
                        </el-form-item>
                    </el-col>
                </el-col>
            </el-col>
            <template v-if="form.production">
                <el-col :span="24" class="mt-4" v-if="(form.production_type == 'production' || form.production_type == 'assembly') && active_step == 2">
                    <el-card class="box-card">
                        <div slot="header" class="clearfix">
                            {{ $t('message.materials') }}
                        </div>
                        <el-table :data="product_materials" class="crm-el-table">
                            <template slot="empty">
                                <span></span>
                            </template>
                            <el-table-column :label="$t('message.name')">
                                <template slot-scope="item">
                                    {{ (item.row.material ? item.row.material.name : '') | truncate }}
                                </template>
                            </el-table-column>
                            <el-table-column :label="$t('message.quantity')">
                                <template slot-scope="item">
                                    <template v-if="item.row.material && !item.row.material.measurement_changeable">
                                        <el-input type="number" v-model="item.row.quantity" size="mini"></el-input>
                                    </template>
                                    <template v-else-if="item.row.material && item.row.material.measurement_changeable">
                                        <el-input type="number" v-model="item.row.inverse_quantity" size="mini"></el-input>
                                    </template>
                                </template>
                            </el-table-column>
                            <el-table-column :label="$t('message.measurement')">
                                <template slot-scope="item">
                                    <template v-if="item.row.material && !item.row.material.measurement_changeable">
                                        {{ item.row.material | inverseAddMeasurement(item.row.quantity) }}
                                    </template>
                                    <template v-else-if="item.row.material && item.row.material.measurement_changeable">
                                        {{ item.row.material | inverseAddMeasurement(item.row.inverse_quantity) }}
                                    </template>
                                </template>
                            </el-table-column>
                            <el-table-column :label="$t('message.measurement')">
                                <template slot-scope="item">
                                    <el-button  @click="removeMaterial(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                        <el-row>
                            <el-col :span="12" class="mt-2">
                                <materials @append="appendMaterial" :plc="$t('message.product_select_plc')"></materials>
                            </el-col>
                        </el-row>
                    </el-card>
                </el-col>
                <el-col :span="24" class="mt-4" v-if="form.production_type == 'assembly' && active_step == 3">
                    <el-card type="border-card">
                        <div slot="header" class="clearfix">
                            {{ $t('message.products') }}
                        </div>
                        <el-table :data="semi_products" class="crm-el-table">
                            <template slot="empty">
                                <span></span>
                            </template>
                            <el-table-column :label="$t('message.name')">
                                <template slot-scope="item">
                                    {{ (item.row.product ? item.row.product.name : '') | truncate }}
                                </template>
                            </el-table-column>
                            <el-table-column :label="$t('message.quantity')">
                                <template slot-scope="item">
                                    <el-input type="number" v-model="item.row.quantity" size="mini"></el-input>
                                </template>
                            </el-table-column>
                            <el-table-column :label="$t('message.measurement')">
                                <template slot-scope="item">
                                    {{ (item.row.product ? item.row.product.measurement ? item.row.product.measurement.name : '' : '') | truncate }}
                                </template>
                            </el-table-column>
                            <el-table-column :label="$t('message.measurement')">
                                <template slot-scope="item">
                                    <el-button  @click="removeSemiProduct(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                        <el-row>
                            <el-col :span="12" class="mt-2">
                                <products @append="appendSemiProduct" :plc="$t('message.product_select_plc')" :semi="true"></products>
                            </el-col>
                        </el-row>
                    </el-card>
                </el-col>
            </template>
            <el-col :span="24" class="mt-4 text-center" v-if="form.production">
                <el-button-group>
                    <el-button type="primary" v-if="active_step > 1" @click="prev" icon="el-icon-arrow-left">  {{ $t('message.prev')}}</el-button>
                    <el-button type="primary"  v-if="(form.production_type == 'production' && active_step  < 2) || (form.production_type == 'assembly' && active_step < 3)" @click="next"> {{ $t('message.next')}}<i class="el-icon-arrow-right el-icon-right"></i></el-button>
                </el-button-group>
            </el-col>
        </el-form>
    </el-main>
</div>
</template>
<script>
	import { mapGetters, mapActions } from "vuex";
    import form from '@/utils/mixins/form';
    import product from '@/utils/mixins/models/product';
    import drawer from '@/utils/mixins/includes/drawer';
    import products from '@selects/crm-product';

	export default {
        mixins: [form,product,drawer],
        components:{products},
        data() {
            return {
                is_new: false,
            }
        },
	  	methods: {
			...mapActions({
				save: "products/update",
				edit: 'products/show',
				destroyMaterial: 'products/deleteMaterial',
				destroySemiProduct: 'products/deleteSemiProduct'
            }),
            afterOpen(){
                this.form = this.getForm;
                this.product_materials = [];
                this.semi_products = [];
                this.load();
				this.active_step = 1;
            },
			load() {
                if (!this.loading && this.product) {
                    this.changeLoading(true);
                    this.edit({id: this.product.id})
                        .then((res) => {
                            this.form = this.getForm;
                            this.product_materials = JSON.parse( JSON.stringify( this.old_product_materials ));
                            this.semi_products = JSON.parse( JSON.stringify( this.old_semi_products ));
                            this.changeLoading();
                        })
                        .catch(err => {
                            this.$alert(err);
                            this.changeLoading();
                        });
                }
			},
			deleteMaterial(product_material) {
				this.destroyMaterial(product_material.id)
					.then(res => { this.$alert(res) })
					.catch(err => {	this.$alert(err) })
			},
			deleteSemiProduct(semi_product) {
				this.destroySemiProduct(semi_product.id)
				.then(res => {	this.$alert(res) })
				.catch(err => {	this.$alert(err) })
			},
  		}
	};
</script>