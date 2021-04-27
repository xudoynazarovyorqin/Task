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
        <li>
          <router-link slot="title" :to="{ name: 'applications.transactions', params: {id: $route.params.id} }">
            {{ $t("message.transaction history") }}
          </router-link>
        </li>
        <li class="actionli">
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
              <el-tab-pane :label="$t('message.audits')">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">{{ $t("message.add_data") }}</th>
                      <th scope="col">{{ $t("message.user") }}</th>
                      <th scope="col">{{ $t("message.event") }}</th>
                      <th scope="col">{{ $t("message.ip_address") }}</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in audits" :key="item.id + 'audits' + index">
                      <td>{{ item.created_at }}</td>
                      <td>{{ item.username }}</td>
                      <td>
                        <template v-if="item.event == 'created'">
                            <div class="event_created">
                                {{ $t("message.Creation") }}
                            </div>
                        </template>
                        <template v-else-if="item.event == 'updated'">
                            <div class="event_updated">
                                {{ $t("message.Update") }}
                            </div>
                        </template>
                        <template v-else-if="item.event == 'deleted'">
                            <div class="event_deleted">
                                {{ $t("message.Removal") }}
                            </div>
                        </template>
                      </td>
                      <td>{{ item.ip_address }}</td>
                      <td>
                          <span @click="downloadChangesFunction(item.id)" style="cursor: pointer; color: #3490dc;">
                            <span class="el-icon-download"></span>
                          </span>
                      </td>
                    </tr>
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
  name: "ApplicationAudits",
  data() {
    return {
        audits: [],
        application_id: this.$route.params.id,
        loading: false,
    };
  },
  created() {
      this.loadModel()
  },
  computed:{
      ...mapGetters({

      }),
  },
  methods: {
    ...mapActions({
        getAudits: "applications/getAudits",
        downloadChanges: "audits/downloadChanges",
    }),
    loadModel(){
        if (!this.loading && this.application_id) {
            this.changeLoading(true);
            this.getAudits(this.application_id)
            .then(res => {
                this.audits = res.data.result.data.audits;
                this.changeLoading();
            })
            .catch(err => {
              this.changeLoading();
              this.$alert(err);
            })
        }
    },
    downloadChangesFunction(audit_id){
        this.downloadChanges(audit_id)
            .then(res => {
                const url = window.URL.createObjectURL(new Blob([res.data], {type:'application/json'}));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'changes.json');
                document.body.appendChild(link);
                link.click();
            })
            .catch(err => {
                this.$alert(err)
            })
    },
    changeLoading(argument) {
        this.loading = argument ? true : false;
    },
  }
};
</script>

