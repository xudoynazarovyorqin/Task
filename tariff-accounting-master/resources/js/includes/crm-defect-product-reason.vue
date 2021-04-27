<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span>  Причины </span>
            <el-button v-can="['assemblies.defect_product']" type="success" size="small" class="mr-1" @click="submit()"> {{ $t('message.save') }}</el-button>
        </header>
        <div class="row table-sm mr-0 ml-0 mt-4">
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header">  Причины для {{ defect_product.product ? defect_product.product.name : '' }}  {{ defect_product.quantity }}</h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="d-flex">
                                    <td class="col-5">Причины</td>
                                    <td class="col-5">Количество</td>
                                    <td class="col-2">Удалить </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="d-flex" v-for="(defect_product_reason,index) in collection" :key="'reasons'+index">
                                    <td class="col-5">
                                        {{ (defect_product_reason.reason) ? defect_product_reason.reason.name : '' }}
                                    </td>
                                    <td class="col-5">
                                        <el-input
                                            type="number"
                                            v-model="defect_product_reason.quantity"
                                            size="mini"
                                            :min="0"
                                        ></el-input>
                                    </td>
                                    <td class="col-2">
                                        <el-button @click="removeReason(defect_product_reason)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                                    </td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="input-group-sm col-12">
                                    <el-select
                                        @change="appendReason"
                                        v-model="current_reason"
                                        filterable
                                        remote
                                        size="mini"
                                        reserve-keyword
                                        placeholder="Добавить причина"
                                        :remote-method="searchReason"
                                        :loading="loadingReasons"
                                    >
                                        <el-option
                                        v-for="item in reasons"
                                        :key="item.id"
                                        :label="item.name"
                                        :value="item"
                                        ></el-option>
                                        <template slot="empty">
                                        <p
                                            class="cursor-pointer el-select-dropdown__empty"
                                            @click="showModal('create_reason')"
                                        >Создать новый причина</p>
                                        </template>
                                    </el-select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <crm-reason-create-modal @crm-close="closeCreateReasonModal"></crm-reason-create-modal>
        </div>
    </div>
</template>
<script>
import CrmReasonCreateModal from '@/includes/modals/crm-reason-create-modal'
import { mapGetters, mapActions } from "vuex";
export default {
    components:{
        CrmReasonCreateModal
    },
    props: {
        defect_product: {},
        drawer: {type:Boolean,default: false}
    },
  data() {
    return {
        collection: [],
        current_reason: '',
        loadingReasons: false,
    };
  },
  computed: {
      ...mapGetters({
          reasons: 'reasons/list',
      }),
  },
  watch: {
      defect_product: {
          handler: async function() {
              if (this.defect_product) {
                if (this.defect_product.reasons) {
                    this.collection = this.defect_product.reasons
                }
              }
          },
          deep: true,
          immediate: true
      }
  },
  mounted() {
      this.searchReason('');
  },
  methods:{
        ...mapActions({
            filterReasons: 'reasons/index',
        }),
        searchReason(filter){
            let request = {'search': filter};
            this.loadingReasons = true;
            this.filterReasons(request)
                .then(res=>{
                    this.loadingReasons = false
                })
                .catch(err=>{
                    this.loadingReasons = false
                })
        },
        appendReason(){
            if (!this.current_reason || this.current_reason === '' || this.current_reason === null || this.current_reason === undefined) {
                return;
            }
            let item = {}
            item.reason = this.current_reason;
            item.reason_id = this.current_reason.id;
            item.quantity = 0;
            item.color = this.current_reason.color
            this.collection.push(item)
            this.current_reason = ''
        },
        removeReason(line){
            this.collection.length > 0 ? (this.collection.splice(this.collection.indexOf(line),1)) : ''
        },
        submit(){
            this.$emit('close',{drawer:'drawerReasons',reasons: this.collection})
        },
        showModal(modal) {
            this.$modal.show(modal);
        },
        closeCreateReasonModal(data) {
            if (data.created && data.created === true) {
                this.current_reason = data.reason;
                this.appendReason()
            }
        },

    }
};
</script>