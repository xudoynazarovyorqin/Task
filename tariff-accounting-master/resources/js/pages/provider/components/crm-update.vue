<template>
    <div>
   		<header id="el-drawer__title" class="el-drawer__header">
			<span> {{ $t('message.new') }} {{ $t('message.provider') | lowerFirst }}</span>
			<el-button v-can="['providers.update']" type="success" size="small" class="mr-1" :loading="waiting"
				@click="submit(false)"> {{ $t('message.save') }}</el-button>
			<el-button v-can="['providers.update']" type="primary" size="small" class="mr-1" :loading="waiting"
				@click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
			<el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
			</el-button>
		</header>
        <el-main v-loading="loading">
            <el-form ref="form" status-icon :model="form" :rules="rules" :label-position="'right'" label-width="150px" size="mini">
                <el-row>
                    <el-col :span="8">
                        <el-form-item :label="columns.name.title" prop="name">
                            <el-input v-model="form.name" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.sku.title" prop="sku">
                            <el-input v-model="form.sku" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.email.title">
                            <el-input v-model="form.email" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.phone.title">
                            <el-input v-model="form.phone" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item :label="columns.fax.title">
                            <el-input v-model="form.fax" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.actual_address.title">
                            <el-input v-model="form.actual_address" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.inn.title">
                            <el-input v-model="form.inn" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.comment.title">
                            <el-input :rows="3" type="textarea" v-model="form.comment" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <types v-model="form.type_id" size="mini"></types>
                        <el-form-item :label="columns.full_name.title">
                            <el-input v-model="form.full_name" autocomplete="off"></el-input>
                        </el-form-item>
                        <el-form-item :label="columns.legal_address.title">
                            <el-input type="textarea" :rows="3" v-model="form.legal_address" autocomplete="off"></el-input>
                        </el-form-item>
                        <template v-if="form.type_id === 1">
                            <el-form-item :label="columns.mfo.title">
                                <el-input v-model="form.mfo" autocomplete="off"></el-input>
                            </el-form-item>
                            <el-form-item :label="columns.okonx.title">
                                <el-input v-model="form.okonx" autocomplete="off"></el-input>
                            </el-form-item>
                            <el-form-item :label="columns.oked.title">
                                <el-input v-model="form.oked" autocomplete="off"></el-input>
                            </el-form-item>
                            <el-form-item :label="columns.rkp_nds.title">
                                <el-input v-model="form.rkp_nds" autocomplete="off"></el-input>
                            </el-form-item>
                        </template>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="8" :offset="2">
                        <el-card class="mt-2" v-for="(item,index) in provider_contact_persons" :key="index">
                            <div slot="header" class="clearfix">
                                <span class="h4">{{ $t('message.Contact person') }}</span>
                                <el-button style="float: right;" circle type="danger" size="mini" icon="el-icon-delete" @click="removeContactPerson(index)"></el-button>
                            </div>
                            <el-form label-position="right" label-width="100px">
                                <el-form-item :label="$t('message.name')">
                                    <el-input v-model="item.full_name" size="mini"></el-input>
                                </el-form-item>
                                <el-form-item :label="$t('message.Position')">
                                    <el-input v-model="item.position" size="mini"></el-input>
                                </el-form-item>
                                <el-form-item :label="$t('message.phone')">
                                    <el-input v-model="item.phone" size="mini"></el-input>
                                </el-form-item>
                                <el-form-item :label="$t('message.email')">
                                    <el-input v-model="item.email" size="mini"></el-input>
                                </el-form-item>
                                <el-form-item :label="$t('message.comment')">
                                    <el-input v-model="item.comment" size="mini"></el-input>
                                </el-form-item>
                            </el-form>
                        </el-card>
                        <el-button type="primary" round size="mini" @click="addContactPerson()" class="mt-2">
                                <i class="el-icon-plus"></i> {{ $t('message.Contact person') }}
                        </el-button>
                    </el-col>
                    <el-col :span="8" :offset="4">
                        <el-card class="mt-2" v-for="(item,index) in provider_checking_accounts" :key="index">
                            <div slot="header" class="clearfix">
                                <span class="h4">{{ $t('message.Payment account') }}</span>
                                <el-button style="float: right;" circle type="danger" size="mini" icon="el-icon-delete" @click="removeCheckingAccount(index)"></el-button>
                            </div>
                            <el-form label-position="right" label-width="120px">
                                <el-form-item :label="$t('message.Bank')">
                                    <el-input v-model="item.bank" size="mini"></el-input>
                                </el-form-item>
                                <el-form-item :label="$t('message.address')">
                                    <el-input v-model="item.address" size="mini"></el-input>
                                </el-form-item>
                                <el-form-item :label="$t('message.Correspondent account')">
                                    <el-input v-model="item.correspondent_account" size="mini"></el-input>
                                </el-form-item>
                                <el-form-item :label="$t('message.Payment account')">
                                    <el-input v-model="item.checking_account" size="mini"></el-input>
                                </el-form-item>
                            </el-form>
                        </el-card>
                        <el-button type="primary" round size="mini" @click="addCheckingAccount()" class="mt-2">
                                <i class="el-icon-plus"></i> {{ $t('message.Payment account') }}
                        </el-button>
                    </el-col>
                </el-row>
            </el-form>
        </el-main>
    </div>
</template>
<script>
    import drawer from '@/utils/mixins/includes/drawer';
    import form from '@/utils/mixins/form';
    import provider from '@/utils/mixins/models/provider';
    import {mapGetters,mapActions} from 'vuex';

    export default {
        props:['provider'],
        mixins:[form,drawer,provider],
        computed: {
            ...mapGetters({
                old_provider_contact_persons: 'providers/provider_contact_persons',
                old_provider_checking_accounts: 'providers/provider_checking_accounts',
            })
        },
        methods:{
            ...mapActions({
                save: 'providers/update',
                edit: 'providers/show',
            }),
            afterOpen(){
                this.form = this.getForm;
                this.provider_contact_persons = [];
                this.provider_checking_accounts = [];
                this.load();
            },
            load(){
                if (!this.loading && this.provider) {
                    this.changeLoading(true)
                    this.edit(this.provider.id)
                    .then(res => {
                        this.changeLoading();
                        this.form = this.getForm;
                        this.provider_contact_persons = JSON.parse( JSON.stringify( this.old_provider_contact_persons ));
                        this.provider_checking_accounts = JSON.parse( JSON.stringify( this.old_provider_checking_accounts ))
                    })
                    .catch(err => {
                        this.changeLoading();
                        this.$alert(err)
                    })
                }
            }
        }
    }
</script>
