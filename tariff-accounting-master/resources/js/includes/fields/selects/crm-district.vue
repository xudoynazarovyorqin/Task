<template>
    <el-row>
        <el-form-item :label="label || $t('message.district')" size="small" :prop="prop">
            <el-col :span="24">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.district')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(district, index) in districts" :key="'districts-' + index" :label="district.name" :value="district.id"></el-option>
                </el-select>
            </el-col>
        </el-form-item>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        name:'districts',
        mixins: [mix],
        props:{
            district_id: {
                default: null
            },
            prop:{
                default: 'district_id'
            },
        },
        watch: {
            district_id: {
                handler: function() {
                    this.dispatch(this.district_id)
                }
            },
            immediate: true,
            deep: true,
        },
        mounted() {
            if (this.districts && this.districts.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                districts: 'districts/inventory'
            }),
        },
        methods: {
            ...mapActions({
                updateInventory: 'districts/inventory'
            }),
        },
    }
</script>
