export default {
    props: {
        label: {
            default: null
        },
        size: {
            default: 'small',
        },
        plc: {
            default: null,
        }
    },
    data() {
        return {
            selected: null,
            loading: false,
        }
    },
    methods: {
        showModal(modal) {
            this.$modal.show(modal);
        },
        dispatch(e) {
            this.$emit('input', e)
            this.selected = e
        },
        onSearch(search){
            if( search != '' ) {
                this.loading = true;
                this.search(search, this);
              }
        },
        search: _.debounce((search, self) => {
            if( _.isFunction(self.updateInventory)) {
              self.updateInventory({search: search})
                .then(res => {
                    self.loading = false;
                })
                .catch(err => {
                    self.loading = false;
                    self.$alert(err);
                });
            }

          }, 1000)
    },
}
