<template>
  <div>
        <el-dropdown>
            <el-button type="primary">
                    Добавить этаж <i class="el-icon-arrow-down el-icon--right"></i>
            </el-button>
            <el-dropdown-menu slot="dropdown">
                <el-dropdown-item>
                    <el-button class="pus_but"  v-on:click="add_item">Добавить надземный этаж</el-button>
                </el-dropdown-item>
                <el-dropdown-item>
                    <el-button class="pus_but" >Добавить подземный этаж</el-button>
                </el-dropdown-item>
            </el-dropdown-menu>
        </el-dropdown>

        <div class="NewOrderList blocks_padding">
             <el-row v-for="(item, index) in items" :gutter="20" :key="index">

                <div class="add-Above-Ground-Floor">
                   <el-collapse  v-model="item.description">
                        <el-collapse-item title="Этаж 1" name="1">
                            <RoomTable></RoomTable>
                        </el-collapse-item>
                    </el-collapse>
                    <button class="btn-floating" v-on:click="remove_item(index)"> <i class="el-icon-circle-close"></i> </button>
                    <button class="btn-floating copy_block"> <i class="el-icon-document-copy"></i> </button>
                    <el-button  class="add__room" @click="addRoomForm = true"  type="success" size="small" round> <i class="el-icon-plus"></i> Добавить помещение</el-button>
                </div>

            </el-row>
        </div>

        <el-drawer :visible.sync="addRoomForm" size="80%" :with-header="false">
            <AddRoomForm></AddRoomForm>
        </el-drawer>


    </div>
</template>
<script>
import RoomTable from "./room-table"
import AddRoomForm from "./add-Room-Form"
export default {
     components: {RoomTable, AddRoomForm},
    data(){
        return {
            addRoomForm: false,
            items: [
                {
                    description: ['1'],
                    // name: 'Web Design',
                    // description: 'Invoice app',
                    // unit_cost: 300,
                    // quantity: 6
                }
            ]
        }
    },
    methods: {
        format_price: (number) => {
            return '$' + parseFloat(number).toFixed(2)
        },
        add_item: function () {
            this.items.push({
            // name: '',
            description: '',
            // description2: '',
            // description3: '',
            // description4: '',
            // unit_cost: '',
            // quantity: ''
            })
        },
        update_value: function (index, event, property) {
            this.items[index][property] = event.target.innerText
        },
        remove_item: function (index) {
            this.items.splice(index, 1)
        }
    },
    computed: {
        all_items: function () {
        return this.items.reduce((accumulator, item) => {
            return accumulator + (item.unit_cost*item.quantity)
        }, 0)
        },
        date: () => (new Date()).toLocaleString(),
        // total_amount_due: function () {
        //  return this.all_items - this.amount_paid
        // },
        total_all_items: function () {
            return this.all_items
        }
    }
}
</script>
