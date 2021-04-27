<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span> {{ $t('message.edit') }} {{ $t('message.contract') | lowerFirst }}</span>
            <el-button v-can="['contractClients.update']" type="success" size="small" class="mr-1" :loading="waiting"
                @click="submit(false)"> {{ $t('message.save') }}</el-button>
            <el-button v-can="['contractClients.update']" type="primary" size="small" class="mr-1" :loading="waiting"
                @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
            <el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
            </el-button>
        </header>
        <el-main>
          <el-form
            ref="form"
            status-icon
            :model="form"
            :rules="rules"
            :label-position="'right'"
            class="style__label"
            size="mini"
          >
            <el-row :gutter="20">
              <el-col :span="8">
                <el-form-item :label="columns.number.title" prop="number">
                  <el-input v-model="form.number" autocomplete="off"></el-input>
                </el-form-item>
              </el-col>

              <el-col :span="8">
                <el-form-item :label="columns.begin_date.title" prop="begin_date">
                  <el-date-picker
                    type="date"
                    v-model="form.begin_date"
                    :format="date_format"
                    :value-format="date_format"
                  ></el-date-picker>
                </el-form-item>
              </el-col>

              <el-col :span="8">
                <states v-model="form.status_id" :state_id="form.status_id" size="small"></states>
              </el-col>

              <el-col :span="8">
                <clients v-model="form.client_id" :client_id="form.client_id" size="small"></clients>
              </el-col>

              <el-col :span="8">
                <el-form-item :label="columns.comment.title">
                  <el-input type="textarea" :rows="3" v-model="form.comment" autocomplete="off"></el-input>
                </el-form-item>
              </el-col>

              <el-col :span="24">
                <el-card class="box-card">
                  <el-row :gutter="20">
                    <el-col :span="8">
                      <el-form-item :label="columns.conclusion_date.title" prop="conclusion_date">
                        <!-- <el-date-picker
                          type="date"
                          v-model="form.conclusion_date"
                          :format="date_format"
                          :value-format="date_format"
                          :disabled="model.application ? true : false"
                        ></el-date-picker> -->
                        <el-date-picker
                          type="date"
                          v-model="form.conclusion_date"
                          :format="date_format"
                          :value-format="date_format"
                        ></el-date-picker>
                      </el-form-item>
                    </el-col>

                    <el-col :span="8">
                      <el-form-item :label="columns.termination_date.title" prop="termination_date">
                        <el-date-picker
                          type="date"
                          v-model="form.termination_date"
                          :format="date_format"
                          :value-format="date_format"
                        ></el-date-picker>
                      </el-form-item>
                    </el-col>
                  </el-row>
                </el-card>
              </el-col>
            </el-row>

            <el-row :gutter="20">
              <el-col :span="24">
                <el-card class="mt-2">
                  <div slot="header" class="clearfix">
                    <span class="h4">{{ $t('message.suspenses') }}</span>
                  </div>
                  <el-table size="medium" :data="[...old_items, ...contract_client_suspenses]" style="width: 100%" class="crm-el-table">
                      <template slot="empty">
                          <span></span>
                      </template>
                      <el-table-column :label="$t('message.date')">
                          <template slot-scope="item">
                            <template v-if="item.row.id">
                              {{ item.row.from_date }} {{ $t('message.by') }} {{ item.row.to_date }}
                            </template>
                            <template v-else>
                              <el-col :span="11">
                                <el-date-picker
                                  type="date"
                                  v-model="item.row.from_date"
                                  :format="date_format"
                                  :value-format="date_format"
                                  size="small"
                                  style="width: 100%;"
                                  placeholder=".. - .. - ...."
                                ></el-date-picker>
                              </el-col>
                              <el-col class="line" :span="2">{{ $t('message.by') }}</el-col>
                              <el-col :span="11">
                                <el-date-picker
                                  type="date"
                                  :format="date_format"
                                  :value-format="date_format"
                                  v-model="item.row.to_date"
                                  size="small"
                                  style="width: 100%;"
                                  placeholder=".. - .. - ...."
                                ></el-date-picker>
                              </el-col>
                            </template>
                          </template>
                      </el-table-column>
                      <el-table-column :label="$t('message.comment')">
                          <template slot-scope="item">
                            <template v-if="item.row.id">
                              {{ item.row.comment }}
                            </template>
                            <template v-else>
                              <el-input v-model="item.row.comment" size="small"></el-input>
                            </template>
                          </template>
                      </el-table-column>
                      <el-table-column :label="$t('message.delete')">
                          <template slot-scope="item">
                              <el-button @click="item.row.id ? deleteOldSuspense(item.row.id) : removeSuspense(item.row)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                          </template>
                      </el-table-column>
                  </el-table>
                  <el-button type="primary" round size="mini" @click="addSuspense()" class="mt-2">
                    <i class="el-icon-plus"></i>
                    {{ $t('message.add') }}
                  </el-button>
                </el-card>
              </el-col>
            </el-row>
          </el-form>
        </el-main>
    </div>
</template>
<script>
    import {mapGetters,mapActions} from 'vuex';
    import form from '@/utils/mixins/form';
    import contract from '@/utils/mixins/models/contract-client';
    import drawer from '@/utils/mixins/includes/drawer';

    export default {
        props:['contract'],
        mixins:[drawer,form,contract],
        methods:{
            ...mapActions({
                save: 'contractClients/update',
                edit: 'contractClients/show',
                deleteSuspense: 'contractClients/deleteSuspense',
            }),
            afterOpen(){
                this.form = this.getForm;
                this.contract_client_suspenses = [];
                this.load();
            },
            load(){
                if (!this.loading && this.contract) {
                    this.changeLoading(true);
                    this.edit(this.contract.id)
                    .then(res => {
                        this.form = this.getForm;
                        this.changeLoading(false);
                    })
                    .catch(err => {
                        this.$alert(err);
                        this.changeLoading(false)
                    })
                }
            },
            deleteOldSuspense(id){
                this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
                    confirmButtonText: this.$t('message.yes'),
                    cancelButtonText: this.$t('message.cancel'),
                    type: 'warning'
                }).then(() => {
                    this.deleteSuspense({suspense_id: id})
                        .then(res=> {
                            this.load();
                            this.listChanged();
                            this.$alert(res);
                        })
                        .catch(err => {
                          this.$alert(err)
                        })
                }).catch(() => {});
            },
        }
    }
</script>
