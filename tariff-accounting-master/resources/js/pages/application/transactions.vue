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
        <li>
          <router-link slot="title" :to="{ name: 'applications.parts', params: {id: $route.params.id} }">
            {{ $t("message.payment history") }}
          </router-link>
        </li>
        <li class="actionli">
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
          <el-col :span="24" class="mt-4">
            <el-tabs type="border-card">
              <el-tab-pane label=" История">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">{{ $t("message.add_data") }}</th>
                      <th scope="col">{{ $t("message.Amount") }}</th>
                      <th scope="col">{{ $t("message.paymentTypes") }}</th>
                      <th scope="col">{{ $t("message.state") }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(transaction, index) in transactions" :key="index + 'application_transactions'">
                      <td>{{ transaction.created_at }}</td>
                      <td>{{ transaction.amount | formatMoney }}</td>
                      <td>
                            <div v-if="transaction.payment_system == 'cash'" class="divr">{{ $t("message.Cash payment") }}</div>
                            <div v-else-if="transaction.payment_system == 'payme'" class="divr_payme">Payme</div>
                            <div v-else-if="transaction.payment_system == 'click'" class="divr_click">Click</div>
                            <div v-else-if="transaction.payment_system == 'paynet'" class="divr_paynet">Paynet</div>
                      </td>
                      <td>{{ transaction_states[transaction.state] }}</td>
                    </tr>
                    <!-- <tr>
                      <td>2020-10-13 05:50</td>
                      <td>17 000 UZS</td>
                      <td>
                        <div class="divr_payme">Payme</div>
                      </td>
                    </tr>
                    <tr>
                      <td>2020-11-13 05:50</td>
                      <td>17 000 UZS</td>
                      <td>
                        <div class="divr_click">Click</div>
                      </td>
                    </tr>
                    <tr>
                      <td>2020-01-18 05:50</td>
                      <td>18 000 UZS</td>
                      <td>
                        <div class="divr_paynet">Paynet</div>
                      </td>
                    </tr> -->
                  </tbody>
                </table>
              </el-tab-pane>
            </el-tabs>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";

export default {
  name: "ApplicationTransactions",
  data() {
    return {
        transactions: [],
        application_id: this.$route.params.id,
        loading: false,
    };
  },
  created() {
      this.loadModel()
  },
  computed:{
      ...mapGetters({
          transaction_states: 'transactions/transaction_states'
      }),
  },
  methods: {
    ...mapActions({
        showApplication: "applications/show",
        getApplicationTransactions: "applications/getTransactions",
    }),
    loadModel(){
        if (!this.loading && this.application_id) {
            this.changeLoading(true);
            this.getApplicationTransactions(this.application_id)
            .then(res => {
                this.transactions = res.data.result.data.transactions;
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

