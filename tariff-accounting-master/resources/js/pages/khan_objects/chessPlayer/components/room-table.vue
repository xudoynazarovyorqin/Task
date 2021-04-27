<template>
    <div>

        <div class="page-title add-room-title">Демо дом</div>

        <div class="add-room-table">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="col__1">Номер помещения</th>
                        <th class="col__1">Кол-во комнат</th>
                        <th class="col__1">Площадь, м2</th>
                        <th class="col__1">Полная цена  </th>
                        <th class="col__1">Статус</th>
                        <th class="col__1">Студия</th>
                        <th class="col__1">Свободная планировка</th>
                        <th class="col__1">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Квартира №
                            <el-popover class="style_popover" placement="top" width="200" v-model="visible">
                                <div class="popover_input">
                                    <el-input  size="mini"  v-model="input" clearable> </el-input>
                                    <el-button type="success" size="mini" @click="visible = false" icon="el-icon-check"></el-button>
                                    <el-button type="danger"  size="mini" @click="visible = false" icon="el-icon-circle-close"></el-button>
                                </div>
                                <el-button class="s__but" slot="reference">341</el-button>
                            </el-popover>
                        </td>
                        <td>
                            <el-popover class="style_popover" placement="top" width="200" v-model="visible1">
                                <div class="popover_input">
                                    <el-input  size="mini"  v-model="input1" clearable> </el-input>
                                    <el-button type="success" size="mini" @click="visible1 = false" icon="el-icon-check"></el-button>
                                    <el-button type="danger"  size="mini" @click="visible1 = false" icon="el-icon-circle-close"></el-button>
                                </div>
                                <el-button class="s__but" slot="reference">1</el-button>
                            </el-popover>
                        </td>
                        <td>
                            <el-popover class="style_popover" placement="top" width="200" v-model="visible2">
                                <div class="popover_input">
                                    <el-input  size="mini"  v-model="input2" clearable> </el-input>
                                    <el-button type="success" size="mini" @click="visible2 = false" icon="el-icon-check"></el-button>
                                    <el-button type="danger"  size="mini" @click="visible2 = false" icon="el-icon-circle-close"></el-button>
                                </div>
                                <el-button class="s__but" slot="reference">41.23</el-button>
                            </el-popover>
                        </td>
                        <td>
                            <el-popover class="style_popover" placement="top" width="200" v-model="visible3">
                                <div class="popover_input">
                                    <el-input  size="mini"  v-model="input3" clearable> </el-input>
                                    <el-button type="success" size="mini" @click="visible3 = false" icon="el-icon-check"></el-button>
                                    <el-button type="danger"  size="mini" @click="visible3 = false" icon="el-icon-circle-close"></el-button>
                                </div>
                                <el-button class="s__but" slot="reference">2.300 00</el-button>
                            </el-popover>
                        </td>
                        <td>
                            <el-popover class="style_popover" placement="top" width="200" v-model="visible4">
                                <div class="popover_input">
                                    <el-input  size="mini"  v-model="input4" clearable> </el-input>
                                    <el-button type="success" size="mini" @click="visible4 = false" icon="el-icon-check"></el-button>
                                    <el-button type="danger"  size="mini" @click="visible4 = false" icon="el-icon-circle-close"></el-button>
                                </div>
                                <el-button class="s__but" slot="reference">Свободно</el-button>
                            </el-popover>
                        </td>
                        <td><el-switch v-model="value1" active-color="#13ce66" inactive-color="#ff4949"></el-switch></td>
                        <td><el-switch v-model="value2" active-color="#13ce66" inactive-color="#ff4949"></el-switch></td>
                        <td>
                            <el-row>
                                <el-button type="success" icon="el-icon-view" circle @click="centerDialogVisible = true"></el-button>
                                <el-button type="primary" icon="el-icon-edit" circle></el-button>
                                <el-button type="danger" icon="el-icon-delete" circle></el-button>
                            </el-row>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <el-dialog title="Warning" :visible.sync="centerDialogVisible" width="40%" center>
            <img src="/images/kark.png" alt="" style="width: 100%;">
        </el-dialog>

    </div>
</template>
<script>
  export default {
    data() {
        return {
            centerDialogVisible: false,
            visible: false,
            visible1: false,
            visible2: false,
            visible3: false,
            visible4: false,
            input: '341',
            input1: '1',
            input2: '41.23',
            input3: '2.300 00',
            input4: 'Свободно',
            value1: '',
            value2: ''
        }
    }
  };
</script>
