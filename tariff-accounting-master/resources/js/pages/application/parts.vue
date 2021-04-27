<template>
  <div>
    <el-breadcrumb separator="/" class="mb-3">
      <el-breadcrumb-item :to="{ name: 'applications.index' }">{{ $t("message.applications") }}</el-breadcrumb-item>
      <el-breadcrumb-item>{{ $t("message.show_application") }} ({{ $t('message.n') }} {{ application_id }})</el-breadcrumb-item>
    </el-breadcrumb>

    <div class="user_info">
      <ul class="user_info_top_menu">
        <li>
          <router-link slot="title" :to="{ name: 'applications.show', params: {id: $route.params.id} }">
            {{ $t("message.application information") }}
          </router-link>
        </li>
        <li class="actionli">
          <router-link slot="title" :to="{ name: 'applications.parts', params: {id: $route.params.id} }">
            {{ $t("message.payment history") }}
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'applications.transactions', params: {id: $route.params.id} }">
            {{ $t("message.transaction history") }}
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'applications.audits', params: {id: $route.params.id} }">
            {{ $t("message.audits") }}
          </router-link>
        </li>
        <!-- <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList5.index', params: {id: $route.params.id} }">
            {{ $t("message.SMS Alerts") }}
          </router-link>
        </li>
        <li>
          <router-link slot="title" :to="{ name: 'saleReadyProductList6.index', params: {id: $route.params.id} }">
            {{ $t("message.printing documents") }}
          </router-link>
        </li> -->
      </ul>
      <div class="p-4" v-loading="loading">
        <el-row :gutter="20">
          <el-col :span="24" class="mt-2">
            <el-collapse v-model="activeNames" accordion>
              <el-collapse-item name="1">
                <template slot="title">
                  <i class="el-icon-s-order"></i>
                  <span class="font_weight">{{ application_services_name }}</span>
                  <!-- <span>12 месячные</span> -->
                </template>
                <div>
                  <table class="table table-bordered">
                    <thead>
                      <tr class="text-center bg_color_table">
                        <th scope="col">{{ $t("message.service") }}</th>
                        <th scope="col">{{ $t("message.Start date") }}</th>
                        <th scope="col">{{ $t("message.Monthly amount") }}</th>
                        <th scope="col">{{ $t("message.total_amount") }}</th>
                        <th scope="col">{{ $t("message.Total payment") }}</th>
                        <th scope="col">{{ $t("message.not_paid") }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr class="text-center bg_color_table">
                        <td>{{ application_services_name }}</td>
                        <td>{{ (model.contract_client) ? model.contract_client.conclusion_date : '' }}</td>
                        <td>{{ model.amount | formatMoney }}</td>
                        <td>{{ getTotalAmount | formatMoney }}</td>
                        <td>{{ getTotalPaid | formatMoney }}</td>
                        <td>{{ getTotalAmount - getTotalPaid | formatMoney }}</td>
                      </tr>
                    </tbody>
                  </table>

                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">{{ $t("message.What month") }}</th>
                        <th scope="col">{{ $t("message.total_amount") }}</th>
                        <th scope="col">{{ $t("message.Paid amount") }}</th>
                        <th scope="col">{{ $t("message.Unpaid amount") }}</th>
                        <th scope="col">{{ $t("message.state") }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(part, index) in parts" :key="index + 'application_parts'">
                          <template v-if="part.status == 'active'">
                            <td>{{ part.start_date }} &mdash; {{ part.stop_date }}</td>
                            <td>{{ part.amount | formatMoney }}</td>
                            <td>
                                <span class="act_color">{{ part.paid | formatMoney }}</span>
                            </td>
                            <td>{{ (part.amount - part.paid) | formatMoney }}</td>
                            <td>
                                <template v-if="part.amount - part.paid > 0">
                                    <span class="act_warning_color">{{ $t("message.not_paid") }}</span>
                                </template>
                                <template v-else>
                                    <span class="act_color">{{ $t("message.paid") }}</span>
                                </template>
                            </td>
                          </template>
                          <template v-else-if="part.status == 'inactive'">
                            <td>{{ part.start_date }} &mdash; {{ part.stop_date }}</td>
                            <td colspan="3"></td>
                            <td>
                                <span class="act_danger_color">{{ $t("message.suspense") }}</span>
                            </td>
                          </template>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </el-collapse-item>
            </el-collapse>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
  name: "ApplicationPayments",
  data() {
    return {
        activeNames: ['1'],
        parts: [],
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
        getTotalAmount: function() {
            return _.sumBy([...this.parts], function(o) {
                return +o.amount;
            })
        },
        getTotalPaid: function() {
            return _.sumBy([...this.parts], function(o) {
                return +o.paid;
            })
        },

        application_services_name: function() {
            if( _.size(this.application_services) > 1 ) {
                let service_name = '';
                this.application_services.forEach(element => {
                    service_name += (element.service) ? element.service.name + ', ' : '';
                });
                return service_name;
            }
            else if(_.size(this.application_services) == 1 ) {
                return (this.application_services[0] && this.application_services[0].service) ? this.application_services[0].service.name : '';
            }
            else {
                return '';
            }
        },
  },
  methods: {
    ...mapActions({
        showApplication: "applications/show",
        getApplicationParts: "applications/getParts",
    }),
    loadModel(){
        if (!this.loading && this.application_id) {
            // model Load
            this.changeLoading(true);
            this.showApplication(this.application_id)
            .then(res => {
              this.changeLoading();
            })
            .catch(err => {
              this.changeLoading();
              this.$alert(err);
            })

            // parts Load
            this.changeLoading(true);
            this.getApplicationParts(this.application_id)
            .then(res => {
                this.parts = res.data.result.data.parts;
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


