<template>
    <div class="table-modal-sale mytabel mr-3 ml-3 mt-3">
        <table class="table table-bordered table-hover mr-0 ml-0 p-0 bg-white">
            <thead>
                <tr>
                    <th v-if="columns.id.show">{{ columns.id.title }}</th>
                    <th v-if="columns.porch.show">
                        {{ columns.porch.title }}
                    </th>
                    <th v-if="columns.floor.show">
                        {{ columns.floor.title }}
                    </th>
                    <th v-if="columns.price.show">
                        {{ columns.price.title }}
                    </th>
                    <th v-if="columns.room.show">
                        {{ columns.room.title }}
                    </th>
                    <th v-if="columns.area.show">
                        {{ columns.area.title }}
                    </th>
                    <th v-if="columns.full.show">
                        {{ columns.full.title }}
                    </th>
                    <th v-if="columns.status.show">
                        {{ columns.status.title }}
                    </th>
                    <th v-if="columns.stocks.show">
                        {{ columns.stocks.title }}
                    </th>
                </tr>

                <tr>
                    <th v-if="columns.id.show">
                        <el-input
                            clearable
                            size="mini"
                            v-model="filterForm.id"
                            :placeholder="columns.id.title"
                        ></el-input>
                    </th>
                    <th v-if="columns.porch.show">
                        <el-input
                            clearable
                            size="mini"
                            v-model="filterForm.porch"
                            :placeholder="columns.porch.title"
                        ></el-input>
                    </th>
                    <th v-if="columns.floor.show">
                        <el-select
                            v-model="filterForm.floor"
                            filterable
                            clearable
                            :placeholder="columns.floor.title"
                            size="mini"
                        >
                            <el-option label="1" value="shanghai"></el-option>
                            <el-option label="2" value="beijing"></el-option>
                        </el-select>
                    </th>
                    <th v-if="columns.price.show">
                        <el-input
                            clearable
                            size="mini"
                            v-model="filterForm.price"
                            :placeholder="columns.price.title"
                        >
                        </el-input>
                    </th>
                    <th v-if="columns.room.show">
                        <el-select
                            v-model="filterForm.room"
                            filterable
                            clearable
                            :placeholder="columns.room.title"
                            size="mini"
                        >
                            <el-option label="1" value="shanghai"></el-option>
                            <el-option label="2" value="beijing"></el-option>
                        </el-select>
                    </th>
                    <th v-if="columns.area.show">
                        <el-select
                            v-model="filterForm.area"
                            filterable
                            clearable
                            :placeholder="columns.area.title"
                            size="mini"
                        >
                            <el-option
                                label="34 m2"
                                value="shanghai"
                            ></el-option>
                            <el-option
                                label="102 m2"
                                value="beijing"
                            ></el-option>
                        </el-select>
                    </th>
                    <th v-if="columns.full.show">
                        <el-input
                            clearable
                            size="mini"
                            v-model="filterForm.full"
                            :placeholder="columns.full.title"
                        >
                        </el-input>
                    </th>
                    <th v-if="columns.status.show">
                        <el-select
                            v-model="filterForm.status"
                            filterable
                            clearable
                            :placeholder="columns.status.title"
                            size="mini"
                        >
                            <el-option
                                label="Свободные"
                                value="shanghai"
                            ></el-option>
                            <el-option
                                label="Забронированные"
                                value="beijing"
                            ></el-option>
                            <el-option
                                label="Проданные"
                                value="beijing"
                            ></el-option>
                        </el-select>
                    </th>
                    <th v-if="columns.stocks.show">
                        <el-input
                            clearable
                            size="mini"
                            v-model="filterForm.stocks"
                            :placeholder="columns.stocks.title"
                        >
                        </el-input>
                    </th>
                </tr>
            </thead>

            <transition-group name="flip-list" tag="tbody">
                <tr v-for="user in list" :key="user.id" class="cursor-pointer">
                    <td v-if="columns.id.show">{{ user.id }}</td>
                    <td v-if="columns.porch.show">{{ user.porch }}</td>
                    <td v-if="columns.floor.show">
                        {{ user.floor }}
                    </td>
                    <td v-if="columns.price.show">
                        {{ user.price }}
                    </td>
                    <td v-if="columns.room.show">{{ user.room }}</td>
                    <td v-if="columns.area.show">
                        {{ user.area }}
                    </td>
                    <td v-if="columns.full.show">{{ user.full }}</td>
                    <td v-if="columns.status.show">{{ user.status }}</td>
                    <td v-if="columns.stocks.show">
                        {{ user.stocks }}
                    </td>
                </tr>
            </transition-group>
        </table>
    </div>
</template>
<script>
// import CreateApplication from './components/crm-create-modal'
export default {
    components: {},
    data() {
        return {
            data_s: "",
            // loadingData: false,
            // tableID: false,
            filterForm: {
                search: "",
                id: "",
                porch: "",
                floor: "",
                price: "",
                room: "",
                area: "",
                full: "",
                status: "",
                stocks: ""
            },
            list: [
                {
                    id: "1",
                    porch: "Секция 1",
                    floor: "24",
                    price: "60 235 ",
                    room: "1",
                    area: "42.50 м2",
                    full: "2 560 000 ",
                    status: "Свободна",
                    stocks: "нет"
                },
                {
                    id: "1",
                    porch: "Секция 1",
                    floor: "25",
                    price: "60 235 ",
                    room: "1",
                    area: "42.50 м2",
                    full: "2 560 000 ",
                    status: "Свободна",
                    stocks: "нет"
                },
                {
                    id: "1",
                    porch: "Секция 1",
                    floor: "26",
                    price: "60 235 ",
                    room: "3",
                    area: "42.50 м2",
                    full: "2 560 000 ",
                    status: "Свободна",
                    stocks: "нет"
                },
                {
                    id: "1",
                    porch: "Секция 1",
                    floor: "26",
                    price: "60 235 ",
                    room: "2",
                    area: "42.50 м2",
                    full: "2 560 000 ",
                    status: "Свободна",
                    stocks: "нет"
                }
            ],
            columns: {
                id: {
                    show: true,
                    title: "Номер помещения",
                    sortable: true,
                    column: "id"
                },
                porch: {
                    show: true,
                    title: "Подъезд",
                    sortable: true,
                    column: "porch"
                },
                floor: {
                    show: true,
                    title: "Этаж",
                    sortable: true,
                    column: "floor"
                },
                price: {
                    show: true,
                    title: "Цена за метр",
                    sortable: true,
                    column: "price"
                },
                room: {
                    show: true,
                    title: "Комнатность",
                    sortable: true,
                    column: "room"
                },
                area: {
                    show: true,
                    title: "Площадь, м2",
                    sortable: true,
                    column: "area"
                },
                full: {
                    show: true,
                    title: "Полная цена",
                    sortable: true,
                    column: "full"
                },
                status: {
                    show: true,
                    title: "Статус",
                    sortable: true,
                    column: "status"
                },
                stocks: {
                    show: true,
                    title: "Акции",
                    sortable: true,
                    column: "stocks"
                }
            }
            // tableID: false,
            // tableUser: false
        };
    }
};
</script>
