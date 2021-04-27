<template>
    <el-row>
        <el-form-item :label="label || $t('message.contract')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.contract')" filterable clearable :size="size" class="d-block" remote reserve-keyword :remote-method="onSearch" :loading="loading">
                    <el-option v-for="(item,index) in items" :key="'contract_clients' + index" :label="'№ ' + item.number + ' от ' + item.begin_date" :value="item.id"></el-option>
                </el-select>
            </el-col>
            <el-col :span="4">
                <i v-if="is_with_add" class="el-icon-plus cursor-pointer ml-2" size="small" @click="showModal('create_contract_client')"> </i>
                <div class="el-form-item__info" v-if="selected">
                    {{ $t('message.balance') }} {{ balance | formatMoney}}
                </div>
            </el-col>
        </el-form-item>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>

<script>
    import create from '@/includes/modals/crm-contract-client-create-modal';
    import { mapGetters, mapActions } from "vuex";
    import mix from '@mixins/fields/form-item';

    export default {
        name: 'contracts',
        mixins: [mix],
        components:{create},
        props: {
            client_id:{
                default: null,
            },
            prop:{
                default: 'contract_client_id'
            },
            contract_id: {
                default: null,
            },
            is_with_add: {
                default: false
            }
        },
        watch: {
            client_id: {
                handler: function(newVal,oldVal) {
                    if (newVal != oldVal) {
                        this.selected = '';

                        if( newVal ) { // agar client_id ozgarsa dogovorni olib kelish
                            this.updateInventory({client_id: newVal})
                            .then(res => {
                                this.loading = false;
                            })
                            .catch(err => {
                                this.loading = false;
                                this.$alert(err);
                            });
                        }
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
            if (this.contract_clients && this.contract_clients.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                contract_clients: 'contractClients/inventory'
            }),
            items: function() {
                return ((!this.client_id) ? this.contract_clients : _.filter(this.contract_clients,['client_id', this.client_id]))
            },
            balance: function() {
                if( this.selected ) {
                    let contract = _.find(this.contract_clients, ['id', this.selected]);
                    if( contract ) {
                        return contract.remainder;
                    }
                    else {
                        return 0;
                    }
                }
                else {
                    return 0;
                }
            }
        },
        methods: {
            ...mapActions({
                updateInventory: 'contractClients/inventory'
            }),
            afterCreated(data){
                if (data.created && data.created === true){
                    this.dispatch(data.contract_client.id)
                }
            }
        },
    }
</script>









