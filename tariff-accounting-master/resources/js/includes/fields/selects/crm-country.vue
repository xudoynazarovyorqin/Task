<template>
    <el-row>
        <el-form-item :label="label || $t('message.country')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.country')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(country,index) in countries" :key="'countries-' + index" :label="country.name" :value="country.id"></el-option>
                </el-select>
            </el-col>
            <el-col :span="4">
                <i class="el-icon-plus cursor-pointer ml-2" size="small" @click="showModal('create_country')"> </i>
            </el-col>
        </el-form-item>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import create from '@/includes/modals/crm-country-create-modal';
    import mix from '@mixins/fields/form-item';

    export default {
        name:'countries',
        mixins: [mix],
        components:{create},
        props:{
            country_id: {
                default: null
            },
            prop:{
                default: 'country_id'
            },
        },
        watch: {
            country_id: {
                handler: function() {
                    this.selected = this.country_id;
                }
            },
            immediate: true,
            deep: true,
        },
        mounted() {
            if (this.countries && this.countries.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                countries: 'countries/inventory'
            }),
            balance: function() {
                return (this.selected ? _.find(this.countries, ['id', this.selected]).balance : 0)
            }
        },
        methods: {
            ...mapActions({
                updateInventory: 'countries/inventory'
            }),
            afterCreated(data){
                if (data.created && data.created === true){
                    this.dispatch(data.country.id)
                }
            }
        },
    }
</script>