<template>
    <el-col :span="24">
        <header id="el-drawer__title" class="el-drawer__header">
            <span>
                {{ $t('message.new') }} {{ $t('message.Realization') | lowerFirst }}
            </span>
            <el-button v-can="['realizations.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit()"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main class="pt-2">
            <el-card class="box-card crm-card-pt-1">
                <el-form ref="form" :model="form" :rules="rules" :label-position="'right'" label-width="150px">
                    <el-col>
                        <el-form-item size="small" prop="number" class="d-inline-flex crm-document-number">
                            <span  class="document-title"> {{ form.id }} </span>
                            <template slot="label">
                                <span class="document-title">{{ $t('message.Realization') }} {{ $t('message.n') }}</span>
                            </template>
                        </el-form-item>
                        <el-form-item label-width="20px" size="small" prop="datetime" class="d-inline-flex">
                            <el-date-picker prefix-icon="el-icon-date" v-model="form.datetime" type="datetime"
                                :format="date_time_format" :value-format="date_time_format">
                            </el-date-picker>
                            <template slot="label">
                                <span class="document-title">{{ $t('message.from') | lowerFirst }}</span>
                            </template>
                        </el-form-item>
                        <el-form-item class="d-inline-flex" size="small">
                            <template slot="label">
                                <el-button @click="includeDrawer = true" type="primary" size="small" class="d-inline-flex"> {{ $t('message.Link document') }} </el-button>
                            </template>
                        </el-form-item>
                        <span class="el-dropdown-link float-right p-4">
                            <span class="text-secondary">{{ $t('message.owner') }}:</span> <span> {{ auth_name }}</span>
                        </span>
                    </el-col>
                    <el-col :span="8">
                        <users v-model="form.user_id" :user_id="form.user_id" prop="user_id" :label="$t('message.To whom')"></users>
                    </el-col>
                    <el-col :span="8">
                        <realization-types v-model="form.realizationable_type" :realizationable_type="form.realizationable_type" :disabled="false"></realization-types>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item :label="columns.realizationable_id.title" prop="realizationable_id">
                            <el-input v-model="form.realizationable_id" size="small"></el-input>
                        </el-form-item>
                    </el-col>
                </el-form>
            </el-card>
            <el-card class="mt-2 pb-2">
                <p>{{ $t('message.Before transferring the raw materials, please choose why the raw materials will be delivered (required document), the reason is to indicate the raw materials reserved for this document') }}</p>
                <el-table size="medium" :data="items" style="width: 100%" class="crm-el-table" v-loading="loadingMaterial">
                    <template slot="empty">
                        <span></span>
                    </template>
                    <el-table-column width="220" :label="$t('message.name')">
                        <template slot-scope="item">
                            <b>{{ (item.row.material ? item.row.material.name : '') | truncate }}</b>
                        </template>
                    </el-table-column>
                    <el-table-column :label="$t('message.available')">
                        <template slot-scope="item">
                            {{ item.row.available | formatNumber }}
                        </template>
                    </el-table-column>
                    <el-table-column :label="$t('message.Booked')">
                        <template slot-scope="item">
                            {{ item.row.booked | formatNumber }}
                        </template>
                    </el-table-column>
                    <el-table-column :label="$t('message.measurement')">
                        <template slot-scope="item">
                            {{ item.row.material ? item.row.material.measurement ? item.row.material.measurement.name : '' : '' }}
                        </template>
                    </el-table-column>
                    <el-table-column width="200" :label="$t('message.quantity')">
                        <template slot-scope="item">
                            <el-input v-model="item.row.quantity" size="mini"></el-input>
                        </template>
                    </el-table-column>
                    <el-table-column width="250" :label="$t('message.Armored Exemptions')">
                        <template slot-scope="item">
                            <el-input v-model="item.row.issued_from_booked" size="mini"></el-input>
                        </template>
                    </el-table-column>
                    <el-table-column width="90" :label="$t('message.delete')">
                        <template slot-scope="item">
                            <el-button @click.native.prevent="deleteRow(item.$index, items)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                        </template>
                    </el-table-column>
                </el-table>
                <el-col :span="12" class="mt-1">
                    <materials @append="appendMaterial" :plc="$t('message.product_select_plc')"></materials>
                </el-col>
            </el-card>
        </el-main>
        <el-drawer size="80%" :with-header="false" :append-to-body="true" :visible.sync="includeDrawer" ref="includeDrawer" @closed="drawerClosed('includeDrawerChild')"  @opened="drawerOpened('includeDrawerChild')">
            <documents drawer="includeDrawer" ref="includeDrawerChild"></documents>
        </el-drawer>
    </el-col>
</template>
<script>

    import {mapGetters,mapActions} from 'vuex';
    import drawer from '@/utils/mixins/includes/drawer';
    import form from '@/utils/mixins/form';
    import realization from "@/utils/mixins/models/realization";
    import documents from "./include/crm-documents";

    export default {
        mixins:[drawer,realization,form],
        components:{documents},
        computed:{
            ...mapGetters({
                lastId: 'realizations/lastId',
            })
        },
        methods:{
            ...mapActions({
                save: 'realizations/store',
                getLastId: 'realizations/getLastId',
                loadReservations: 'realizations/loadReservations'
            }),
            afterOpen() {
                this.form = this.getForm;

                if (!this.lastId) {
                    this.getLastId().then(res => {
                     this.form.id = res.last_id})
                }else{
                    this.form.id = this.lastId;
                }
            },
            afterLeave(){
                this.$store.commit("realizations/SET_SELECTED_ROW",null);
                this.items = [];
            }
        }
    }
</script>
