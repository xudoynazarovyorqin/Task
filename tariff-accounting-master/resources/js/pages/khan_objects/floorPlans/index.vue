<template>
    <div>
        <div class="floorPlans">
            <el-row :gutter="20" class="mb-3">
                <el-col :span="24">
                    <div class="page-title ">Демо дом</div>
                    <el-breadcrumb
                        class="mb-1"
                        separator-class="el-icon-arrow-right"
                    >
                        <el-breadcrumb-item :to="{}">Name</el-breadcrumb-item>
                        <el-breadcrumb-item>Name</el-breadcrumb-item>
                        <el-breadcrumb-item>Name</el-breadcrumb-item>
                    </el-breadcrumb>
                </el-col>
                <el-col :span="24">
                    <div class="info_flpprPlans">
                        Рекомендуемый размер изображения 1000*1000 пикселей. Без
                        пустых или белых полей в формате jpeg, jpg, png. Вес
                        одного изображения не должен превышать 500 кб.
                        <br />
                        Необходимо загрузить отдельными файлами планировки для
                        каждой секции, даже если планировки типовые.
                    </div>
                </el-col>
                <el-col :span="24">
                    <el-row class="but_style">
                        <el-button type="success" size="small" round>
                            <i class="el-icon-upload"></i> Выбрать планировки
                            помещений</el-button
                        >
                        <!-- <el-button type="success" size="small" round> <i class="el-icon-upload"></i> Загрузить 3D-планировки</el-button> -->
                    </el-row>
                </el-col>
            </el-row>

            <el-row :gutter="20">
                <el-col :span="2">Планировка</el-col>
                <el-col :span="3">Наименование</el-col>
                <el-col :span="14"
                    >Помещения, соответствующие планировке</el-col
                >
                <el-col :span="5"></el-col>
            </el-row>
            <el-row :gutter="20" class="block_add">
                <el-col :span="2"
                    ><img
                        class="img_isolate"
                        alt=""
                        src="https://pb9413.profitbase.ru/uploads/apartment_62_62/uploads/preset/9413/41d3fe71ab8d8372d7557f80c0249b05.png"
                /></el-col>
                <el-col :span="3">&nbsp;</el-col>
                <el-col :span="14">
                    <div class="mark-chess">
                        <span>№263</span>
                        <span>№267</span>
                        <span>№271</span>
                        <span>№275</span>
                        <span>№279</span>
                        <span>№283</span>
                        <span>№287</span>
                        <span>№291</span>
                        <span>№295</span>
                        <span>№299</span>
                        <span>№303</span>
                        <span>№307</span>
                        <span>№311</span>
                        <span>№315</span>
                        <span>№319</span>
                        <span>№323</span>
                        <span>№327</span>
                        <span>№331</span>
                        <span>№335</span>
                        <span>№339</span>
                        <span>№343</span>
                        <span>№343</span>
                        <span>№343</span>
                        <span>№343</span>
                        <span>№343</span>
                        <span>№343</span>
                        <span>№343</span>
                        <br />
                        <div class="dashed" @click="markChassModal = true">
                            Отметить на шахматке
                        </div>
                    </div>
                </el-col>
                <el-col :span="5">
                    <el-row class="text-right but_style">
                        <el-button
                            type="success"
                            size="mini"
                            round
                            @click="floorPlansEdit = true"
                        >
                            <i class="el-icon-edit-outline"></i>
                            Редактировать</el-button
                        >
                        <el-button
                            type="danger"
                            size="mini"
                            round
                            @click="modalDElet = true"
                        >
                            <i class="el-icon-circle-close"></i> Удалить
                            объект</el-button
                        >
                    </el-row>
                </el-col>
            </el-row>
        </div>

        <el-drawer
            :visible.sync="markChassModal"
            size="90%"
            :with-header="false"
        >
            <MarkChassModal></MarkChassModal>
        </el-drawer>

        <el-dialog
            class="CopuHomeModal modal_icon_ix"
            title=" Внимание Вы действительно хотите это сделать?"
            :visible.sync="modalDElet"
            width="30%"
            center
        >
            <DeletModal></DeletModal>
        </el-dialog>

        <el-dialog
            class="CopuHomeModal modal_icon_ix"
            title="Редактирование планировки помещения"
            :visible.sync="floorPlansEdit"
            width="30%"
            center
        >
            <el-upload
                class="upload-demo w-100"
                action="https://jsonplaceholder.typicode.com/posts/"
                :on-preview="handlePreview"
                :on-remove="handleRemove"
                :file-list="fileList"
                list-type="picture"
            >
                <el-button size="small" type="primary" class="w-100" round>
                    <i class="el-icon-upload"></i>Добавить
                    изображения</el-button
                >
                <!-- <div slot="tip" class="el-upload__tip">
                    jpg/png files with a size less than 500kb
                </div> -->
            </el-upload>
        </el-dialog>
    </div>
</template>
<script>
import DeletModal from "./../../../components/crm/include/khna_modal";
import MarkChassModal from "./components/mark-chess-modal";
export default {
    name: "floorPlans",
    components: {
        MarkChassModal,
        DeletModal
    },
    data() {
        return {
            markChassModal: false,
            modalDElet: false,
            floorPlansEdit: false,
            fileList: [
                {
                    name: "food.jpeg",
                    url:
                        "https://fuss10.elemecdn.com/3/63/4e7f3a15429bfda99bce42a18cdd1jpeg.jpeg?imageMogr2/thumbnail/360x360/format/webp/quality/100"
                }
            ]
        };
    },
    methods: {
        handleRemove(file, fileList) {
            console.log(file, fileList);
        },
        handlePreview(file) {
            console.log(file);
        }
    }
};
</script>
