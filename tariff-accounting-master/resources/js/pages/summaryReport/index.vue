<template ref="ApplicationList">
  <div class="row table-sm mr-0 ml-0 p-0">
    <div class="crm-content-header d-flex w-100 mb-2">
      <div class="crm-content-header-left d-flex w-50">
        <div class="crm-content-header-left-item mr-3" style="width:400px">
          <h5 class="d-inline mr-2 font-weight-bold">Хонадонлар дебиторлик рўйхати</h5>
          <crm-refresh @c-click="fetchData()"></crm-refresh>
        </div>
        <div class="crm-content-header-left-item">
          <el-date-picker
            v-model="filter.from_date"
            type="date"
            :format="date_format"
            :value-format="date_format"
            size="mini"
            :placeholder="$t('message.from')"
          ></el-date-picker>
        </div>
        <div class="crm-content-header-left-item">
          <el-date-picker
            v-model="filter.to_date"
            type="date"
            :format="date_format"
            :value-format="date_format"
            size="mini"
            :placeholder="$t('message.to')"
          ></el-date-picker>
        </div>
      </div>
      <div class="crm-content-header-right d-flex w-50 justify-content-end">
        <div class="crm-content-header-right-item">
          <export-excel
            :data="excel_data"
            :fields="excel_fields"
            @fetch="controlExcelData()"
            worksheet="Report"
            name="Report"
          >
            <el-button size="mini">
              <i class="el-icon-document-delete"></i>
              {{ $t('message.download_excel') }}
            </el-button>
          </export-excel>
        </div>
        <!-- <div class="crm-content-header-right-item">
          <crm-column-settings :columns="columns" @c-change="updateColumn"></crm-column-settings>
        </div> -->
      </div>
    </div>

    <table
      class="table table-bordered table-hover"
      v-loading="loadingData"
      :element-loading-text="$t('message.loading')"
      element-loading-spinner="el-icon-loading"
    >
      <thead>
        <tr>
            <th>{{ $t("message.n") }}</th>
            <th>Батальонлар</th>
            <th>Дебитор <br>қарздорлик <br>{{ from_date_format }} й.</th>
            <th>Умумий тушум <br>({{ month_name }})</th>
            <th>Дебитор <br>қарздорлик <br>{{ to_date_format }} й.</th>
            <th>Бир кунлик <br>тушум <br>{{ to_date_format }} й.</th>
            <th>%</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in list" :key="index + 'reports'">
          <td>{{ index + 1 }}</td>
          <td>{{ item.district_name }}</td>
          <td>{{ item.total_part_not_paid | formatNumber(2) }}</td>
          <td>{{ item.transaction_amount_from_to | formatNumber(2) }}</td>
          <td>{{ item.total_part_not_paid_in_to_date | formatNumber(2) }}</td>
          <td>{{ item.transaction_amount_in_to_date | formatNumber(2) }}</td>
          <td>{{ item.percent | formatNumber(2) }}</td>
        </tr>
        <tr>
          <td colspan="2">{{ $t("message.total") }}</td>
          <td>{{ totals.total_total_part_not_paid | formatNumber(2) }}</td>
          <td>{{ totals.total_transaction_amount_from_to | formatNumber(2) }}</td>
          <td>{{ totals.total_total_part_not_paid_in_to_date | formatNumber(2) }}</td>
          <td>{{ totals.total_transaction_amount_in_to_date | formatNumber(2) }}</td>
          <td>{{ totals.total_percent | formatNumber(2) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script>

import { mapActions, mapGetters } from "vuex";

export default {
  data() {
    return {
        filter: {
            from_date: '',
            to_date: '',
        },
        from_date_format: '',
        to_date_format: '',
        month_name: '',
        loadingData: false,
        list: [],
        totals: {},
        excel_data: [],
        dd: 'Дебитор қарздорлик' + this.from_date_format,
        excel_fields: {
            // '№' :'number',
            // 'Батальонлар' :'district_name',
            // //'Дебитор қарздорлик' + this.from_date_format . 'й.' :'district_name',
            // this.dd :'district_name',
        },
    };
  },
  computed: {
    ...mapGetters({

    }),
  },
  watch: {
        filter: {
            handler: function(new_value, old_value) {
                if( this.filter.from_date && this.filter.to_date ){
                    this.fetchData();
                }
            },
            deep: true
        },
    },
  mounted() {
    this.fetchData();
    this.controlExcelData();
  },
  methods: {
    ...mapActions({
      updateList: "summaryReports/index",
    }),

    fetchData() {
        const query = { ...this.filter };
        if (!this.loadingData) {
            this.loadingData = true;
            this.updateList(query).then(res => {
                this.excel_data = [];
                if( res.data && res.data.applications_by_district ) {
                    this.list = Object.keys(res.data.applications_by_district).map(i => res.data.applications_by_district[i]);

                    var i = 1;
                    this.list.forEach(item => {
                        this.excel_data.push({
                            'number': i,
                            'district_name': item.district_name,
                            'total_part_not_paid': item.total_part_not_paid,
                            'transaction_amount_from_to': item.transaction_amount_from_to,
                            'total_part_not_paid_in_to_date': item.total_part_not_paid_in_to_date,
                            'transaction_amount_in_to_date': item.transaction_amount_in_to_date,
                            'percent': item.percent,
                        });
                        i++;
                    });
                }

                if( res.data && res.data.totals ) {
                    this.totals = res.data.totals;
                    this.excel_data.push({
                        'number': '',
                        'district_name': "Жами",
                        'total_part_not_paid': this.totals.total_total_part_not_paid,
                        'transaction_amount_from_to': this.totals.total_transaction_amount_from_to,
                        'total_part_not_paid_in_to_date': this.totals.total_total_part_not_paid_in_to_date,
                        'transaction_amount_in_to_date': this.totals.total_transaction_amount_in_to_date,
                        'percent': this.totals.total_percent,
                    });
                }

                if( res.data && res.data.from_date ) {
                    this.from_date_format = res.data.from_date;
                }
                if( res.data && res.data.to_date ) {
                    this.to_date_format = res.data.to_date;
                }
                if( res.data && res.data.month_name ) {
                    this.month_name = res.data.month_name;
                }
                this.controlExcelData();

                this.loadingData = false
            }).catch(err => {
                this.loadingData = false
            });
        }
    },
    controlExcelData() {
      this.excel_fields = {};
      this.excel_fields['№'] = 'number';
      this.excel_fields['Батальонлар'] = 'district_name';
      this.excel_fields['Дебитор қарздорлик ' + this.from_date_format + ' й.'] = 'total_part_not_paid';
      this.excel_fields['Умумий тушум ' + this.month_name] = 'transaction_amount_from_to';
      this.excel_fields['Дебитор қарздорлик ' + this.to_date_format + ' й.'] = 'total_part_not_paid_in_to_date';
      this.excel_fields['Бир кунлик тушум ' + this.to_date_format + ' й.'] = 'transaction_amount_in_to_date';
      this.excel_fields['%'] = 'percent';
    },
  }
};
</script>
