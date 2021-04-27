<template>
    <modal :name="name" width="90%" height="auto"  @before-open="beforeOpen" :scrollable="true">
        <div class="el-dialog__header cursor-move">
            <span class="el-dialog__title">{{ $t('message.new') }} {{ $t('message.role') | lowerFirst }}</span>
            <button type="button" class="el-dialog__headerbtn" @click="closeModal(name)">
                <i class="el-dialog__close el-icon el-icon-close"></i>
            </button>
        </div>
        <div class="el-dialog__body">
            <el-form ref="form" status-icon :model="form" :rules="rules" :label-position="'right'" label-width="150px" size="mini">
                <el-row>
                    <el-col :span="10">
                        <el-form-item :label="columns.name.title" prop="name">
                            <el-input v-model="form.name" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="10">
                        <el-form-item :label="columns.slug.title" prop="slug">
                            <el-input v-model="form.slug" autocomplete="off"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="10">
                        <el-form-item :label="'Права доступь'">
                            <el-tree
                                :data="dataPermissions"
                                show-checkbox
                                node-key="id"
                                ref="tree"
                                :default-expanded-keys="[2, 3]"
                                :default-checked-keys="[5]"
                                :props="defaultProps">
                            </el-tree>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-form>
        </div>
        <div class="el-dialog__footer">
            <span class="dialog-footer">
                <el-button @click="closeModal(name)" round size="mini" :loading="waiting">{{ $t('message.cancel') }}</el-button>
                <el-button type="primary" @click="submit" round size="mini" :loading="waiting">{{ $t('message.save') }}</el-button>
            </span>
        </div>
    </modal>
</template>
<script>
    import {mapGetters,mapActions} from 'vuex';
    import create from '@mixins/modals/create-modal';

    export default {
        mixins:[create],
        data(){
            return {
                name: 'create_role',
                defaultProps: {
                    children: 'children',
                    label: 'name'
                }
            }
        },
        async mounted(){
          if (this.dataPermissions && this.dataPermissions.length === 0){
              await this.loadParentPermissions()
          }
        },
        computed: {
            ...mapGetters({
                dataPermissions: 'permissions/parent_permissions',
                roles: 'roles/list',
                rules: 'roles/rules',
                model: 'roles/model',
                columns: 'roles/columns'
            })
        },
        methods:{
            ...mapActions({
                save: 'roles/store',
                updateList: 'roles/index',
                loadParentPermissions: 'permissions/parent_permissions',
                updateInventory: 'roles/inventory',
            }),
            beforeOpen(){
                this.form = JSON.parse( JSON.stringify( this.model ));
            },
            submit(){
                let checkedPermissions = this.$refs.tree.getCheckedNodes();
                let perms = [];
                for (let key in checkedPermissions){
                    if (checkedPermissions.hasOwnProperty(key)){
                        let checkedPermission = checkedPermissions[key];
                        perms.push(checkedPermission.id)
                    }
                }
                this.form['permissions'] = perms;
                this.$refs['form'].validate((valid) => {
                    if (valid) {
                        this.changeWaiting(true);
                        this.save(this.form)
                            .then(async (res) => {
                                await this.updateInventory();
                                this.loadParentPermissions()
                                this.$emit('crm-close', {created: true, role: res.data.role});
                                this.$alert(res);
                                this.changeWaiting();
                                this.closeModal(this.name)
                            })
                            .catch(err => {
                                this.changeWaiting();
                                this.$alert(err)
                            })
                    }
                });
            }
        },
    }

</script>
