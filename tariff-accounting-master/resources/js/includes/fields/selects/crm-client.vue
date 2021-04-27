<template>
    <el-row>
        <el-form-item :label="label || $t('message.client')" size="small" :prop="prop">
            <el-col :span="20">
                <el-select :value="selected" @input="dispatch" :placeholder="plc || $t('message.client')" filterable clearable :size="size" class="d-block" remote reserve-keyword :remote-method="onSearch" :loading="loading">
                    <el-option v-for="(client,index) in clients" :key="'clients-' + index" :label="client.name" :value="client.id"></el-option>
                </el-select>
            </el-col>
            <el-col :span="4">
                <i class="el-icon-plus cursor-pointer ml-2" size="small" @click="showModal('create_client')"> </i>
                <!-- <div class="el-form-item__info" v-if="selected">
                    {{ $t('message.balance') }} {{ balance | formatMoney}}
                </div> -->
            </el-col>
        </el-form-item>
        <create @crm-close="afterCreated"></create>
    </el-row>
</template>

<script>
    import { mapGetters, mapActions } from "vuex";
    import create from '@/includes/modals/crm-client-create-modal';
    import mix from '@mixins/fields/form-item';

    export default {
        name:'clients',
        mixins: [mix],
        components:{create},
        props:{
            client_id: {
                default: null
            },
            prop:{
                default: 'client_id'
            },
        },
        watch: {
            client_id: {
                handler: function() {
                    this.dispatch(this.client_id)
                    console.log(this.client_id)

                    if ( !_.find(this.clients, ['id', this.client_id]) ) {
                        this.updateInventory({id: this.client_id})
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
        mounted() {
            if (this.clients && this.clients.length === 0) this.updateInventory()
        },
        computed: {
            ...mapGetters({
                clients: 'clients/inventory'
            }),
            balance: function() {
                return (this.selected ? _.find(this.clients, ['id', this.selected]).balance : 0)
            }
        },
        methods: {
            ...mapActions({
                updateInventory: 'clients/inventory'
            }),
            afterCreated(data){
                if (data.created && data.created === true){
                     this.dispatch(data.client.id)
                }
            }
        },
    }
</script>
