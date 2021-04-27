<template>
  <div>
    <el-breadcrumb separator="/" class="mb-3">
      <el-breadcrumb-item :to="{ name: 'applications.index' }">{{ $t("message.applications") }}</el-breadcrumb-item>
      <el-breadcrumb-item>{{ $t("message.show_application") }} ({{ $t('message.n') }} {{ application_id }})</el-breadcrumb-item>
    </el-breadcrumb>

    <div class="user_info">
      <ul class="user_info_top_menu">
        <li class="actionli">
          <router-link slot="title" :to="{ name: 'applications.show', params: {id: application_id} }">
            {{ $t("message.application information") }}
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'applications.parts', params: {id: application_id} }">
            {{ $t("message.payment history") }}
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'applications.transactions', params: {id: application_id} }">
            {{ $t("message.transaction history") }}
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'applications.audits', params: {id: application_id} }">
            {{ $t("message.audits") }}
          </router-link>
        </li>
        <!-- <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList5.index', params: {id: application_id} }">
            {{ $t("message.SMS Alerts") }}
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList6.index', params: {id: application_id} }">
            {{ $t("message.printing documents") }}
          </router-link>
        </li> -->
      </ul>
      <div class="p-4" v-loading="loading">
        <el-row :gutter="20">
          <el-col :span="12">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span class="font_weight">
                  <i class="el-icon-shopping-cart-2 mr-2"></i> {{ $t("message.client") }}
                </span>
              </div>
              <div class="text item">
                <div class="blockas_eliment">
                  <span class="font_weight">{{ $t("message.client_name") }}</span>
                  <span>{{ (model.client) ? model.client.name : '' }}</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">{{ $t("message.phone") }}</span>
                  <span>{{ (model.client) ? model.client.phone : '' }}</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">{{ columns.district_id.title }}</span>
                  <span>{{ (model.district) ? model.district.name : '' }}</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">{{ columns.quarter_id.title }}</span>
                  <span>{{ (model.quarter) ? model.quarter.name : '' }}</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">{{ columns.object_street.title }}</span>
                  <span>{{ model.object_street }}</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">{{ columns.object_home.title }}</span>
                  <span>{{ model.object_home }}</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">{{ columns.object_corps.title }}</span>
                  <span>{{ model.object_corps }}</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">{{ columns.object_flat.title }}</span>
                  <span>{{ model.object_flat }}</span>
                </div>
              </div>
            </el-card>
          </el-col>
          <el-col :span="12">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span class="font_weight">
                  <i class="el-icon-shopping-cart-2 mr-2"></i> Продажа
                </span>
              </div>
              <div class="text item">
                <div class="blockas_eliment">
                  <span class="font_weight">{{ columns.console_number.title }}</span>
                  <span>{{ model.console_number }}</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">{{ columns.status_id.title }}</span>
                  <span>{{ (model.status) ? model.status.state : '' }}</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">{{ columns.created_at.title }}</span>
                  <span>{{ model.created_at }}</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">{{ columns.contract_client_id.title }}</span>
                  <span>{{ (model.contract_client) ? ($t('message.n') + ' ' + model.contract_client.number + ' ' + $t('message.from') + ' ' + model.contract_client.begin_date) : '' }}</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">{{ $t("message.conclusion_date") }}</span>
                  <span>{{ (model.contract_client) ? model.contract_client.conclusion_date : '' }}</span>
                </div>
                <div class="blockas_eliment">
                  <span class="font_weight">{{ $t("message.termination_date") }}</span>
                  <span>{{ (model.contract_client) ? model.contract_client.termination_date : '' }}</span>
                </div>
              </div>
            </el-card>
          </el-col>

          <el-col :span="24" class="mt-4">
            <el-card class="box-card">
              <div slot="header" class="clearfix">
                <span class="font_weight">
                  <i class="el-icon-shopping-cart-2 mr-2"></i> {{ model.service }}
                </span>
              </div>
              <div class="text item">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">{{ $t("message.name") }}</th>
                      <th scope="col">{{ $t("message.price") }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in application_services" :key="item.id + 'application_services' + index">
                      <td>{{ (item.service) ? item.service.name : '' }}</td>
                      <td>{{ item.price | formatMoney(2) }}</td>
                    </tr>
                    <tr>
                      <th>{{ $t("message.total") }}</th>
                      <th>{{ model.amount | formatMoney(2) }}</th>
                    </tr>
                  </tbody>
                </table>
              </div>
            </el-card>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";

export default {
  name: "ApplicationShow",
  data() {
    return {
      application_id: this.$route.params.id,
      loading: false,
    };
  },
  created() {
      this.loadModel()
  },
  computed:{
      ...mapGetters({
          model: 'applications/model',
          columns: 'applications/columns',
          application_services: 'applications/application_services',
      }),
  },
  methods: {
    ...mapActions({
        showApplication: "applications/show",
    }),
    loadModel(){
        if (!this.loading && this.application_id) {
            this.changeLoading(true);
            this.showApplication(this.application_id)
            .then(res => {
              this.changeLoading();
            })
            .catch(err => {
              this.changeLoading();
              this.$alert(err);
            })
        }
    },
    changeLoading(argument) {
        this.loading = argument ? true : false;
    },
  }
};
</script>
