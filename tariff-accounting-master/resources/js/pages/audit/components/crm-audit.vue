<template>
    <div class="mytable_row">
        <el-row :gutter="20" class="mytable_row_top">
            <el-col :span="4">{{columns.username.title}}</el-col>
            <el-col :span="4">{{columns.event.title}}</el-col>
            <el-col :span="4">{{columns.auditable_type.title}}</el-col>
            <el-col :span="3">{{columns.auditable_id.title}}</el-col>
            <el-col :span="3">{{columns.ip_address.title}}</el-col>
            <el-col :span="3">{{columns.created_at.title}}</el-col>
            <el-col :span="3">{{columns.changes.title}}</el-col>
        </el-row>

        <el-row :gutter="20" v-for="(item,index) in list" :key="'audit-'+index" class="mytable_row_content" :class="fetchColor(item)">
            <el-col :span="4">{{(item.username) ? item.username : 'Нет'}}&nbsp;</el-col>
            <el-col :span="4">{{item.event}}</el-col>
            <el-col :span="4">{{item.auditable_type}}</el-col>
            <el-col :span="3">{{item.auditable_id}}</el-col>
            <el-col :span="3">{{item.ip_address}}</el-col>
            <el-col :span="3">{{item.created_at}}</el-col>
            <el-col :span="3">
            <el-button type="success" size="mini" icon="el-icon-download" round @click="downloadChangesFunction(item.id)"></el-button>
            </el-col>
        </el-row>
    </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
export default {
    data() {
        return {
        list: [],

        };
    },
    computed: {
            ...mapGetters({
                columns: "audits/columns",
                events: 'audits/events'
            }),
        },
    mounted() {
        this.fetchData();
    },
    methods: {
        ...mapActions({
            auditList: "audits/auditList",
            downloadChanges: "audits/downloadChanges",
        }),
        fetchData() {
        this.auditList()
            .then(res => {
                this.list = res.data.audits;
            })
            .catch(err => {
            console.log(err);
            });
        },
        fetchColor(item){
            if(item.event == 'deleted') return 'bg_delet';
            else if(item.event == 'updated') return 'bg_obdt';
            else return 'bg_add';
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
        }
    }
};
</script>

<style scoped>
</style>
