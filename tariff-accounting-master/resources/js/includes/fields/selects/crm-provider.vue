<template>
    <el-row>
        <el-form-item :label="label || $t('message.provider')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.provider')" filterable clearable :size="size" class="d-block">
                    <el-option v-for="(provider,index) in providers" :key="'providers-' + index" :label="provider.name" :value="provider.id"></el-option>
                </el-select>
            </el-col>
            <el-col :span="4">
                <i class="el-icon-plus cursor-pointer ml-2" size="small" @click="showModal('create_provider')"> </i>
                <div class="el-form-item__info" v-if="selected">
                    {{ $t('message.balance') }} {{ balance | formatMoney}}
                </div>
            </el-col>
        </el-form-item>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import create from '@/includes/modals/crm-provider-create-modal';
    import mix from '@mixins/fields/form-item';

    export default {
        name:'providers',
        mixins: [mix],
        components:{create},
        props:{
            provider_id: {
                default: null
            },
            prop:{
                default: 'provider_id'
            },
        },
        watch: {
            provider_id: {
                handler: function() {
                    this.dispatch(this.provider_id)
                }
            },
            immediate: true,
            deep: true,
        },
        mounted() {
            if (this.providers && this.providers.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                providers: 'providers/inventory'
            }),
            balance: function() {
                return (this.selected ? _.find(this.providers, ['id', this.selected]).balance : 0)
            }
        },
        methods: {
            ...mapActions({
                updateInventory: 'providers/inventory'
            }),
            afterCreated(data){
                if (data.created && data.created === true){
                    this.dispatch(data.provider.id)
                }
            }
        },
    }
</script>