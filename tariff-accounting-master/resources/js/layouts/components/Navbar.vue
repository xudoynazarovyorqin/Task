<template>
  <div class="container-fluid no--gutter pl-0 pr-0">
    <header class="--header">
      <!-- <div class="--header-logo">
        <img src="/images/logo_light_max.png" height="30" />
      </div>-->
      <ul class="nav nav-pills ml-1" id="pills-tab" role="tablist">
        <li class="nav-item" @click="changeCurrentTab('')">
          <router-link
            :to="{name: 'home'}"
            class="nav-link text-center"
            :class="{'active' : this.current_tab == ''}"
          >
            <i class="flaticon-home"></i>
            {{ $t('message.main') }}
          </router-link>
        </li>
        <li class="nav-item">
          <a
            class="nav-link text-center"
            id="pills-sales-tab"
            data-toggle="pill"
            href="#pills-sales"
            role="tab"
            aria-controls="pills-sales"
            aria-selected="true"
            @click="changeCurrentTab('pills-sales')"
            :class="{'active' : this.current_tab == 'pills-sales'}"
          >
            <i class="flaticon-shop"></i>
            {{ $t('message.Client applications') }}
          </a>
        </li>
        <li class="nav-item" v-can-or="['payments.index','transactions.index','summary_report.index']">
          <a
            class="nav-link text-center"
            id="pills-cashiers-tab"
            data-toggle="pill"
            href="#pills-cashiers"
            role="tab"
            aria-controls="pills-cashiers"
            aria-selected="true"
            @click="changeCurrentTab('pills-cashiers')"
            :class="{'active' : this.current_tab == 'pills-cashiers'}"
          >
            <i class="flaticon-money"></i>
            {{ $t('message.cashbox') }}
          </a>
        </li>
        <li class="nav-item" v-can-or="['clients.index','contractClients.index']">
          <a
            class="nav-link text-center"
            id="pills-counterparties-tab"
            data-toggle="pill"
            href="#pills-counterparties"
            role="tab"
            aria-controls="pills-counterparties"
            aria-selected="true"
            @click="changeCurrentTab('pills-counterparties')"
            :class="{'active' : this.current_tab == 'pills-counterparties'}"
          >
            <i class="flaticon-staff"></i>
            {{ $t('message.agents') }}
          </a>
        </li>
        <li class="nav-item" v-can-or="['services.index','states.index','paymentTypes.index','districts.index', 'quarters.index']">
          <a
            class="nav-link text-center"
            id="pills-settings-tab"
            data-toggle="pill"
            href="#pills-settings"
            role="tab"
            aria-controls="pills-settings"
            aria-selected="false"
            @click="changeCurrentTab('pills-settings')"
            :class="{'active' : this.current_tab == 'pills-settings'}"
          >
            <i class="flaticon-settings"></i>
            {{ $t('message.settings') }}
          </a>
        </li>

        <li
          class="nav-item"
          v-can-or="['users.index','roles.index','permissions.index','audits.index','userAuthLogs.index']"
        >
          <a
            class="nav-link text-center"
            id="pills-usage-tab"
            data-toggle="pill"
            href="#pills-usage"
            role="tab"
            aria-controls="pills-usage"
            aria-selected="false"
            @click="changeCurrentTab('pills-usage')"
            :class="{'active' : this.current_tab == 'pills-usage'}"
          >
            <i class="flaticon-button"></i>
            HR
          </a>
        </li>
      </ul>
      <div class="dropdown">
        <div
          class="avatar--settings dropdown-toggle"
          id="dropdownMenuButton"
          data-toggle="dropdown"
          aria-haspopup="true"
          aria-expanded="false"
        >
          <div class="avatar--settings-descr">
            <p class="avatar--settings-name">{{ name }}</p>
            <span>{{ phone }}</span>
          </div>
        </div>
        <div
          class="dropdown-menu dropdown-menu-right"
          aria-labelledby="dropdownMenuButton"
          style="transform: translate3d(-58px, 43px, 0px);"
        >
          <router-link class="dropdown-item" :to="{name: 'settings'}">{{ $t('message.settings') }}</router-link>
          <a class="dropdown-item" href="#" @click="logout">{{ $t('message.logout') }}</a>
        </div>
      </div>
    </header>
    <div class="tab-content" id="pills-tabContent">
      <div
        class="tab-pane fade show"
        id="pills-sales"
        role="tabpanel"
        aria-labelledby="pills-sales-tab"
        :class="{'active' : this.current_tab == 'pills-sales'}"
      >
        <nav class="navbar navbar-expand navbar-light bg-white">
          <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
              <!-- <li>
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'saleReadyProducts.index'}"
                >Заявка</router-link>
              </li> -->
              <li v-can="'applications.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'applications.index'}"
                >{{ $t("message.Client applications") }}</router-link>
              </li>
            </ul>
          </div>
        </nav>
      </div>
      <div
        class="tab-pane fade show"
        id="pills-cashiers"
        role="tabpanel"
        aria-labelledby="pills-cashiers-tab"
        :class="{'active' : this.current_tab == 'pills-cashiers'}"
      >
        <nav class="navbar navbar-expand navbar-light bg-white">
          <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
              <li v-can="'payments.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'applicationParts.index'}"
                >{{ $t('message.payments') }}</router-link>
              </li>
              <li v-can="'transactions.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'transactions.index'}"
                >{{ $t('message.Transactions') }}</router-link>
              </li>
              <li v-can="'summary_report.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'summaryReport.index'}"
                >{{ $t('message.Summary report') }}</router-link>
              </li>
              <!-- <li>
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'transactionHistor.index'}"
                >{{ $t('message.transaction history') }}</router-link>
              </li> -->
            </ul>
          </div>
        </nav>
      </div>
      <div
        class="tab-pane fade show"
        id="pills-counterparties"
        role="tabpanel"
        aria-labelledby="pills-counterparties-tab"
        :class="{'active' : this.current_tab == 'pills-counterparties'}"
      >
        <nav class="navbar navbar-expand navbar-light bg-white">
          <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
              <li v-can="'clients.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'clients.index'}"
                >{{ $t('message.clients') }}</router-link>
              </li>
              <li v-can="'contractClients.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'contractClients.index'}"
                >{{ $t('message.contract_clients') }}</router-link>
              </li>
            </ul>
          </div>
        </nav>
      </div>

      <div
        class="tab-pane fade show"
        id="pills-settings"
        role="tabpanel"
        aria-labelledby="pills-settings-tab"
        :class="{'active' : this.current_tab == 'pills-settings'}"
      >
        <nav class="navbar navbar-expand navbar-light bg-white">
          <div class="collapse navbar-collapse">
            <ul class="navbar-nav">
              <li v-can="'states.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'states.index'}"
                >{{ $t('message.states') }}</router-link>
              </li>
              <li v-can="'paymentTypes.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'paymentTypes.index'}"
                >{{ $t('message.paymentTypes') }}</router-link>
              </li>
              <li v-can="'services.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'services.index'}"
                >{{ $t("message.services") }}</router-link>
              </li>
              <li v-can="'districts.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'districts.index'}"
                >{{ $t("message.districts") }}</router-link>
              </li>
              <li v-can="'quarters.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'quarters.index'}"
                >{{ $t("message.quarters") }}</router-link>
              </li>
            </ul>
          </div>
        </nav>
      </div>
      <div
        class="tab-pane fade show"
        id="pills-usage"
        role="tabpanel"
        aria-labelledby="pills-usage-tab"
        :class="{'active' : this.current_tab == 'pills-usage'}"
      >
        <nav class="navbar navbar-expand navbar-light bg-white">
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li v-can="'users.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'users.index'}"
                >{{ $t('message.users') }}</router-link>
              </li>
              <li v-can="'roles.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'roles.index'}"
                >{{ $t('message.roles') }}</router-link>
              </li>
              <li v-can="'permissions.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'permissions.index'}"
                >{{ $t('message.permissions') }}</router-link>
              </li>
              <li v-can="'audits.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'audits.index'}"
                >{{ $t('message.audits') }}</router-link>
              </li>
              <li v-can="'userAuthLogs.index'">
                <router-link
                  class="nav-link"
                  active-class="active--nav-tab-item"
                  :to="{name: 'userAuthLogs.index'}"
                >{{ $t('message.userAuthLogs') }}</router-link>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
export default {
  data() {
    return {
      refreshInterval: null,
      withResource: parseInt(process.env.MIX_WITH_RESOURCE_COMPONENTS)
    };
  },
  computed: {
    ...mapGetters({
      name: "auth/name",
      phone: "auth/phone",
      expires_in: "auth/expires_in",
      current_tab: "current_tab",
      warehouses: "warehouses/inventory"
    }),
    wrs: function() {
      return _.filter(this.warehouses, "show_in_nav");
    }
  },
  mounted() {
    //if (!_.size(this.warehouses)) this.updateWarehouseInventory();
  },
  methods: {
    ...mapActions({
      refresh: "auth/refresh",
      updateWarehouseInventory: "warehouses/inventory"
    }),
    async logout() {
      await this.$store.dispatch("auth/logout");
      this.$router.push(`/login?redirect=${this.$route.fullPath}`);
    },
    changeCurrentTab(selectedNav) {
      this.$store.commit("CHANGE_CURRENT_TAB", selectedNav);
    }
  },
  beforeDestroy() {}
};
</script>
<style lang="scss" >
.navbar-light .navbar-nav .nav-link {
  color: black;
}
.--header {
  .notification {
    .el-icon-message-solid {
      color: #ffffff;
      font-size: 1.5rem;
    }
  }
}

.nav li.nav-item {
  width: 9% !important;
}

.tab-content nav li>a {
    font-size: 13px !important;
    transition: all .4s;
    font-weight: 600;
}
</style>
