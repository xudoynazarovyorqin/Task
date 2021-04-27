<template>
    <modal :name="name" width="80%" height="auto" :draggable="true" @before-open="beforeOpen">
        <div class="el-dialog__header cursor-move">
            <span class="el-dialog__title">{{ $t('message.new') }} {{ $t('message.material') | lowerFirst }}</span>
            <button type="button" class="el-dialog__headerbtn" @click="closeModal(name)">
                <i class="el-dialog__close el-icon el-icon-close"></i>
            </button>
        </div>
        <div class="el-dialog__body">
            <el-form ref="form" status-icon :model="form" :rules="rules" :label-position="'right'" label-width="170px" size="mini">
                <el-col :span="12">
                    <el-form-item :label="columns.name.title" prop="name"  maxlength="200" show-word-limit>
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.code.title" prop="code">
                        <el-input v-model="form.code" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.price.title" prop="price">
                        <el-col :span="12">
                            <price v-model="form.price"></price>
                        </el-col>
                        <el-col :span="11" :offset="1">
                            <currencies v-model="form.price_currency_id" :currency_id="form.price_currency_id" size="mini"></currencies>
                        </el-col>
                    </el-form-item>
                    <measurements v-model="form.measurement_id" :measurement_id="form.measurement_id" size="mini"></measurements>
                    <el-form-item :label="columns.measurement_changeable.title">
                        <el-switch v-model="form.measurement_changeable"></el-switch>
                    </el-form-item>
                    <template v-if="form.measurement_changeable">
                        <measurements v-model="form.additional_measurement_id" :measurement_id="form.additional_measurement_id" size="mini" :label="columns.additional_measurement_id.title" prop=""></measurements>
                        <el-form-item :label="addMeasurementLabel" v-if="form.measurement_id && form.additional_measurement_id">
                            <el-input v-model="form.additional_measurement_rate" style="width:180px !important">
                                <template slot="append" class="el-input-group__append_measurement">{{ addMeasurementName }}</template>
                            </el-input>
                        </el-form-item>
                    </template>
                    <warehouse-types size="mini" v-model="form.warehouse_type_id"></warehouse-types>
                </el-col>
                <el-col :span="12">
                    <el-form-item :label="columns.type_id.title">
                        <el-select v-model="form.type_id" :placeholder="$t('message.choose')" filterable clearable>
                            <el-option v-for="item in types" :key="item.id" :label="item.name" :value="item.id"></el-option>
                        </el-select>
                    </el-form-item>
                    <countries size="mini" v-model="form.country_id"></countries>
                    <el-form-item :label="columns.critical_weight.title" prop="critical_weight">
                        <el-input type="number" v-model="form.critical_weight" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.sku.title" prop="sku">
                        <el-input v-model="form.sku" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.is_active.title">
                        <el-select v-model="form.is_active" filterable clearable>
                            <el-option :label="$t('message.yes')" :value="1"></el-option>
                            <el-option :label="$t('message.no')" :value="0"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item :label="columns.is_reworking.title">
                        <el-select v-model="form.is_reworking" filterable clearable>
                            <el-option :label="$t('message.yes')" :value="1"></el-option>
                            <el-option :label="$t('message.no')" :value="0"></el-option>
                        </el-select>
                    </el-form-item>
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
    import {mapGetters,mapActions} from 'vuex';
    import create from '@mixins/modals/create-modal';
	import material from '@/utils/mixins/models/material';

    export default {
        mixins: [create,material],
        data(){
            return {
                name: 'create_material',
            }
        },
        methods:{
            ...mapActions({
                save: 'materials/store',
            }),
            beforeOpen(){
                this.form = this.getForm;
                if (_.size(this.currencies)) {
                    const active_currency = _.find(this.currencies, 'active');
                    if (active_currency) {
                        this.form.price_currency_id = active_currency.id;
                    }
                }
            },
            submit(){
                if (!this.waiting) {
                    this.$refs['form'].validate((valid) => {
                        if (valid) {
                            this.changeWaiting(true);
                            this.save(this.form)
                                .then(async (res) => {
                                    this.updateInventory();
                                    this.$emit('crm-close', {created: true, material: res.data.material});
                                    this.$alert(res);
                                    this.changeWaiting();
                                    this.closeModal(this.name);
                                })
                                .catch(err => {
                                    this.changeWaiting();
                                    this.$alert(err);
                                })
                        }
                    });
                }
            }

        }
    }
</script>
