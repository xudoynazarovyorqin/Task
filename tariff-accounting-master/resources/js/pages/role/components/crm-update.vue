
<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span> {{ $t('message.edit') }} {{ $t('message.role') }} № {{ role.id }}</span>
            <el-button v-can="['roles.update']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <el-main v-loading="loading">
            <el-form ref="form" status-icon :model="form" :rules="rules" :label-position="'right'" label-width="150px" size="mini">
                <el-col :span="10">
                    <el-form-item :label="columns.name.title" prop="name">
                        <el-input v-model="form.name" autocomplete="off"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="10">
                    <el-form-item :label="columns.slug.title" prop="slug">
                        <el-input readonly v-model="form.slug" autocomplete="off"></el-input>
                    </el-form-item>
                </el-col>
                <el-col :span="10">
                    <el-form-item :label="'Права доступь'">
                        <el-tree
                            :data="dataPermissions"
                            show-checkbox
                            node-key="id"
                            ref="tree"
                            :default-checked-keys="default_checked_permissions"
                            :props="defaultProps">
                        </el-tree>
                    </el-form-item>
                </el-col>
            </el-form>
        </el-main>
    </div>
</template>
<script>
    import {mapGetters,mapActions} from 'vuex';
    import form from '@/utils/mixins/form';
    import role from '@/utils/mixins/models/role';
    import drawer from '@/utils/mixins/includes/drawer';

    export default {
        props:['role'],
        mixins: [form,role,drawer],
        data(){
            return {
                default_checked_permissions: []
            }
        },
        async mounted(){
            if (this.dataPermissions && this.dataPermissions.length === 0)  await this.loadParentPermissions();
        },
        computed: {
            ...mapGetters({
                permissions: 'roles/permissions'
            })
        },
        methods:{
            ...mapActions({
                save: 'roles/update',
                loadParentPermissions: 'permissions/parent_permissions',
                edit: 'roles/show',
            }),
            afterOpen(){
                this.form = this.getForm;
                this.load();
            },
            load(){
                if (!this.loading && this.role) {
                    this.changeLoading(true);
                    this.edit(this.role.id)
                        .then((res) => {
                            this.default_checked_permissions = [];
                            for (let key in this.permissions){
                                if (this.permissions.hasOwnProperty(key)){
                                    this.default_checked_permissions.push(this.permissions[key].id)
                                }
                            }
                            this.form = JSON.parse( JSON.stringify( this.model ));
                            this.$refs.tree.setCheckedKeys(this.default_checked_permissions);
                            this.changeLoading()
                        })
                        .catch(err => {
                            this.changeLoading()
                        });                    
                }
            }
        },
    }
</script>
