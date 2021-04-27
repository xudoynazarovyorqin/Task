<template>
    <el-card>
        <span slot="header">
            <i class="el-icon-user"></i> {{ $t('message.employees') }}
        </span>
        <el-row :gutter="24">
            <el-col :span="12" v-if="is_edit || is_show === true">
                <div v-for="(employeeGroup,index) in old_employee_groups" :key="index" class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center active">
                            {{ employeeGroup.name }}
                            <span class="badge badge-primary badge-pill">({{ employeeGroup.users.length }})</span>
                        </li>
                        <li v-for="(employee,index) in employeeGroup.users" :key="index" class="list-group-item d-flex justify-content-between align-items-center">
                            - {{ (employee.user) ? employee.user.name : '' }}
                        </li>
                    </ul>
                    <br>
                </div>
            </el-col>
            <el-col :span="12" v-if="is_show !== true">
                <el-form label-width="100px">
                    <el-input
                        placeholder="Ключевое слово фильтра"
                        v-model="filterText">
                    </el-input>
                    <el-form-item :label="'Сотрудники'">
                        <el-tree
                        :check-on-click-node="true"
                            :data="dataEmployees"
                            show-checkbox
                            node-key="id"
                            class="filter-tree"
                            ref="tree"
                            :default-expanded-keys="[]"
                            :props="defaultEmployeeProps"
                            :filter-node-method="filterNode"
                            @check-change="changeEmployees()"
                        ></el-tree>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
    </el-card>
</template>
<script>
import { mapGetters, mapActions } from 'vuex'
    export default {
            props:['is_edit','old_employee_groups','is_show'],
            data() {
                return {
                    filterText: '',
                     defaultEmployeeProps: {
                        children: "children",
                        label: "name",
                    }
                }
            },
            mounted() {
                if (this.dataEmployees && this.dataEmployees.length === 0)this.loadEmployeeGroups();
            },
            watch: {
                 filterText(val) {
                    this.$refs.tree.filter(val);
                }
            },
            computed: {
                ...mapGetters({
                    dataEmployees: "employeeGroups/list",
                })
            },
            methods: {
                ...mapActions({
                    loadEmployeeGroups: "employeeGroups/index",
                }),
                changeEmployees(){
                    let checkedEmployees = this.$refs.tree.getCheckedNodes();
                    let employees = []
                    for (let key in checkedEmployees) {
                        if (checkedEmployees.hasOwnProperty(key)) {
                        let checkedEmployee = checkedEmployees[key];
                            if (checkedEmployee.pivot) {
                                employees.push(checkedEmployee.pivot);
                            }
                        }
                    }
                    this.$emit('crm-change',employees);
                },
                filterNode(value, data) {
                    if (!value) return true;
                    return data.name.indexOf(value) !== -1;
                }
            },
    }
</script>