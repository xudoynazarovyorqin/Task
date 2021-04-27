<template>
 <modal :name="name" width="90%" height="auto" :draggable="true" @before-open="beforeOpen">
    <div class="el-dialog__header cursor-move">
        <span class="el-dialog__title">{{ $t('message.new') }} {{ $t('message.product') | lowerFirst }}</span>
        <button type="button" class="el-dialog__headerbtn" @click="closeModal(name)">
            <i class="el-dialog__close el-icon el-icon-close"></i>
        </button>
    </div>
    <div class="el-dialog__body">
        <el-form ref="form" status-icon :model="form" :rules="rules" :label-position="'right'" label-width="150px" size="mini">
            <el-steps :active="active_step" simple  finish-status="success" v-if="form.production" shadow="always">
                <el-step :title="$t('message.main')" class="cursor-pointer"  icon="el-icon-edit"></el-step>
                <el-step :title="$t('message.materials')" class="cursor-pointer" icon="el-icon-upload" v-if="form.production_type == 'production' || form.production_type == 'assembly'"></el-step>
                <el-step :title="$t('message.semi_products')" class="cursor-pointer" icon="el-icon-set-up" v-if="form.production_type == 'assembly'"></el-step>
            </el-steps>
            <el-row class="mt-2" v-if="active_step == 1">
                <el-col :span="18">
                    <el-form-item :label="columns.name.title" prop="name">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="24">
                    <el-col :span="8">
                        <el-form-item :label="columns.code.title">
                            <el-input v-model="form.code" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.purchase_price.title" prop="purchase_price">
                            <el-col :span="12">
                                <product-price v-model="form.purchase_price"></product-price>
                            </el-col>
                            <el-col :span="11" :offset="1" class="ml-1">
                                <currencies v-model="form.purchase_currency_id" :currency_id="form.purchase_currency_id" size="mini"></currencies>
                            </el-col>
                        </el-form-item>
                        <el-form-item :label="columns.selling_price.title" prop="selling_price">
                            <el-col :span="12">
                                <product-price v-model="form.selling_price"></product-price>
                            </el-col>
                            <el-col :span="11" :offset="1" class="ml-1">
                                <currencies v-model="form.selling_currency_id" size="mini" :currency_id="form.selling_currency_id"></currencies>
                            </el-col>
                        </el-form-item>
                        <measurements size="mini" v-model="form.measurement_id"></measurements>
                    </el-col>
                    <el-col :span="8">
                        <warehouse-types size="mini" v-model="form.warehouse_type_id"></warehouse-types>
                        <categories size="mini" v-model="form.categories"></categories>
                        <el-form-item :label="columns.nds.title">
                            <el-input v-model="form.nds" autocomplete="off"></el-input>
                        </el-form-item>
                        <countries size="mini" v-model="form.country_id"></countries>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item :label="columns.vendor_code.title">
                            <el-input v-model="form.vendor_code" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.recycled.title">
                            <el-checkbox v-model="form.recycled"></el-checkbox>
                        </el-form-item>
                        <el-form-item :label="columns.production.title">
                            <el-checkbox v-model="form.production"></el-checkbox>
                        </el-form-item>
                        <el-form-item v-if="form.production" :label="columns.production_type.title">
                            <el-select v-model="form.production_type" :placeholder="$t('message.choose')" filterable clearable>
                                <el-option :label="$t('message.semi_product')" :value="'production'"></el-option>
                                <el-option disabled :label="$t('message.ready_product')" :value="'assembly'"></el-option>
                            </el-select>
                        </el-form-item>
                        <el-form-item :label="columns.description.title">
                            <el-input :rows="3" type="textarea" v-model="form.description" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                </el-col>
            </el-row>
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
            </template>
            <el-col :span="24" class="mt-4 text-center" v-if="form.production">
                <el-button-group>
                    <el-button type="primary" v-if="active_step > 1" @click="prev" icon="el-icon-arrow-left">  {{ $t('message.prev')}}</el-button>
                    <el-button type="primary"  v-if="(form.production_type == 'production' && active_step  < 2) || (form.production_type == 'assembly' && active_step < 3)" @click="next"> {{ $t('message.next')}}<i class="el-icon-arrow-right el-icon-right"></i></el-button>
                </el-button-group>
            </el-col>
        </el-form>
    </div>
    <div class="el-dialog__footer">
        <span class="dialog-footer">
            <el-button @click="closeModal(name)" round size="mini" :loading="waiting">{{ $t('message.cancel') }}</el-button>
            <el-button type="primary" @click="submit" round size="mini" :loading="waiting">{{ $t('message.save') }}</el-button>
        </span>
    </div>
</modal>
</template>
<script>
	import { mapGetters, mapActions } from "vuex";
    import create from '@mixins/modals/create-modal';
    import product from '@mixins/models/product';

	export default {
		mixins: [create,product],
        data(){
            return {
                name: 'create_product',
            }
        },
		methods: {
			...mapActions({
				save: "products/store",
			}),
			beforeOpen(){
                this.form = this.getForm;
                this.product_materials = [];
                this.semi_products = [];
                this.active_step = 1;
                if (_.size(this.currencies)) {
                    const active_currency = _.find(this.currencies, 'active');
                    if (active_currency) {
                        this.form.purchase_currency_id = active_currency.id;
                        this.form.selling_currency_id = active_currency.id;
                    }
                }
            },
			submit() {
				if (!this.waiting) {
                    /**
                     * Get product materials
                     */
                    this.form['product_materials'] = this.product_materials.map((item) => {
                        return { material_id: item.material.id, quantity: item.quantity, inverse_quantity: item.inverse_quantity }
                    });
                    /**
                     * Get Semi products
                     */
                    this.form['semi_products'] = [];
					this.$refs["form"].validate(valid => {
						if (valid) {
                        this.changeWaiting(true);
						this.save(this.form)
							.then(async res => {
								this.updateInventory();
								this.changeWaiting();
								this.$alert(res);
								this.$emit("crm-close", {created: true, product: res.data.product});
                                this.closeModal(this.name);
							})
							.catch(err => {
                            	this.changeWaiting();
								this.$alert(err);
							});
						}
					});
				}
			}
		}
	};
</script>
