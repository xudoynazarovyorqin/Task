<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span> {{ $t('message.new') }} {{ $t('message.role') }}</span>
            <el-button v-can="['roles.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}</el-button>
        </header>
        <div class="row">
            <div class="col-12 mt-2">
                <div class="modal-body">
                    <el-form ref="form" status-icon :model="form" :rules="rules" :label-position="'right'" label-width="150px" size="mini">
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
                                    :default-expanded-keys="[]"
                                    :default-checked-keys="[]"
                                    :props="defaultProps">
                                </el-tree>
                            </el-form-item>
                        </el-col>
                    </el-form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapGetters,mapActions} from 'vuex';
    import form from '@/utils/mixins/form';
    import role from '@/utils/mixins/models/role';
    import drawer from '@/utils/mixins/includes/drawer';

    export default {
        mixins: [form,role,drawer],
        methods:{
            ...mapActions({
                save: 'roles/store',
            }),
            afterOpen(){
                this.form = this.getForm;
            }
        },
    }
</script>
