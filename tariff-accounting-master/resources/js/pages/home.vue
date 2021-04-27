<template>
  <div class="container-fluid">
    <el-row :gutter="20">
      <el-col :md="6" :xl="6">
        <div class="itme_blocks_pages">
          <div class="itme_blocks_pages_top">
            <img src="/images/logos/download.svg" class="payme_icon" alt />
            <div class="info_topright">{{ transaction_counts.count_payme }} та</div>
          </div>
          <div class="itme_blocks_pages_botton">
            <div class="itme_blocks_pages_botton_summa">{{ transaction_amounts.amount_payme | formatMoney }}</div>
            <div class="itme_blocks_pages_botton_title">{{ $t("message.Payme") }}</div>
          </div>
        </div>
      </el-col>
      <!-- end el-col -->

      <el-col :md="6" :xl="6">
        <div class="itme_blocks_pages">
          <div class="itme_blocks_pages_top">
            <img src="/images/logos/logo.png" class="payme_icon" alt />
            <div class="info_topright">{{ transaction_counts.count_click }} та</div>
          </div>
          <div class="itme_blocks_pages_botton">
            <div class="itme_blocks_pages_botton_summa">{{ transaction_amounts.amount_click | formatMoney }}</div>
            <div class="itme_blocks_pages_botton_title">{{ $t("message.Click") }}</div>
          </div>
        </div>
      </el-col>
      <!-- end el-col -->

      <el-col :md="6" :xl="6">
        <div class="itme_blocks_pages">
          <div class="itme_blocks_pages_top">
            <img src="/images/logos/logo-paynet.png" style="width: 205px;" class="payme_icon" alt />
            <div class="info_topright">{{ transaction_counts.count_paynet }} та</div>
          </div>
          <div class="itme_blocks_pages_botton">
            <div class="itme_blocks_pages_botton_summa">{{ transaction_amounts.amount_paynet | formatMoney }}</div>
            <div class="itme_blocks_pages_botton_title">{{ $t("message.Paynet") }}</div>
          </div>
        </div>
      </el-col>
      <!-- end el-col -->

      <el-col :md="6" :xl="6">
        <div class="itme_blocks_pages">
          <div class="itme_blocks_pages_top">
            <i class="iconstyle iconitme_blocks_pages4"></i>
            <div class="info_topright">{{ transaction_counts.count_cash }} та</div>
          </div>
          <div class="itme_blocks_pages_botton">
            <div class="itme_blocks_pages_botton_summa">{{ transaction_amounts.amount_cash | formatMoney }}</div>
            <div class="itme_blocks_pages_botton_title">{{ $t("message.Cash") }}</div>
          </div>
        </div>
      </el-col>
      <!-- end el-col -->
    </el-row>
    <!-- end el-row -->

    <application-chart></application-chart>
    <el-row :gutter="20" class="mb-4">
      <el-col :span="8">
        <div class="naves_chart bg-white shadow-sm">
          <transaction-chart></transaction-chart>
        </div>
      </el-col>
      <el-col :span="16">
        <div class="dome_blocks collapse_scrol row__progress mb-4">
          <el-collapse v-model="activeNames" class="pb-0 car_box my_collapse">
            <el-collapse-item name="1">
              <template slot="title" class="blocks_title">
                <div class="blocks_title">{{ $t('message.audits')}}</div>
              </template>
              <audits></audits>
            </el-collapse-item>
          </el-collapse>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import applicationChart from "@/pages/application/components/crm-chart";
import transactionChart from "@/pages/transaction/components/crm-chart";
import audits from "@/pages/audit/components/crm-audit";
import { mapGetters, mapActions } from "vuex";
export default {
  data() {
    return {
      activeNames: ["1"],
      transaction_amounts: {},
      transaction_counts: {},
    };
  },
    computed: {
            ...mapGetters({

            }),
        },
    mounted() {
        this.loadTransactionAmountsAndCounts();
    },
  components: {
    "application-chart": applicationChart,
    "transaction-chart": transactionChart,
    audits
  },
  methods: {
      ...mapActions({
            getAmountsAndCounts: "transactions/getAmountsAndCounts",
        }),
        loadTransactionAmountsAndCounts() {
            this.getAmountsAndCounts()
                .then(res => {
                    this.transaction_amounts = res.data.amounts;
                    this.transaction_counts = res.data.counts;
                })
                .catch(err => {
                    this.$alert(err)
                });
        },
  }
};
</script>

<style lang="scss">
.car_box {
  background: #fff;
  border-radius: 0.5rem;
  box-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
  -webkit-transition: all 0.3s ease-in-out;
}

.style_elRow {
  border-bottom: 1px solid #f1f1f1;
  padding: 15px 10px;
  margin: 0px !important;
  display: flex;
  align-items: center;
  margin-bottom: 20px !important;
}
.mytable_row {
  padding: 0px 10px;
}
.mytable_row_top {
  font-weight: 600;
  font-size: 16px;
}
.mytable_row_content {
  padding: 10px 0px;
  border-bottom: 1px solid #f1f1f1;
}
.mytable_row_content .el-col {
  padding-left: 10px !important;
  padding-right: 10px !important;
}
.bg_add {
  background-color: #28d09420 !important;
}
.bg_delet {
  background-color: #ff496120 !important;
}

.bg_obdt {
  background-color: #ff914920 !important;
}
/*  style  collapse*/
.my_collapse {
  overflow: hidden;
  .el-collapse-item__header {
    padding: 27px 20px;
    border-bottom: 1px solid #f2f2f2;
  }
  .mytable_row {
    margin-top: 10px;
  }
}
/* END  style  collapse*/

/* style my pragres */
.el_progress_my {
  padding: 7px 0px;
  border-bottom: 1px solid #f1f1f1;
}
.el_progress_Data {
  display: flex;
  justify-content: space-between;
  //   margin-bottom: 6px;
}
.el_progress_sum {
  display: flex;
}
.el_progress_Data_info {
  font-weight: 600;
}
.el_progress_sum_ii {
  margin-left: 20px;
  font-size: 12px;
}
.text_ii {
  color: #a1a1a1;
}
.square_I {
  margin: 0px 5px;
  font-size: 12px;
  color: #848383;
}
.square_I span {
  width: 15px;
  height: 15px;
  margin-right: 5px;
  border-radius: 3px;
}
.right__bar div {
  font-size: 20px;
}
.right__bar span {
  font-size: 15px;
  color: #848383;
  margin-bottom: 10px;
  display: block;
  margin-top: -7px;
}

.right_bart_sec_info {
  text-align: left;
  font-weight: 600;
  font-size: 16px;
}
.height350 {
  height: 350px;
}
.ov_scrol {
  overflow-y: scroll;
}
/* width */
.ov_scrol::-webkit-scrollbar {
  width: 7px;
}

/* Track */
.ov_scrol::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px #fff;
  border-radius: 10px;
}

/* Handle */
.ov_scrol::-webkit-scrollbar-thumb {
  background: rgb(204, 204, 204);
  border-radius: 10px;
}

.dd_flex {
  display: flex;
  justify-content: center;
  /* align-items: center; */
  flex-direction: column;
}
.el_progress_my {
  padding: 7px 0px;
  border-bottom: 1px solid #f1f1f1;
}
.el-radio-button__orig-radio:checked + .el-radio-button__inner {
  color: #fff;
  background-color: #67c23a;
  border-color: #67c23a;
  -webkit-box-shadow: -1px 0 0 0 #67c23a;
  box-shadow: -1px 0 0 0 #67c23a;
}
/* end style my pragres */

/*  style chrt */
.bar16 .chartjs-render-monitor {
  height: 355px !important;
}
.blocks_title {
  font-size: 14px;
  font-weight: 600;
}

@media (min-width: 1700px) {
  .bar16 .chartjs-render-monitor {
    height: 400px !important;
  }
  .bar16 .chart_H.height350 {
    height: 445px !important;
  }
  .bar16 .blocks_title {
    font-size: 0.875rem !important;
    font-weight: 600;
  }
}
@media (min-width: 2500px) {
  .chartjs-render-monitor {
    height: auto !important;
  }
}
/* end  style chrt */

.home__domen {
  width: 100%;
  border-top: 1px solid #f2f2f2;
  justify-content: space-around;
  align-items: center;
}
.dome_blocks .el-collapse-item__content {
  padding-bottom: 0;
}
.info_dome {
  width: 50%;
  border-right: 1px solid rgb(242, 242, 242);
  text-align: center;
}
.info_dome p {
  margin: 0px;
  margin-bottom: -5px;
  margin-top: 10px;
  font-size: 15px;
}
.info_dome span {
  font-size: 1.875rem !important;
  font-weight: 600 !important;
  margin: 0px;
  margin-bottom: 10px;
  color: #626262;
}
.itme_blocks_pages {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  background-color: #fff;
  margin-bottom: 20px;
  padding: 15px;
  border-radius: 10px;
  min-height: 200px;
}

.itme_blocks_pages_botton {
  .itme_blocks_pages_botton_summa {
    line-height: 2rem;
    font-weight: 700;
    color: #000;
    font-size: 22px;
  }

  .itme_blocks_pages_botton_title {
    color: #718096;
    font-size: 20px;
  }
}

.itme_blocks_pages_top {
  display: flex;
  justify-content: space-between;
  //   align-items: center;
}

.info_topright {
  width: 60px;
  height: 30px;
  border-radius: 20px;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #ffffff;
  background-color: #91c714;

  i {
    font-size: 15px;
    font-weight: 700;
  }
}

.iconstyle {
  width: 40px;
  height: 40px;
  transition: 0.7s;
  display: inline-block;
}
.payme_icon {
  width: 150px;
  padding-top: 40px;
}
.iconstyle {
  width: 60px;
  height: 60px;
  transition: 0.7s;
  margin-top: 30px;
  display: inline-block;
}

.iconitme_blocks_pages4 {
  -webkit-mask: url(/images/logos/money.svg);
  mask: url(/images/logos/money.svg);
  background-color: #92c81982;
  -webkit-mask-size: cover;
  mask-size: cover;
}
.ClassHight {
  min-height: 460px;
}
.naves_chart {
  border-radius: 10px;
  padding: 10px 10px 30px 10px;
}
</style>

