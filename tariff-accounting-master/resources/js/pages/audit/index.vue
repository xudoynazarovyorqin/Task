<template  ref="AuditsList">
 <div class="row table-sm mr-0 ml-0 p-0">
   <div class="crm-content-header d-flex w-100 mb-2">
      <div class="crm-content-header-left d-flex w-50">
          <div class="crm-content-header-left-item mr-3" style="width:260px">
              <h5 class="d-inline mr-2 font-weight-bold">{{ $t("message.audits")  }}</h5>
              <crm-refresh @c-click="refresh()"></crm-refresh>
          </div>
            <div class="crm-content-header-left-item">
                <el-input size="mini" :placeholder="$t('message.search')" prefix-icon="el-icon-search"
                    v-model="filterForm.search" clearable></el-input>
            </div>
            <div class="crm-content-header-left-item">
                <el-date-picker v-model="filterForm.from_date" type="date" :format="date_format"
                    :value-format="date_format" size="mini" :placeholder="$t('message.from')">
                </el-date-picker>
            </div>
            <div class="crm-content-header-left-item">
                <el-date-picker v-model="filterForm.to_date" type="date" :format="date_format"
                    :value-format="date_format" size="mini" :placeholder="$t('message.to')">
                </el-date-picker>
            </div>
      </div>
      <div class="crm-content-header-right d-flex w-50 justify-content-end">
          <div class="crm-content-header-right-item">
              <crm-column-settings :columns="columns" @c-change="updateColumn"></crm-column-settings>
          </div>
      </div>
    </div>
    <table class="table table-bordered vld-parent table-hover" v-loading="loadingData"	:element-loading-text="$t('message.loading')" element-loading-spinner="el-icon-loading">
        <crm-pagination :pagination="pagination" @c-change="updatePagination"></crm-pagination>
        <thead>
            <tr>
                <crm-th :sort="sort" :column="columns.id" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.username" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.event" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.auditable_type" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.auditable_id" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.ip_address" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.created_at" @c-change="updateSort"></crm-th>
                <crm-th :sort="sort" :column="columns.changes"></crm-th>
                <crm-th :sort="sort" :column="columns.settings"></crm-th>
            </tr>
            <tr>
                <th v-if="columns.id.show">
                    <el-input clearable size="mini" class="id_input" v-model="filterForm.id"
                        :placeholder="columns.id.title"></el-input>
                </th>
                <th v-if="columns.username.show">
                    <users v-model="filterForm.user_id" size="mini"></users>
                </th>
                <th v-if="columns.event.show">
                    <el-select filterable clearable :placeholder="columns.event.title" size="mini"
                        v-model="filterForm.event">
                        <el-option v-for="(value,index) in events" :key="index" :label="value" :value="index">
                        </el-option>
                    </el-select>
                </th>
                <th v-if="columns.auditable_type.show">
                    <el-input clearable size="mini" v-model="filterForm.auditable_type"
                        :placeholder="columns.auditable_type.title"></el-input>
                </th>
                <th v-if="columns.auditable_id.show">
                    <el-input clearable size="mini" v-model="filterForm.auditable_id"
                        :placeholder="columns.auditable_id.title"></el-input>
                </th>
                <th v-if="columns.ip_address.show">
                    <el-input clearable size="mini" v-model="filterForm.ip_address"
                        :placeholder="columns.ip_address.title"></el-input>
                </th>
                <th v-if="columns.created_at.show">
                    <el-date-picker v-model="filterForm.created_at" :placeholder="columns.created_at.title" size="mini"  :value-format="date_format">
                    </el-date-picker>
                </th>
                <th v-if="columns.settings.show"></th>
                <th v-if="columns.changes.show"></th>
            </tr>
        </thead>
        <transition-group name="flip-list" tag="tbody">
            <tr v-for="audit in list" :key="audit.id">
                <td v-if="columns.id.show">{{ audit.id }}</td>
                <td v-if="columns.username.show">{{ audit.username }}</td>
                <td v-if="columns.event.show">{{ audit.event }}</td>
                <td v-if="columns.auditable_type.show">{{ audit.auditable_type }}</td>
                <td v-if="columns.auditable_id.show">{{ audit.auditable_id }}</td>
                <td v-if="columns.ip_address.show">{{ audit.ip_address }}</td>
                <td v-if="columns.created_at.show">{{ audit.created_at | dateFormat }}</td>
                <td v-if="columns.changes.show" class="text-center">
                    <span @click="downloadChangesFunction(audit.id)" style="cursor: pointer; color: #3490dc;">
                        <span class="el-icon-download"></span>
                    </span>
                </td>
                <td v-if="columns.settings.show"></td>
            </tr>
        </transition-group>
    </table>
</div>
</template>
<script>
    import { mapActions, mapGetters } from "vuex";
    import list from "@/utils/mixins/list";
    import users from '@inventory/crm-user-select';

	export default {
        mixins:[list],
        components:{users},
        computed: {
            ...mapGetters({
                list: "audits/list",
                columns: "audits/columns",
                pagination: "audits/pagination",
                filter: "audits/filter",
                sort: "audits/sort",
                events: 'audits/events'
            }),
        },
        methods: {
            ...mapActions({
                updateList: "audits/index",
                updateSort: "audits/updateSort",
                updateFilter: "audits/updateFilter",
                updateColumn: "audits/updateColumn",
                updatePagination: "audits/updatePagination",
                downloadChanges: "audits/downloadChanges",
                refreshData: "audits/refreshData",
            }),
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
            }
        },
	};
</script>
<style>
    .el-button+.el-button {
        margin-left: 0!important;
    }
</style>
