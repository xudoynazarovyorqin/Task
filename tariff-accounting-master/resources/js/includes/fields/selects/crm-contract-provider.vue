<template>
    <el-row>
        <el-form-item :label="label || $t('message.contract')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.contract')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(item,index) in items" :key="'contract_providers' + index" :label="'№ ' + item.number + ' от ' + item.begin_date" :value="item.id"></el-option>
                </el-select>
            </el-col>
            <el-col :span="4">
                <i class="el-icon-plus cursor-pointer ml-2" size="small" @click="showModal('create_contract_provider')"> </i>
                <div class="el-form-item__info" v-if="selected">
                    {{ $t('message.Sum') }} {{ balance | formatMoney}}
                </div>
            </el-col>
        </el-form-item>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>

<script>
    import create from '@/includes/modals/crm-contract-provider-create-modal';
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        name: 'contracts',
        mixins: [mix],
        components:{create},
        props: {
            provider_id:{
                default: null,
            },
            prop:{
                default: 'contract_provider_id'
            },
            contract_id: {
                default: null,
            },
        },
        watch: {
            provider_id: {
                handler: function(newVal,oldVal) {
                    if (newVal != oldVal) {
                        this.selected = '';
                    }
                },
                immediate: true,
                deep: true,
            },
            contract_id: {
                handler: function(newVal,oldVal) {
                    this.selected = this.contract_id;
                }
            }
        },
        mounted() {
            if (this.contract_providers && this.contract_providers.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                contract_providers: 'contractProviders/inventory'
            }),
            items: function() {
                return ((!this.provider_id) ? this.contract_providers : _.filter(this.contract_providers,['provider_id', this.provider_id]))
            },
            balance: function() {
                return (this.selected ? _.find(this.contract_providers, ['id', this.selected]).sum : 0)
            }
        },
        methods: {
            ...mapActions({
                updateInventory: 'contractProviders/inventory'
            }),
            afterCreated(data){
                if (data.created && data.created === true){
                    this.dispatch(data.contract_provider.id)
                }
            }
        },
    }
</script>