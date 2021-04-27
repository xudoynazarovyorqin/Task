<template>
  <div>
    <header id="el-drawer__title" class="el-drawer__header">
      <span>{{ $t('message.new') }} {{ $t('message.contract') | lowerFirst }}</span>
      <el-button
        v-can="['contractClients.create']"
        type="success"
        size="small"
        class="mr-1"
        :loading="waiting"
        @click="submit(false)"
      >{{ $t('message.save') }}</el-button>
      <el-button
        v-can="['contractClients.create']"
        type="primary"
        size="small"
        class="mr-1"
        :loading="waiting"
        @click="submit(true)"
      >{{ $t('message.save_and_exit') }}</el-button>
      <el-button
        type="warning"
        @click="close()"
        icon="el-icon-close"
        size="small"
      >{{ $t('message.close') }}</el-button>
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
              <el-table
                size="medium"
                :data="contract_client_suspenses"
                style="width: 100%"
                class="crm-el-table"
              >
                <template slot="empty">
                  <span></span>
                </template>
                <el-table-column :label="$t('message.date')">
                  <template slot-scope="item">
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
                    <el-col class="line mt-1" :span="2">{{ $t('message.by') }}</el-col>
                    <el-col :span="11">
                      <el-date-picker
                        type="date"
                        v-model="item.row.to_date"
                        :format="date_format"
                        :value-format="date_format"
                        size="small"
                        style="width: 100%;"
                        placeholder=".. - .. - ...."
                      ></el-date-picker>
                    </el-col>
                  </template>
                </el-table-column>
                <el-table-column :label="$t('message.comment')">
                  <template slot-scope="item">
                    <el-input v-model="item.row.comment" size="small"></el-input>
                  </template>
                </el-table-column>
                <el-table-column :label="$t('message.delete')" width="80">
                  <template slot-scope="item">
                    <el-button
                      @click="removeSuspense(item.row)"
                      type="danger"
                      icon="el-icon-delete"
                      size="mini"
                      circle
                    ></el-button>
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
import { mapGetters, mapActions } from "vuex";
import form from "@/utils/mixins/form";
import contract from "@/utils/mixins/models/contract-client";
import drawer from "@/utils/mixins/includes/drawer";

export default {
  mixins: [drawer, form, contract],
  data() {
    return {
      checked: true
    };
  },
  methods: {
    ...mapActions({
      save: "contractClients/store"
    }),
    afterOpen() {
      this.form = this.getForm;
      this.contract_client_suspenses = [];
    }
  }
};
</script>
