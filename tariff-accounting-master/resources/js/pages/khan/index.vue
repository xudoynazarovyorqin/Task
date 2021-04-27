<template>
    <div>
        
        <el-row :gutter="20">
            <el-col :span="24">
                 <el-button type="success"  size="small" round  @click="kassa_modal = true">Kassa modal </el-button>
                 <el-button type="success"  size="small" round>Primary</el-button>
            </el-col>  
        </el-row>
        
        <!-- Components Khan -->
        <el-drawer title="I am the title" :visible.sync="kassa_modal"  :with-header="false" size="80%">
            <ModalKass></ModalKass>
        </el-drawer>
        <!-- Components Khan -->

    </div>
</template>
<script>
    import ModalKass from "./components/kassa_modal"
    export default {
        components:{
            ModalKass,
        },
        data() {
            return {
            kassa_modal: false,
            };
        },
    };
</script>