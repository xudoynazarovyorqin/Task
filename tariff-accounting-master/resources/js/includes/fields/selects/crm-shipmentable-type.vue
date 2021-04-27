<template>
    <el-row>
        <el-form-item :label="label || $t('message.Type document')" size="small" :prop="prop">
            <el-col :span="24">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.Type document')" :disabled="disabled" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(val,key) in items" :key="'shipmentable_type' + key" :label="val" :value="key"></el-option>
                </el-select>
            </el-col>
        </el-form-item>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        name: 'documents',
        mixins: [mix],
        props: {
            shipmentable_type:{
                default: null,
            },
            prop:{
                default: 'shipmentable_type'
            },
            disabled:{
                default:false
            }
        },
        watch: {
            shipmentable_type: {
                handler: function(newVal,oldVal) {
                    this.selected = this.shipmentable_type;
                }
            },
            immediate:true,
            deep:true
        },
        computed: {
            ...mapGetters({
                items: 'shipments/shipmentable_types'
            }),
        }
    }
</script>