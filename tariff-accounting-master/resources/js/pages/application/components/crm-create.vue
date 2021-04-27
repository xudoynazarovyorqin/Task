<template>
  <el-col :span="24">
    <header id="el-drawer__title" class="el-drawer__header">
      <span>{{ $t('message.New application') }}</span>
      <el-button v-can="['applications.create']" type="success" size="small" class="mr-1" :loading="waiting" @click="submit(false)"> {{ $t('message.save') }}</el-button>
      <el-button v-can="['applications.create']" type="primary" size="small" class="mr-1" :loading="waiting" @click="submit(true)"> {{ $t('message.save_and_exit') }}</el-button>
      <el-button
        type="warning"
        @click="close()"
        icon="el-icon-close"
        size="small"
      >{{ $t('message.close') }}</el-button>
    </header>
    <el-main>
      <el-form ref="form" :model="form" :rules="rules" class="style__label" label-position="right">
        <el-card class="box-card pb-1 crm-card-pt-1 mb-4">
          <el-row>
            <el-col>
              <el-form-item size="small" prop="number" class="d-inline-flex crm-document-number">
                <span class="document-title">{{ form.number }}</span>
                <template slot="label">
                  <span
                    class="document-title"
                  >{{ $t('message.application') }} {{ $t('message.n') }}</span>
                </template>
              </el-form-item>
              <el-form-item label-width="20px" size="small" prop="datetime" class="d-inline-flex">
                <el-date-picker
                  prefix-icon="el-icon-date"
                  v-model="form.datetime"
                  type="datetime"
                  :format="date_time_format"
                  :value-format="date_time_format"
                ></el-date-picker>
                <template slot="label">
                  <span class="document-title">{{ $t('message.from') | lowerFirst }}</span>
                </template>
              </el-form-item>
              <span class="el-dropdown-link float-right p-4">
                <span class="text-secondary">{{ $t('message.owner') }}:</span>
                <span>{{ auth_name }}</span>
              </span>
            </el-col>
            <el-col :span="8">
              <clients
                v-model="form.client_id"
                :client_id="form.client_id"
                :label="$t('message.client')"
              ></clients>
            </el-col>
            <el-col :span="8">
              <contracts
                v-model="form.contract_client_id"
                :client_id="form.client_id"
                :contract_id="form.contract_client_id"
                :is_with_add="true"
                prop="contract_client_id"
              ></contracts>
            </el-col>
            <el-col :span="8">
              <states v-model="form.status_id" :state_id="form.status_id" prop="status_id"></states>
            </el-col>
            <el-col :span="8">
              <el-form-item :label="columns.console_number.title" prop="console_number">
                <el-input v-model="form.console_number" size="small"></el-input>
              </el-form-item>
            </el-col>
          </el-row>
        </el-card>

        <el-row :gutter="20">
          <el-col :span="12">
            <el-card class="box-card clent_calabs">
              <el-collapse v-model="activeNames">
                <el-collapse-item :title="$t('message.object')" name="1">
                  <el-form-item class="mb-1" :label="columns.object_name.title">
                    <el-input v-model="form.object_name" size="small"></el-input>
                  </el-form-item>

                  <districts v-model="form.district_id" :district_id="form.district_id" :label="$t('message.district')"></districts>

                  <quarters v-model="form.quarter_id" :quarter_id="form.quarter_id" :label="$t('message.quarter')"></quarters>

                  <el-row :gutter="20">
                    <el-col :span="12">
                      <el-form-item class="mb-1" :label="columns.object_street.title">
                        <el-input v-model="form.object_street" size="small"></el-input>
                      </el-form-item>
                    </el-col>
                    <el-col :span="12">
                      <el-form-item class="mb-1" :label="columns.object_home.title">
                        <el-input v-model="form.object_home" size="small"></el-input>
                      </el-form-item>
                    </el-col>

                    <el-col :span="12">
                      <el-form-item class="mb-1" :label="columns.object_corps.title">
                        <el-input v-model="form.object_corps" size="small"></el-input>
                      </el-form-item>
                    </el-col>
                    <el-col :span="12">
                      <el-form-item class="mb-1" :label="columns.object_flat.title">
                        <el-input v-model="form.object_flat" size="small"></el-input>
                      </el-form-item>
                    </el-col>
                  </el-row>
                </el-collapse-item>
              </el-collapse>
            </el-card>
          </el-col>
        </el-row>
      </el-form>

      <el-col :span="24" class="mt-2">
        <el-card class="box-card pb-3">
          <el-table
            size="medium"
            :data="application_services"
            style="width: 100%"
            class="crm-el-table"
          >
            <template slot="empty">
              <span></span>
            </template>
            <el-table-column :label="$t('message.name')">
              <template slot-scope="item">
                <b>{{ (item.row.service ? item.row.service.name : '') | truncate }}</b>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.selling_price')">
              <template slot-scope="item">
                <amount v-model="item.row.price" :old="item.row.price" size="small"></amount>
              </template>
            </el-table-column>
            <el-table-column :label="$t('message.delete')">
              <template slot-scope="item">
                <el-button
                  @click="removeService(item.row)"
                  type="danger"
                  icon="el-icon-delete"
                  size="mini"
                  circle
                ></el-button>
              </template>
            </el-table-column>
          </el-table>
          <el-col :span="12" class="mt-1">
            <services @append="appendService" :plc="$t('message.services')"></services>
          </el-col>
          <el-col :span="6" :offset="2" class="p-2">
            <h5 class="float-left font-weight-bold">{{ $t('message.total') }}:</h5>
            <h5 class="float-right font-weight-bold">{{ totalAmount | formatMoney(2) }}</h5>
          </el-col>
        </el-card>
      </el-col>
    </el-main>
  </el-col>
</template>
<script>
import application from "@/utils/mixins/models/application";
import form from '@/utils/mixins/form';
import drawer from "@/utils/mixins/includes/drawer";
import { mapGetters, mapActions } from "vuex";

export default {
  mixins: [application, form, drawer],
  data() {
    return {
      activeNames: ["1"],
      is_edit: false,
    };
  },
  computed: {
      ...mapGetters({
          services: 'services/inventory',
          clients: 'clients/inventory'
      }),
  },

    watch: {
        'form.client_id': {
                handler: function(new_value, old_value) {
                    if( new_value ) {
                        this.loadClientObjectData()
                    }
                },
                deep: true
            },
    },

  methods: {
    ...mapActions({
      save: "applications/store",
      getLastId: "applications/getLastId",
      loadServiceInventory: "services/inventory",
    }),
    afterOpen() {
        if (_.isFunction(this.empty)) {
            this.empty();
        }
      this.form = this.getForm;
      this.application_services = [];
      if (!this.last_id) {
        this.getLastId().then(res => {
          this.form.number = res.last_id;
        });
      } else {
        this.form.number = this.last_id;
      }

      if (this.services && this.services.length === 0) {
        this.loadServiceInventory()
        .then(res => {
          if( this.services.length ) {
            this.appendService(this.services[0])
          }
        })
        .catch(err => {
          this.$alert(err);
        })
      }
      else if( this.services && this.services.length ) {
        this.appendService(this.services[0])
      }
    }
  },
};
</script>
