<template>
  <div>
    <div class="row table-sm mr-0 ml-0 p-0 mb-3 width-100">
      <div class="col-12 p-0 d-flex align-items-center">
        <h5 class="d-inline mr-2 font-weight-bold">Объекты на продажу</h5>
        <crm-refresh @c-click="refresh()"></crm-refresh>
        <div class="text-center d-flex sorddata ml-3">
          <el-date-picker
            class="ml-3"
            size="mini"
            v-model="filterForm.end_date"
            type="date"
            format="yyyy-MM-dd"
            value-format="yyyy-MM-dd"
            placeholder="дата"
          ></el-date-picker>
          <el-select
            v-model="filterForm.region"
            size="mini"
            placeholder="Найти Объекты"
            style="width: 100%; margin-left: 15px;"
          >
            <el-option label="Дархон" value="shanghai"></el-option>
            <el-option label="Мирзо Улугбек" value="beijing"></el-option>
          </el-select>
          <el-select
            v-model="filterForm.region"
            size="mini"
            placeholder="Тип Объекты"
            style="width: 100%; margin-left: 15px;"
          >
            <el-option label="Дархон" value="shanghai"></el-option>
            <el-option label="Мирзо Улугбек" value="beijing"></el-option>
          </el-select>
        </div>
      </div>
    </div>
    <div class="Objects_ssle create_New_Objects">
      <el-row :gutter="20">
        <el-col :span="8">
          <div
            class="residential_Complex style_building d-flex flex-column justify-content-center align-items-center"
          >
            <div class="info_Complex d-flex flex-column justify-content-center align-items-center">
              <div class="title_Complex mb-2">Жилой комплекс</div>
              <div class="name_Complex mb-2">Дархон</div>
              <div class="data_Complex mb-2">(создан 05.03.2020)</div>
              <div class="show_Complex mb-2" @click="salemodal = true">
                <el-button type="success" icon="el-icon-view" round>Редактировать</el-button>
              </div>
              <div class="info-object-sale d-flex justify-content-between w-100">
                <div class="font_16 text-center">
                  <div>Свободные</div>
                  <div class="shtuk_free">340</div>
                </div>
                <div class="font_16 text-center">
                  <div>Забронированные</div>
                  <div class="shtuk_booked">100</div>
                </div>
                <div class="font_16 text-center">
                  <div>Проданные</div>
                  <div class="shtuk_sold">240</div>
                </div>
              </div>
            </div>
            <div class="footer_Complex d-flex align-items-center">
              <a>
                <span>Домов</span>
                <span>А1</span>
              </a>
              <span></span>
              <a>
                <span>Помещений</span>
                <span>340</span>
              </a>
            </div>
          </div>
        </el-col>
        <!-- end col -->

        <el-col :span="8">
          <div
            class="residential_Complex style_building d-flex flex-column justify-content-center align-items-center"
          >
            <div class="info_Complex d-flex flex-column justify-content-center align-items-center">
              <div class="title_Complex mb-2">Жилой комплекс</div>
              <div class="name_Complex mb-2">Дархон</div>
              <div class="data_Complex mb-2">(создан 05.03.2020)</div>
              <div class="show_Complex mb-2" @click="salemodal = true">
                <el-button type="success" icon="el-icon-view" round>Редактировать</el-button>
              </div>
              <div class="info-object-sale d-flex justify-content-between w-100">
                <div class="font_16 text-center">
                  <div>Свободные</div>
                  <div class="shtuk_free">340</div>
                </div>
                <div class="font_16 text-center">
                  <div>Забронированные</div>
                  <div class="shtuk_booked">100</div>
                </div>
                <div class="font_16 text-center">
                  <div>Проданные</div>
                  <div class="shtuk_sold">240</div>
                </div>
              </div>
            </div>
            <div class="footer_Complex d-flex align-items-center">
              <a>
                <span>Домов</span>
                <span>А1</span>
              </a>
              <span></span>
              <a>
                <span>Помещений</span>
                <span>340</span>
              </a>
            </div>
          </div>
        </el-col>
        <!-- end col -->

        <el-col :span="8">
          <div
            class="residential_Complex style_building d-flex flex-column justify-content-center align-items-center"
          >
            <div class="info_Complex d-flex flex-column justify-content-center align-items-center">
              <div class="title_Complex mb-2">Жилой комплекс</div>
              <div class="name_Complex mb-2">Дархон</div>
              <div class="data_Complex mb-2">(создан 05.03.2020)</div>
              <div class="show_Complex mb-2" @click="salemodal = true">
                <el-button type="success" icon="el-icon-view" round>Редактировать</el-button>
              </div>
              <div class="info-object-sale d-flex justify-content-between w-100">
                <div class="font_16 text-center">
                  <div>Свободные</div>
                  <div class="shtuk_free">340</div>
                </div>
                <div class="font_16 text-center">
                  <div>Забронированные</div>
                  <div class="shtuk_booked">100</div>
                </div>
                <div class="font_16 text-center">
                  <div>Проданные</div>
                  <div class="shtuk_sold">240</div>
                </div>
              </div>
            </div>
            <div class="footer_Complex d-flex align-items-center">
              <a>
                <span>Домов</span>
                <span>А1</span>
              </a>
              <span></span>
              <a>
                <span>Помещений</span>
                <span>340</span>
              </a>
            </div>
          </div>
        </el-col>
        <!-- end col -->
      </el-row>
      <!-- end row -->
    </div>

    <el-drawer
      title="Дархон Домов А1"
      :with-header="false"
      :visible.sync="salemodal"
      direction="rtl"
      size="100%"
    >
      <SaleModal></SaleModal>
    </el-drawer>
    <!-- end el-drawer -->
  </div>
</template>
<script>
import SaleModal from "./components/sale-modal";
export default {
  name: "khan_object_sale",
  components: { SaleModal },
  data() {
    return {
      salemodal: false,
      filterForm: {}
    };
  }
};
</script>
<style lang="scss"></style>
