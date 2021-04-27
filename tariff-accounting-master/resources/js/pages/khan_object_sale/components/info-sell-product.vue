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
                  <div class="b-select-item__name">ЖК Демо — Демо дом - № 33</div>

                  <div class="b-select-item__descr">
                    <div class="b-select-item__descr-item">Подъезд Секция 4</div>
                    <div class="b-select-item__descr-item">21 этаж</div>
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

    <!-- start info-sell-product -->
    <div class="info-sell-product mr-5 ml-5 mt-4">
      <el-row>
        <el-col :span="14">
          <div class="cost-details">
            <div class="item-cost-datas color_od">
              <div class="cost-details__descr">Стоимость продажи</div>
              <div class="cost-details__value">2 920 000 000 so'm</div>
            </div>
            <div class="item-cost-datas color_ya">
              <div class="cost-details__descr">Номер квартиры</div>
              <div class="cost-details__value">№ 33</div>
            </div>
            <div class="item-cost-datas color_od">
              <div class="cost-details__descr">Адрес</div>
              <div class="cost-details__value">
                Екатеринбург, Свердловская область, Малышева, 71
                - эт. 19 № 333
              </div>
            </div>
            <div class="item-cost-datas color_ya">
              <div class="cost-details__descr">Подъезд</div>
              <div class="cost-details__value">Секция 4</div>
            </div>
            <div class="item-cost-datas color_od">
              <div class="cost-details__descr">Этаж</div>
              <div class="cost-details__value">19</div>
            </div>
            <div class="item-cost-datas color_ya">
              <div class="cost-details__descr">Название дома</div>
              <div class="cost-details__value">Демо дом</div>
            </div>
            <div class="item-cost-datas color_od">
              <div class="cost-details__descr">Название ЖК</div>
              <div class="cost-details__value">ЖК Демо</div>
            </div>
            <div class="item-cost-datas color_ya">
              <div class="cost-details__descr">Кол-во комнат</div>
              <div class="cost-details__value">1</div>
            </div>
            <div class="item-cost-datas color_od">
              <div class="cost-details__descr">Площадь, м2</div>
              <div class="cost-details__value">41.23 м2</div>
            </div>
            <div class="item-cost-datas color_ya">
              <div class="cost-details__descr">Название дома</div>
              <div class="cost-details__value">Демо дом</div>
            </div>
            <div class="item-cost-datas color_od">
              <div class="cost-details__descr">Название ЖК</div>
              <div class="cost-details__value">ЖК Демо</div>
            </div>
            <div class="item-cost-datas color_ya">
              <div class="cost-details__descr">Кол-во комнат</div>
              <div class="cost-details__value">1</div>
            </div>
            <div class="item-cost-datas color_od">
              <div class="cost-details__descr">Площадь, м2</div>
              <div class="cost-details__value">41.23 м2</div>
            </div>
          </div>
        </el-col>
        <el-col :span="10">
          <!-- <el-carousel :interval="5000" arrow="always">  -->
          <el-carousel arrow="always" :autoplay="false" height="400px">
            <el-carousel-item class="carousel_img">
              <img src="/images/k1.jpg" alt />
            </el-carousel-item>
            <el-carousel-item class="carousel_img">
              <img src="/images/k2.jpg" alt />
            </el-carousel-item>
            <el-carousel-item class="carousel_img">
              <img src="/images/k3.jpg" alt />
            </el-carousel-item>
            <el-carousel-item class="carousel_img">
              <img src="/images/k4.jpg" alt />
            </el-carousel-item>
          </el-carousel>
          <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="zakaz ifon-sale-squver-color">
              <span>
                <i class="squver-color free"></i>
                Свободные
              </span>
              <!-- <span>
                                <i class="squver-color booked"></i>
                                Забронированные
                            </span>
                            <span>
                                <i class="squver-color sold"></i>
                                Проданные
              </span>-->
            </div>
            <el-button
              type="success"
              icon="el-icon-edit-outline"
              round
              @click="requestHome = true"
            >Заявка на квартиру</el-button>
          </div>
        </el-col>
      </el-row>
    </div>
    <!-- end info-sell-product -->

    <el-drawer
      title="Заявка на квартиру"
      :visible.sync="requestHome"
      direction="rtl"
      size="65%"
      :append-to-body="true"
    >
      <RequestHome></RequestHome>
    </el-drawer>
    <!-- end el-drawer -->
  </div>
</template>
<script>
import RequestHome from "./request-home";
export default {
  components: { RequestHome },
  data() {
    return {
      requestHome: false
    };
  }
};
</script>

<style lang="scss">
.carousel_img img {
  margin: 0;
  width: 100%;
  height: 100%;
  -o-object-fit: unset;
  object-fit: unset;
}
.info-sell-product {
  .el-carousel {
    border: 1px solid #dcdfe6;
    border-radius: 5px;
  }
  .el-carousel__container {
    position: relative;
    min-height: 400px;
  }
  .el-carousel__arrow {
    background-color: rgba(27, 101, 144, 0.82);
  }
}
.item-cost-datas {
  display: flex;
  justify-content: space-between;
  padding: 10px 20px;
  position: relative;
  z-index: 2;
  font-size: 17px;
}
// .item-cost-datas.color_ya::after {
//     width: 35px;
//     height: 35px;
//     content: "";
//     display: block;
//     background: #f2f0fe;
//     position: absolute;
//     top: 5px;
//     right: -15px;
//     border-radius: 5px;
//     z-index: -1;
//     transform: rotate(45deg);
// }
.color_od {
  background-color: #ffffff;
}
.color_ya {
  // background-color: #1b659040;
  background: #d0d0d245;
}
.cost-details {
  margin-right: 50px;
}

.cost-details__value {
  font-weight: 700;
}
.zakaz {
  .squver-color {
    width: 25px;
    height: 25px;
  }
  span {
    display: flex;
    font-size: 16px;
  }
}
</style>
