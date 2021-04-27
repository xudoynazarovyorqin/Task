<template>
    <el-card class="mt-2">
        <el-tabs>
            <el-tab-pane>
                <span slot="label"><i class="el-icon-chat-dot-round"></i> {{ $t('message.history')}}</span>
                <el-table :data="histories">
                  <el-table-column :label="$t('message.created_at')" prop="created_at"></el-table-column>
                  <el-table-column :label="$t('message.comment')" prop="comment"></el-table-column>
                  <el-table-column :label="$t('message.level')">
                      <template slot-scope="item">
                          <el-tag size="small" :color="(item.row.level) ? item.row.level.color : ''">{{ (item.row.level) ? item.row.level.name : '' }}</el-tag>
                      </template>
                  </el-table-column>
                  <el-table-column :label="$t('message.user')">
                      <template slot-scope="item">
                          {{ item.row.user ? item.row.user.name : '' }}
                      </template>
                  </el-table-column>
                </el-table>
                <el-form ref="form" :model="form" :rules="rules" label-width="120px" v-if="is_edit === true" class="mt-2">
                    <levels v-model="form.level_id"></levels>
                    <el-form-item :label="$t('message.comment')">
                        <el-input v-model="form.comment" type="textarea" row="4"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button size="small" round type="primary" @click="submit()"> {{ $t('message.send') }} </el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
            <el-tab-pane :label="$t('message.additional')">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td colspan="2">{{ $t('message.browser')}}</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $t('message.ip_address') }}</td>
                            <td>{{ created_info.ip_address }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t('message.user_agent') }}</td>
                            <td>{{ created_info.user_agent }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t('message.user') }}</td>
                            <td>{{ (created_info.user) ? created_info.user.name : '' }}</td>
                        </tr>
                        <tr>
                            <td>{{ $t('message.time') }}</td>
                            <td>{{ created_info.created_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </el-tab-pane>
        </el-tabs>
    </el-card>
</template>

<script>
    import {mapGetters, mapActions} from 'vuex';
    import levels from '@selects/crm-level';
    export default {
        props:['is_edit'],
        components:{levels},
        data() {
            return {
                form: {
                    level_id: '',
                    comment: ''
                },
                rules: {
                    level_id: [
                        {required: true, message: 'Пожалуйста, выберите уровень', trigger: 'change'}
                    ],
                },
            }
        },
        computed: {
            ...mapGetters({
                columns: 'sales/columns',
                histories: 'sales/histories',
                created_info: 'sales/created_info',
                sale: 'sales/model'
            }),
        },
        methods: {
            ...mapActions({
                save: 'sales/historyStore',
                loadLevelInventory: 'levels/inventory'
            }),
            submit(){
                this.form['sale_id'] = this.sale.id;
                this.$refs['form'].validate((valid) => {
                    if (valid){
                        this.save(this.form)
                            .then(res=> {
                                this.$alert(res);
                                this.loadLevelInventory()
                                this.$emit('crm-change');
                                this.resetForm('form');
                            })
                            .catch(err=> {
                                this.$alert(err)
                            })
                    }
                })
            },
            resetForm(formName) {
                this.$refs[formName].resetFields();
            },
        }
    }
</script>
