<template>
    <el-row>
        <header id="el-drawer__title" class="el-drawer__header">
			<span> {{ $t('message.Choose on which document to deliver the raw materials') }}</span>
			<el-button v-can="['realizations.create']" type="primary" size="small" class="mr-1"
				@click="submit(true)"> {{ $t('message.Snap') }}</el-button>
			<el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
			</el-button>
		</header>
        <el-main v-loading="loading">
            <el-row>
                <el-form v-model="form" label-position="right" label-width="120px">
                    <el-col :span="8">
                        <realizationable-types v-model="form.realizationable_type" size="small"></realizationable-types>
                    </el-col>
                    <el-col :span="4" :offset="1">
                        <el-button type="primary" size="small" icon="el-icon-search" @click="search()">{{ $t('message.Search') }}</el-button>
                    </el-col>
                </el-form>
            </el-row>
            <el-table
                ref="singleTable"
                :data="items"
                highlight-current-row
                class="crm"
                @current-change="handleCurrentChange"
                style="width: 100%">
            <el-table-column
                type="index"
                width="50">
            </el-table-column>
            <el-table-column
               :label="$t('message.Type document')">
                <template slot-scope="item">
                    {{ $t('message.' + item.row.realizationable_type) }}
                </template>
            </el-table-column>
            <el-table-column
               :label="$t('message.Document number')">
                <template slot-scope="item">
                    {{ item.row.realizationable_id }}
                </template>
            </el-table-column>
            <el-table-column
               :label="$t('message.datetime')">
                <template slot-scope="item">
                    {{ item.row.datetime }}
                </template>
            </el-table-column>
            <el-table-column
               :label="$t('message.status')">
                <template slot-scope="item">
                    {{ item.row.status }}
                </template>
            </el-table-column>
        </el-table>
        </el-main>
    </el-row>
</template>
<script>
    import drawer from '@/utils/mixins/includes/drawer';
    import realizationableTypes from "@selects/crm-realizationable-type";
    import { mapActions } from "vuex";
    export default {
        mixins:[drawer],
        components:{realizationableTypes},
        data() {
            return {
                items: [],
                currentRow: null,
                form:{
                    realizationable_type: ''
                }
            }
        },
        methods: {
            ...mapActions({
                getDocuments:'realizations/getDocuments'
            }),
            handleCurrentChange(val) {
                this.currentRow = val;
            },
            submit(){
                this.$store.commit("realizations/SET_SELECTED_ROW",this.currentRow);
                this.close();
            },
            parent() {
                return this.$parent;
            },
            afterOpen(){
                this.load();
            },
            load(){
                this.changeLoading(true)
                this.getDocuments(this.form)
                .then(res => {
                    this.items = res.data ? res.data.documents : [];
                    this.changeLoading(false)
                }).catch(err => {
                    this.items = [];
                    this.changeLoading(false)
                    this.$alert(err)
                })
            },
            search(){
                this.load();
            },
            afterLeave(){
               this.currentRow = null;
            }
        }
    }
</script>