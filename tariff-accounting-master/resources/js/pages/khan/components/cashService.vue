<template>
    <div>
        
        <header id="el-drawer__title" class="el-drawer__header">
            <span title="Новый заказ">
                Услуги
            </span>

            <div class="mr-5">
                <el-badge :value="1" class="icon_font item mr-2" type="primary">
                    <i class="el-icon-shopping-cart-full"></i> 
                </el-badge>
                300 000 000
            </div>
           
            <el-button type="success" size="small" class="mr-1"> Сохранить</el-button>
            <el-button type="primary" size="small" class="mr-1"> Сохранить закрыть</el-button>
            <el-button type="warning" icon="el-icon-close" size="small">Закрыть</el-button>
        </header>
        
        <div class="collapse_style">
            <el-collapse v-model="activeNames" @change="handleChange">

                <el-collapse-item title="Хранение груза" name="1">
                    <div>
                       
                        <table class="table table-bordered color_tab table-striped">
                            <thead>
                                <tr>
                                    <th class="Widht_5">№</th>
                                    <th class="Widht_20">Тип вагон</th>
                                    <th class="Widht_20">Вырузка</th>
                                    <th class="Widht_20">Заезд вагона</th>
                                    <th class="Widht_20">Сумма</th>
                                    <th class="Widht_10">Выбор</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Test</td>
                                    <td>Test</td>
                                    <td>Test</td>
                                    <td>Test</td>
                                    <td>
                                        <el-checkbox v-model="checked"></el-checkbox>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Test</td>
                                    <td>Test</td>
                                    <td>Test</td>
                                    <td>Test</td>
                                    <td>
                                        <el-checkbox v-model="checked"></el-checkbox>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Test</td>
                                    <td>Test</td>
                                    <td>Test</td>
                                    <td>Test</td>
                                    <td>
                                        <el-checkbox v-model="checked"></el-checkbox>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </el-collapse-item>


                <el-collapse-item title="Таможенный склад. Погрузкаа" name="2">
                    <div>
                       ? ? ?
                    </div>
                </el-collapse-item>

                <el-collapse-item title="Заезд и отъезд вагона" name="3">
                    <div>
                        ? ? ?
                    </div>
                </el-collapse-item>

            </el-collapse>
        </div>
    </div>
</template>
<script>
  export default {
    data() {
      return {
        activeNames: ['1'],
        checked: true
      };
    },
    methods: {
      handleChange(val) {
        console.log(val);
      }
    }
  }
</script>