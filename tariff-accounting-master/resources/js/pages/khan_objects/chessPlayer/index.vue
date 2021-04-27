<template>
    <div>
        <div class="floorPlans">
            <el-row :gutter="20" class="mb-3">
                <el-col :span="24">
                    <div class="page-title">Демо дом</div>
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
                        На этой странице вы можете наполнить ваш дом помещениями
                        вручную, не используя импорт. После того как ваши дома
                        будут наполнены, на этой странице вы сможете быстро
                        вносить изменения в отдельные помещения.
                    </div>
                </el-col>
                <el-col :span="24">
                    <el-row class="but_style">
                        <el-button
                            type="success"
                            size="small"
                            @click="centerDialogVisible = true"
                            round
                        >
                            <i class="el-icon-plus"></i> Добавить подъезд
                        </el-button>
                    </el-row>
                </el-col>
            </el-row>
        </div>

        <div class="aad-Floor">
            <el-tabs
                v-model="editableTabsValue"
                type="card"
                closable
                @tab-remove="removeTab"
            >
                <el-tab-pane
                    v-for="(item, index) in editableTabs"
                    :key="index"
                    :label="item.title"
                    :name="item.name"
                >
                    <!-- {{item.content}} -->
                    <AadFloor></AadFloor>
                </el-tab-pane>
            </el-tabs>
        </div>

        <el-dialog
            class="CopuHomeModal modal_icon_ix"
            title="Добавление подъезда"
            :visible.sync="centerDialogVisible"
            width="30%"
            center
        >
            <el-form ref="form" :model="form" size="small" class="style__label">
                <el-form-item label="Название">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>
                <div class="text-center">
                    <el-button
                        class="w-50"
                        type="success"
                        round
                        size="small"
                        @click="addTab(editableTabsValue)"
                        >Создать</el-button
                    >
                </div>
            </el-form>
        </el-dialog>
    </div>
</template>
<script>
import AadFloor from "./components/aad-Floor";
export default {
    name: "chessPlayer",
    components: { AadFloor },
    data() {
        return {
            form: {
                name: ""
            },
            centerDialogVisible: false,
            editableTabsValue: "2",
            editableTabs: [],
            tabIndex: 2
        };
    },
    methods: {
        addTab(targetName) {
            this.centerDialogVisible = false;
            let newTabName = ++this.tabIndex + "";
            this.editableTabs.push({
                title: this.form.name,
                name: newTabName,
                content: "New Tab content"
            });
            this.form.name = "";
            this.editableTabsValue = newTabName;
        },
        removeTab(targetName) {
            let tabs = this.editableTabs;
            let activeName = this.editableTabsValue;
            if (activeName === targetName) {
                tabs.forEach((tab, index) => {
                    if (tab.name === targetName) {
                        let nextTab = tabs[index + 1] || tabs[index - 1];
                        if (nextTab) {
                            activeName = nextTab.name;
                        }
                    }
                });
            }

            this.editableTabsValue = activeName;
            this.editableTabs = tabs.filter(tab => tab.name !== targetName);
        }
    }
};
</script>
