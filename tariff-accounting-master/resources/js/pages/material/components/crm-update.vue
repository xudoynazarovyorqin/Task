<template>
<div>
    <header id="el-drawer__title" class="el-drawer__header">
        <span> {{ $t('message.edit') }} {{ $t('message.material') | lowerFirst }} № {{ material.id }}</span>
        <el-button v-can="['materials.update']" type="success" size="small" class="mr-1" :loading="waiting"
            @click="submit(false)"> {{ $t('message.save') }}</el-button>
        <el-button v-can="['materials.update']" type="primary" size="small" class="mr-1" :loading="waiting"
            @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
        <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
        </el-button>
    </header>
    <el-main class="pt-3" v-loading="loading">
        <ElCard shadow="never">
            <el-form ref="form" status-icon :model="form" :rules="rules" :label-position="'right'"
                label-width="170px" size="mini">
                <el-col :span="24">
                    <el-form-item :label="columns.name.title" prop="name">
                        <el-input v-model="form.name" autocomplete="off" maxlength="200" show-word-limit></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <el-form-item :label="columns.code.title" prop="code">
                        <el-input v-model="form.code" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.price.title" prop="price">
                        <el-col :span="12">
                            <price v-model="form.price" :old="form.price"></price>
                        </el-col>
                        <el-col :span="11" :offset="1">
                            <currencies v-model="form.price_currency_id" :currency_id="form.price_currency_id" size="mini"></currencies>
                        </el-col>
                    </el-form-item>
                    <el-form-item :label="columns.critical_weight.title">
                        <el-input type="number" v-model="form.critical_weight" autocomplete="off"></el-input>
                    </el-form-item>
                    <measurements v-model="form.measurement_id" :measurement_id="form.measurement_id" size="mini"></measurements>
                    <el-form-item :label="columns.measurement_changeable.title">
                        <el-switch v-model="form.measurement_changeable"></el-switch>
                    </el-form-item>
                    <measurements v-model="form.additional_measurement_id" :measurement_id="form.additional_measurement_id" size="mini" :label="columns.additional_measurement_id.title" prop=""></measurements>
                    <el-form-item :label="addMeasurementLabel" v-if="form.measurement_id && form.additional_measurement_id">
                        <el-input v-model="form.additional_measurement_rate" style="width:180px !important">
                            <template slot="append" class="el-input-group__append_measurement">{{ addMeasurementName }}</template>
                        </el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="12">
                    <warehouse-types v-model="form.warehouse_type_id" :warehouse_type_id="form.warehouse_type_id" prop="" size="mini"></warehouse-types>
                    <el-form-item :label="columns.sku.title" prop="sku">
                        <el-input v-model="form.sku" autocomplete="off"></el-input>
                    </el-form-item>
                    <el-form-item :label="columns.is_reworking.title">
                        <el-select v-model="form.is_reworking" filterable clearable>
                            <el-option :label="$t('message.yes')" :value="1"></el-option>
                            <el-option :label="$t('message.no')" :value="0"></el-option>
                        </el-select>
                    </el-form-item>
                    <countries v-model="form.country_id" :country_id="form.country_id"  prop="" size="mini"></countries>
                    <el-form-item :label="columns.is_active.title">
                        <el-select v-model="form.is_active" filterable clearable>
                            <el-option :label="$t('message.yes')" :value="1"></el-option>
                            <el-option :label="$t('message.no')" :value="0"></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
            </el-form>
        </ElCard>
    </el-main>
</div>
</template>
<script>
    import { mapGetters, mapActions } from 'vuex';
	import form from '@/utils/mixins/form';
	import material from '@/utils/mixins/models/material';
    import drawer from '@/utils/mixins/includes/drawer';

    export default {
        mixins: [form,material,drawer],
        methods:{
            ...mapActions({
                save: 'materials/update',
                edit: 'materials/show',
            }),
            afterOpen(){
                this.form = this.getForm;
                this.load()
            },
            load(){
                if (!this.loading && this.material) {
					this.changeLoading(true);
					this.edit({id: this.material.id})
						.then(res => {
                            this.form = this.getForm;
                            this.changeLoading();
						})
						.catch(err => {
						    this.changeLoading();
						})
				}
            },
        }
    }

</script>
