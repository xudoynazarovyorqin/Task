<template>
  <div class="row table-sm mr-0 ml-0 mt-4">
    <div class="col-12">
      <el-tabs type="border-card">
        <el-tab-pane>
          <span slot="label">
            <i class="el-icon-caret-bottom"></i> Продукт: {{ defect_product.product_name }}({{ defect_product.quantity }})
          </span>
          <div class="row table-sm mr-0 ml-0 p-0 width-100">
           <table class="table table-bordered">
                <thead>
                    <tr class="d-flex">
                        <td class="col-6 text-left">Причина</td>
                        <td class="col-4 text-left">Количество</td>
                        <td class="col-2 text-center">Удалить</td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="d-flex" v-for="(defect_product_reason,index) in collection" :key="'reasons'+index">
                        <td class="col-6 text-left">
                            {{ defect_product_reason.reason_name }}
                        </td>
                        <td class="col-4">
                            <el-input-number :precision="2" :step="1" v-model="defect_product_reason.quantity" size="mini" :min="0"></el-input-number>
                        </td>
                        <td class="text-center col-sm-2">
                            <el-button @click="removeReason(defect_product_reason)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                        </td>
                    </tr>
                    <tr class="d-flex">
                        <td class="input-group-sm col-6 current_product">
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
            <div class="row m-0">
                <div class="col-10 p-0">
                    <table>
                        <tbody>
                        <tr>
                            <th scope="row">
                                <el-button type="primary" round @click="submit()" size="small"> Сохранить</el-button>
                            </th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </el-tab-pane>
      </el-tabs>
    </div>
    <crm-reason-create-modal @crm-close="closeCreateReasonModal"></crm-reason-create-modal>
  </div>
</template>
<script>
import CrmReasonCreateModal from '../../../includes/modals/crm-reason-create-modal'
import { mapGetters, mapActions } from "vuex";
export default {
    components:{
        CrmReasonCreateModal
    },
    props: {
        defect_product: {},
        defect_current_index: {}
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
  methods:{
        ...mapActions({
            filterReasons: 'reasons/index',
        }),
        searchReason(filter){
            let request = {'search': filter}
            this.loadingReasons = true
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
            item.reason_id =  this.current_reason.id
            item.reason_name = this.current_reason.name;
            item.quantity = 0;
            item.color = this.current_reason.color
            this.collection.push(item)
            this.current_reason = ''
        },
        removeReason(line){
            this.collection.length > 0 ? (this.collection.splice(this.collection.indexOf(line),1)) : ''
        },
        submit(){
            //this.defect_product.reasons = this.collection
            //this.$emit('close','drawerDefectReason')
            this.$emit('close', {drawer:'drawerDefectReason', reasons: this.collection, current_index: this.defect_current_index})
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