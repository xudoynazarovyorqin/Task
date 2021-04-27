<template>
    <el-row>
        <el-form-item :label="label || $t('message.Type document')" size="small" :prop="prop">
            <el-col :span="24">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.Type document')" :disabled="disabled" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(val,key) in items" :key="'realizationable_type' + key" :label="val" :value="key"></el-option>
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
            realizationable_type:{
                default: null,
            },
            prop:{
                default: 'realizationable_type'
            },
            disabled:{
                default:false
            }
        },
        watch: {
            realizationable_type: {
                handler: function(newVal,oldVal) {
                    this.selected = this.realizationable_type;
                }
            },
            immediate:true,
            deep:true
        },
        computed: {
            ...mapGetters({
                items: 'realizations/realization_types'
            }),
        }
    }
</script>