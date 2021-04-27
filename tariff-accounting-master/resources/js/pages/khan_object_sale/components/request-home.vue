<template>
  <div>
    <div class="form-application mr-4 ml-4 mt-4">
      <el-form ref="form" :model="form" class="style__label">
        <el-row>
          <el-col :span="8">
            <el-form-item label="клиента" size="small">
              <el-select v-model="form.region" placeholder="пожалуйста, выберите клиента">
                <el-option label="Zone one" value="shanghai"></el-option>
                <el-option label="Zone two" value="beijing"></el-option>
              </el-select>

              <div class="style__icon_plus">
                <i class="el-icon-plus" size="small"></i>
              </div>
            </el-form-item>

            <el-form-item label="Договор" size="small">
              <el-select v-model="form.state_id" placeholder="пожалуйста, выберите статус">
                <el-option label="Zone one" value="shanghai"></el-option>
                <el-option label="Zone two" value="beijing"></el-option>
              </el-select>

              <div class="style__icon_plus">
                <i class="el-icon-plus" size="small"></i>
              </div>
            </el-form-item>

            <el-form-item label="Статус" size="small">
              <el-select v-model="form.priority_id" placeholder="пожалуйста, выберите приоритет">
                <el-option label="Zone one" value="shanghai"></el-option>
                <el-option label="Zone two" value="beijing"></el-option>
              </el-select>

              <div class="style__icon_plus">
                <i class="el-icon-plus" size="small"></i>
              </div>
            </el-form-item>

            <el-form-item label="Приоритет" size="small">
              <el-select v-model="form.priority_id" placeholder="пожалуйста, выберите приоритет">
                <el-option label="Zone one" value="shanghai"></el-option>
                <el-option label="Zone two" value="beijing"></el-option>
              </el-select>

              <div class="style__icon_plus">
                <i class="el-icon-plus" size="small"></i>
              </div>
            </el-form-item>

            <el-form-item label="Оформить в рассрочку" size="small">
              <el-switch v-model="form.delivery"></el-switch>
            </el-form-item>
          </el-col>
          <!-- end col -->

          <el-col :span="16">
            <div class="apartment-preview">
              <div class="preview__img_sum d-flex">
                <img src="/images/ikk.png" alt />
                <div class="preview__sum ml-2">
                  <div class="apartment-preview__title">Жилой комплекс Дархон</div>
                  <div class="apartment-preview__description">
                    Екатеринбург, Свердловская область,
                    Малышева, 71
                  </div>
                  <div class="preview_room">
                    <span>1 к</span>
                    <span>№ 33</span>
                    <span>41.23 м2</span>
                  </div>
                  <div class="preview__sum_sum">
                    <div class="nax">2 920 000 000 so'm</div>
                    <div class="preview__description">
                      41-м2
                      <span>71 219 512</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </el-col>
          <!-- end col -->

          <el-col
            :span="24"
            class="mt-4"
            style="    border-top: 1px solid #dcdfe6; padding-top: 30px;"
          >
            <el-row>
              <el-col :span="12">
                <el-form-item label="Кредитный месяц" size="small">
                  <el-select
                    v-model="form.region"
                    placeholder="пожалуйста, выберите клиента"
                    style="width: 300px !important;"
                  >
                    <el-option label="Zone one" value="shanghai"></el-option>
                    <el-option label="Zone two" value="beijing"></el-option>
                  </el-select>
                </el-form-item>
              </el-col>

              <el-col :span="12">
                <el-form-item label="Пеня" size="small">
                  <el-input-number
                    v-model="form.num"
                    @change="handleChange"
                    :min="1"
                    :max="10"
                    style="width: 300px !important;"
                  ></el-input-number>
                </el-form-item>
              </el-col>
              <!-- end col -->

              <el-col :span="12">
                <el-form-item label="Процентная ставка по кредиту" size="small">
                  <el-input-number
                    v-model="form.num2"
                    @change="handleChange"
                    :min="1"
                    :max="10"
                    style="width: 300px !important;"
                  ></el-input-number>
                </el-form-item>
              </el-col>
              <!-- end col -->

              <el-col :span="12">
                <el-form-item label="Начальная дата" size="small">
                  <el-date-picker
                    type="date"
                    placeholder="Pick a date"
                    v-model="form.date1"
                    style="width: 300px !important;"
                  ></el-date-picker>
                </el-form-item>
              </el-col>
              <!-- end col -->

              <el-col :span="12">
                <el-form-item label="Первоначальный взнос" size="small">
                  <el-input v-model="form.name" style="width: 300px !important;"></el-input>
                </el-form-item>
              </el-col>
              <!-- end col -->

              <el-col :span="12">
                <el-form-item label="Первоначальный взнос (%)" size="small">
                  <el-input-number
                    v-model="form.num3"
                    @change="handleChange"
                    :min="1"
                    :max="10"
                    style="width: 300px !important;"
                  ></el-input-number>
                </el-form-item>
              </el-col>
              <!-- end col -->
            </el-row>
            <!-- end row -->
          </el-col>
          <!-- end col -->
        </el-row>
        <!-- end row -->
      </el-form>
      <!-- end form -->
    </div>
  </div>
</template>
<script>
export default {
  components: {},
  data() {
    return {
      form: {}
    };
  },
  methods: {
    handleChange(value) {
      console.log(value);
    }
  },
  computed: {}
};
</script>
<style lang="scss">
.style__icon_plus {
  font-size: 17px;
  height: 32px;
  line-height: 30px;
  width: 30px;
  border-radius: 4px;
  border: 1px solid #dcdfe6;
  color: #dcdfe6;
  display: inline-block;
  padding: 0px 0px 0px 5px;
  cursor: pointer;
  margin-left: 10px;
}
</style>
