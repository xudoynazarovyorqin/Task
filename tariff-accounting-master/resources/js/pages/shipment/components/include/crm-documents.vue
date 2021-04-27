<template>
    <el-row>
        <header id="el-drawer__title" class="el-drawer__header">
			<span> {{ $t('message.Select a document for shipping goods') }}</span>
			<el-button v-can="['shipments.create']" type="primary" size="small" class="mr-1"
				@click="submit(true)"> {{ $t('message.Snap') }}</el-button>
			<el-button type="warning" @click="close()" icon="el-icon-close" size="small"> {{ $t('message.close') }}
			</el-button>
		</header>
        <el-main v-loading="loading">
            <el-row>
                <el-form v-model="form" label-position="right" label-width="120px">
                    <el-col :span="8">
                        <shipmentable-types v-model="form.shipmentable_type" size="small"></shipmentable-types>
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
                    {{ $t('message.' + item.row.shipmentable_type) }}
                </template>
            </el-table-column>
            <el-table-column
               :label="$t('message.Document number')">
                <template slot-scope="item">
                    {{ item.row.shipmentable_id }}
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
    import shipmentableTypes from "@selects/crm-shipmentable-type";
    import { mapActions } from "vuex";
    export default {
        mixins:[drawer],
        components:{shipmentableTypes},
        data() {
            return {
                items: [],
                currentRow: null,
                form:{
                    shipmentable_type: ''
                }
            }
        },
        methods: {
            ...mapActions({
                getDocuments:'shipments/getDocuments'
            }),
            handleCurrentChange(val) {
                this.currentRow = val;
            },
            submit(){
                this.$store.commit("shipments/SET_SELECTED_ROW",this.currentRow);
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