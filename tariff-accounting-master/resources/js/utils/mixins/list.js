export default {
    data() {
        return {
            drawerCreate: false,
            drawerShow: false,
            drawerUpdate: false,
            filterForm: {},
            loadingData: false,
            excel_data: [],
            excel_fields: {},
            checkAll: false,
            selectedItems: [],
            selectedItem: null,
            reloadList: false,
            loadingComments: false,
        }
    },
    created() {
        this.filterForm = JSON.parse(JSON.stringify(this.filter));
        this.debouncedFetchData = _.debounce(this.fetchData, 500);
    },
    mounted() {
        if (_.isFunction(this.controlExcelData)) {
            this.controlExcelData();
        }
    },
    watch: {
        filterForm: {
            handler: async function(newVal, oldVal) {
                await this.updatePagination({ key: "page", value: 1 });
                await this.updateFilter(newVal);
                this.debouncedFetchData();
            },
            deep: true,
            immediate: true
        },
        sort: {
            handler: async function(newVal, oldVal) {
                if (newVal != oldVal && _.isFunction(this.debouncedFetchData)) {
                    this.debouncedFetchData();
                }
            },
            deep: true,
            immediate: true
        },
        'pagination.page': {
            handler: async function(newVal, oldVal) {
                if (newVal != oldVal && _.isFunction(this.debouncedFetchData)) {
                    this.debouncedFetchData();
                }
            },
            deep: true,
            immediate: true,
        },
        'pagination.per_page': {
            handler: async function(newVal, oldVal) {
                if (newVal != oldVal && _.isFunction(this.debouncedFetchData)) {
                    this.debouncedFetchData();
                }
            },
            deep: true,
            immediate: true,
        },
        columns: {
            handler: function() {
                this.controlExcelData()
            },
            deep: true
        }
    },
    methods: {
        closeDrawer(drawer) {
            if (this.$refs[drawer] && _.isFunction(this.$refs[drawer].closeDrawer)) {
                this.$refs[drawer].closeDrawer();
            }
        },
        drawerClosed(ref) {
            if (this.$refs[ref]) {
                this.$refs[ref].closed()
            }
            if (this.reloadList === true) {
                this.fetchData();
                this.afterFetchData();
            }
            if (_.isFunction(this.empty)) {
                this.empty()
            }
        },
        drawerOpened(ref) {
            if (this.$refs[ref]) {
                if (_.isFunction(this.$refs[ref].opened)) {
                    this.$refs[ref].opened()
                }
            }
        },
        listChanged() {
            this.reloadList = true;
        },
        afterFetchData() {
            this.reloadList = false;
        },
        fetchData() {
            const query = {...this.filter, ...this.pagination, ...this.sort };
            if (!this.loadingData) {
                this.loadingData = true;
                this.updateList(query).then(res => {
                    this.loadingData = false
                }).catch(err => {
                    this.loadingData = false
                });
            }
        },
        refresh() {
            this.refreshData()
                .then(res => {
                    this.filterForm = JSON.parse(JSON.stringify(this.filter))
                })
                .catch(err => {})
        },
        edit(model) {
            this.selectedItem = model;
            this.drawerUpdate = true;
        },
        controlExcelData() {
            this.excel_fields = {};
            for (let key in this.columns) {
                if (this.columns.hasOwnProperty(key)) {
                    let column = this.columns[key];
                    if (column.show && column.column !== 'settings') {
                        this.excel_fields[column.title] = column.column;
                    }
                }
            }
        },
        destroy(model) {
            this.delete(model.id)
                .then(res => {
                    this.$alert(res);
                    this.fetchData()
                })
                .catch(err => {
                    this.$alert(err);
                })
        },
        print(model) {
            this.printModel({ id: model.id })
                .then(res => {
                    const WinPrint = window.open("", "", "left=0,top=0,toolbar=0,scrollbars=0,status=0");
                    WinPrint.document.write(res.data);
                    WinPrint.document.close();
                    //WinPrint.focus();
                    WinPrint.print();
                    WinPrint.close();
                })
                .catch(err => {
                    this.$alert(err);
                });
        },
    },
    destroyed() {

    },
}
