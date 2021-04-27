<template>
  <div>
    <!-- top-modal-sale-header-->
    <div class="top-modal-sale-header">
      <div class="title_header_fix">
        <el-row>
          <el-col :span="24">
            <div class="sale-select d-flex align-items-center justify-content-between">
              <div class="b-select-item">
                <div class="b-select-item__image-col">
                  <img
                    class="b-select-item__image"
                    src="https://pb9413.profitbase.ru/uploads/house_470_320/uploads/house/9413/5e8393d4b99f7.jpeg"
                  />
                </div>
                <div class="b-select-item__text-col">
                  <div class="b-select-item__name">ЖК Демо — Демо дом</div>

                  <div class="b-select-item__descr">
                    <div class="b-select-item__descr-item">
                      Екатеринбург, Свердловская область,
                      Малышева, 71
                    </div>
                    <div class="b-select-item__descr-item">IV квартал 2020</div>
                    <div class="b-select-item__descr-item">
                      <span>251 помещение</span>
                    </div>
                  </div>
                </div>
              </div>
              <el-button type="danger" icon="el-icon-close" size="small">Закрыть</el-button>
            </div>
          </el-col>
          <!-- end col -->
        </el-row>
        <!-- end row -->
      </div>
      <!-- end ale-select-modal -->
    </div>
    <!-- end top-modal-sale-header-->

    <div class="sale-select-modal mr-3 ml-3 mt-3">
      <el-row :gutter="20">
        <el-col :span="24">
          <div class="d-flex align-items-end justify-content-between">
            <div class="d-flex align-items-end">
              <div class="filter-modal-sale demo-input-suffix">
                <span class="demo-input-label">КОЛ-ВО КОМНАТ</span>
                <el-radio-group
                  v-model="radio3"
                  size="small"
                  style="display: block; margin-bottom: -6px;"
                >
                  <el-radio-button label="1"></el-radio-button>
                  <el-radio-button label="2"></el-radio-button>
                  <el-radio-button label="3"></el-radio-button>
                </el-radio-group>
              </div>

              <div class="filter-modal-sale demo-input-suffix">
                <span class="demo-input-label">СТОИМОСТЬ, $</span>
                <div class="d-flex">
                  <el-input size="small" placeholder="от" v-model="input1"></el-input>
                  <el-input size="small" placeholder="до" v-model="input2"></el-input>
                </div>
              </div>
              <div class="filter-modal-sale demo-input-suffix">
                <span class="demo-input-label">ПЛОЩАДЬ, М2</span>
                <div class="d-flex">
                  <el-input size="small" placeholder="от" v-model="input3"></el-input>
                  <el-input size="small" placeholder="до" v-model="input4"></el-input>
                </div>
              </div>
            </div>
            <el-dropdown class="tip_sale_but" trigger="click">
              <span class="el-dropdown-link">
                <i class="el-icon-film"></i>
                <span class="ml-1">Плитка</span>
                <span class="flex1">
                  <i class="el-icon-arrow-down el-icon--right"></i>
                </span>
              </span>
              <el-dropdown-menu class="ul_sale_but" slot="dropdown">
                <el-dropdown-item icon="el-icon-tickets">Список</el-dropdown-item>
                <el-dropdown-item icon="el-icon-menu">Планировки</el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </div>
          <div class="el__slider" style="flex: 1;">
            <div class="slider__block">
              <span class="title_slider__block">ПЛОЩАДЬ, М2</span>
              <el-slider v-model="value" range :marks="marks" :min="240" :max="1000"></el-slider>
            </div>
            <div class="slider__block">
              <span class="title_slider__block">СТОИМОСТЬ, $</span>
              <el-slider v-model="value" range :marks="marks" :min="240" :max="1000"></el-slider>
            </div>
          </div>
        </el-col>
        <!-- end col -->

        <el-col :span="24">
          <div class="ifon-sale-squver mt-3">
            <div class="ifon-sale-squver-title">
              Найдено
              <span>344</span> помещения. Из них
              свободно:
              <span>251</span>
            </div>
            <div class="ifon-sale-squver-color">
              <span>
                <i class="squver-color free"></i>
                Свободные
              </span>
              <span>
                <i class="squver-color booked"></i>
                Забронированные
              </span>
              <span>
                <i class="squver-color sold"></i>
                Проданные
              </span>
              <span>
                <i class="squver-color unavailable"></i>
                Недоступные
              </span>
            </div>
          </div>
        </el-col>
        <!-- end col -->
      </el-row>
      <!-- end row -->
    </div>
    <!-- end ale-select-modal-->

    <SquareModal></SquareModal>
    <!-- Modal Square Objevts -->

    <CardModal></CardModal>
    <!-- end Card Modal -->

    <TableModal></TableModal>
    <!-- Modal Table Objevts -->
  </div>
</template>
<script>
import SquareModal from "./square-modal";
import TableModal from "./table-modal";
import CardModal from "./card-modal";
export default {
  components: { SquareModal, TableModal, CardModal },
  data() {
    return {
      input1: "",
      input2: "",
      input3: "",
      input4: "",
      radio3: "",
      value: [240, 840],
      marks: {
        240: {
          style: {
            color: "#1989FA"
          },
          label: this.$createElement("strong", "240")
        },
        840: {
          style: {
            color: "#1989FA"
          },
          label: this.$createElement("strong", "840")
        }
      }
    };
  },
  methods: {
    flat(e) {
      console.log(e);

      e.target.classList.toggle("active1");
    }
  }
};
</script>
