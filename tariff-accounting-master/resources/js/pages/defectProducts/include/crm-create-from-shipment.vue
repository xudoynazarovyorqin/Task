<template>
  <div class="row mr-0">
    <div class="col-12 mt-2">
      <div class="modal-body">
        <el-form ref="formFromShipment" status-icon :model="form" :rules="rules" label-position="right" label-width="120px" >
          <el-col :span="12">
            <el-form-item label="ID отгрузки" size="small">
              <el-col :span="12">
                <el-input size="mini" v-model="form.shipment_id" style="width: 100%;" placeholder="ID отгрузки" clearable></el-input>
              </el-col>
              <el-button type="primary" size="mini" @click="getShimpentObject()">OK</el-button>
            </el-form-item>
          </el-col>

          <el-col :span="12">
            <div v-if="have_object">
              <table class="table table-bordered">
                <tbody>
                  <tr>
                    <td class="text-left" style="width: 50%">Клиент: {{ model.client_name }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </el-col>
          <el-col :span="24" v-if="have_object">
            <el-tabs type="border-card">
              <el-tab-pane>
                <span slot="label">
                  <i class="el-icon-s-grid"></i> История
                </span>

                <el-table :data="old_defect_products" style="width: 100%">
                    <el-table-column type="expand">
                      <template slot-scope="props">
                        <p v-for="(item,index) in props.row.defect_product_reasons" :key="index">
                            {{ (item.reason) ? item.reason.name : '' }}: {{ item.quantity }}
                        </p>
                      </template>
                    </el-table-column>
                    <el-table-column label="Наименование продукта" prop="product_name"></el-table-column>
                    <el-table-column label="Ед. изм." prop="measurement"></el-table-column>
                    <el-table-column label="Кол. брак" prop="quantity"></el-table-column>
                    <el-table-column :label="columns.date.title" prop="date"></el-table-column>
                    <el-table-column :label="columns.created_at.title" prop="created_at"></el-table-column>
                </el-table>
              </el-tab-pane>
            </el-tabs>
            <br />
          </el-col>

          <el-col :span="24" v-if="have_object">
            <el-tabs type="border-card">
              <el-tab-pane>
                <span slot="label">
                  <i class="el-icon-s-grid"></i> Продукты
                </span>

                <table class="table mb-0">
                  <thead>
                      <tr class="d-flex">
                          <th class="col-1">№</th>
                          <th class="col-3">Наименование продукта</th>
                          <th class="col-1">Ед. изм.</th>
                          <th class="col-1 text-center">Кол. отгружен</th>
                          <th class="col-2 text-center">Кол. брак</th>
                          <th class="col-2 text-center">Дата</th>
                          <th class="col-1 text-center">Причина</th>
                          <th class="col-1 text-center">Удалить</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr v-for="(shipment_product, index) in shipment_products" class="d-flex" :key="shipment_product.id + shipment_product.product_name">
                          <td class="col-1">
                              {{ index  + 1 }}.
                          </td>
                          <td  class="input-group-sm col-sm-3">
                              <b>{{ shipment_product.product_name }}</b>
                          </td>
                          <td class="input-group-sm col-sm-1">
                              <p>{{ shipment_product.measurement }}</p>
                          </td>
                          <td class="input-group-sm col-sm-1">
                              <p>{{ shipment_product.shimpent_quantity }}</p>
                          </td>
                          <td class="input-group-sm col-sm-2 text-center">
                              <el-input-number v-model="shipment_product.quantity" size="mini" :min="0" :max="shipment_product.max_quantity" ></el-input-number>
                          </td>
                          <td class="input-group-sm col-sm-2 text-center">
                              <el-date-picker v-model="shipment_product.date" size="mini" format="yyyy/MM/dd" :value-format="date_format"></el-date-picker>
                          </td>
                          <td class="text-center col-sm-1">
                              <el-button size="mini" type="primary" @click="openReasons(shipment_product, index)" round>Причины</el-button>
                          </td>
                          <td class="text-center col-sm-1">
                              <el-button @click="removeProduct(shipment_product)" type="danger" icon="el-icon-delete" size="mini" circle></el-button>
                          </td>
                      </tr>

                      <tr class="d-flex">
                          <td  class="input-group-sm col-sm-4 select_product">
                              <el-select
                                  @change="appendProduct"
                                  v-model="current_product"
                                  filterable
                                  size="mini"
                                  reserve-keyword
                                  placeholder="Продукты">
                                  <el-option
                                      v-for="(item,index) in sh_products"
                                      :key="index+'sh_products'"
                                      :label="item.product_name"
                                      :value="item">
                                  </el-option>
                              </el-select>
                          </td>
                          <td class="col-sm-9"></td>
                      </tr>
                  </tbody>
                </table>
              </el-tab-pane>
            </el-tabs>
            <br />
          </el-col>

          <el-col :span="24" v-if="have_object">
            <table>
              <tbody>
                <tr>
                  <th scope="row">
                    <el-button type="primary" round @click="submitShipment(true)" size="small">Сохранить и закрыть</el-button>
                  </th>
                </tr>
              </tbody>
            </table>
          </el-col>
        </el-form>
      </div>
    </div>

    <el-drawer :append-to-body="true" title="Причины" :visible.sync="drawerDefectReason" size="40%" :drawer="drawerDefectReason">
      <div>
        <crm-defect-reason @close="responseReasonDrawer" :defect_product="currentProduct" :defect_current_index="current_index"></crm-defect-reason>
      </div>
    </el-drawer>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import CrmDefectReason from './crm-defect-reason';
export default {
  props: {
    reloadModel: { type: Boolean, required: true }
  },
  components:{
      'crm-defect-reason': CrmDefectReason
  },

  data() {
    return {
      form: {},
      shipment_products: [],
      drawerDefectReason: false,
      have_object: false,
      current_product: '',
      currentProduct: null,
      current_index: null,
    };
  },

  watch: {
    reloadModel: {
      handler: function() {
        if (this.reloadModel) {
          this.have_object = false;
        }
      },
      deep: true
    },    
  },

  computed: {
    ...mapGetters({
      model: "defectProduct/model",
      old_defect_products: "defectProduct/old_defect_products",
      rules: "defectProduct/rules",
      columns: "defectProduct/columns",

      sh_products: "defectProduct/shipment_products",
    })
  },
  methods: {
    ...mapActions({
      save: "defectProduct/createDefectFromShipment",

      getShipment: "defectProduct/getShipment",
    }),

    getShimpentObject() {
      if (this.validateShipmentId()) {
        this.getShipment({shipment_id: this.form.shipment_id})
          .then(res => {
            this.form = JSON.parse(JSON.stringify(this.model));
            this.have_object = true;            
          })
          .catch(err => {
            this.$alert(err);
            this.have_object = false;
          });
      }
    },

    submitShipment(close = true){
        this.form['defect_products'] = this.shipment_products;
        
        if ( this.validateProducts() ){
           this.save(this.form)
               .then(res => {
                   this.$alert(res);
                   if (close)
                       this.$emit('c-close',{reload: true, drawer: 'drawerCreateFromShipment'});
               })
               .catch(err => {
                   this.$alert(err);
               });
        }
    },

    validateProducts() {
       if (this.shipment_products.length === 0)
       {
           this.$message({
               message: 'Продукты пусти',
               type: 'warning'
           });
           return false;
       }
       return true;
    },

    validateShipmentId() {
      if (!this.form.shipment_id) {
        this.$message({
          message: "ID отгрузки пустой",
          type: "warning"
        });
        return false;
      }

      return true;
    },

    appendProduct(){
        this.shipment_products.push({
            shipment_product_id: this.current_product.shipment_product_id,
            product_id: this.current_product.product_id,
            product_name: this.current_product.product_name,
            measurement: this.current_product.measurement,
            shimpent_quantity: parseFloat(this.current_product.shimpent_quantity),
            max_quantity: parseFloat(this.current_product.max_quantity),
            quantity: 0,
            date: '',
            reasons: [],            
        })
        
        this.current_product = '';
    },

    removeProduct (line) {
        this.shipment_products.splice(this.shipment_products.indexOf(line), 1)
    },

    openReasons(shipment_product, index){
      this.currentProduct = shipment_product;
      this.current_index = index;
      this.drawerDefectReason = true;
    },

    responseReasonDrawer(data){
      if( this.current_index && this.current_index !== null )
      {
        this.shipment_products[data.current_index].reasons = data.reasons;
      }

      this[data.drawer] = false
      $(".v-modal").remove();
    }
  }
};
</script>
