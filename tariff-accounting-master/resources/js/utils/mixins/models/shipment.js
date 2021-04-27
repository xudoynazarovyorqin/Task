import { mapGetters, mapActions } from 'vuex';
import users from '@selects/crm-user';
import products from '@selects/crm-product';
import shipmentableTypes from '@selects/crm-shipmentable-type';

export default {
    components: {
        users,
        shipmentableTypes,
        products
    },
    data() {
        return {
            form: {},
            items: [],
            includeDrawer: false,
            loadingProduct: false
        }
    },
    computed: {
        ...mapGetters({
            model: 'shipments/model',
            rules: 'shipments/rules',
            columns: 'shipments/columns',
            getForm: 'shipments/form',
            selectedRow: 'shipments/selectedRow',
        })
    },
    methods: {
        ...mapActions({
            showProduct: 'products/show',
        }),
        submit(close = true) {
            this.form['items'] = _.map(this.items, function(item) {
                return {
                    product_id: item.product ? item.product.id : null,
                    quantity: item.quantity,
                    issued_from_booked: item.issued_from_booked
                }
            })

            this.$refs['form'].validate((valid) => {
                if (valid) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.close();
                            this.listChanged();
                            this.waitingStop();
                        })
                        .catch(err => {
                            this.waitingStop();
                            this.$alert(err);
                        });
                }
            });
        },
        appendProduct(product) {
            this.loadingProduct = true;
            this.showProduct({ id: product.id, params: { warehouse_info: true } })
                .then(res => {
                    this.loadingProduct = false;
                    if (res.data) {
                        this.pushProduct(res.data.product, res.data.available, res.data.booked)
                    }
                })
                .catch(err => {
                    this.loadingProduct = false;
                })
        },
        pushProduct(product, available = 0, booked = 0, qty = 0, issued_from_booked = 0) {
            if (!_.some(this.items, ['product.id', product.id])) {
                let item = {};
                item.product = product;
                item.available = available;
                item.booked = booked;
                item.quantity = qty;
                item.issued_from_booked = issued_from_booked;
                this.items.push(item);
            }
        },
        deleteRow(index, rows) {
            this.$confirm(this.$t('message.delete_confirm'), this.$t('message.confirm'), {
                confirmButtonText: this.$t('message.yes'),
                cancelButtonText: this.$t('message.cancel'),
                type: 'warning'
            }).then(() => {
                rows.splice(index, 1);
            }).catch(() => {});
        },
        closeDrawer(drawer) {
            if (this.$refs[drawer] && _.isFunction(this.$refs[drawer].closeDrawer)) {
                this.$refs[drawer].closeDrawer();
            }
        },
        drawerClosed(ref) {
            if (this.$refs[ref] && _.isFunction(this.$refs[ref].closed)) {
                this.$refs[ref].closed()
            }
            if (this.selectedRow && this.form.shipmentable_id != this.selectedRow.id) {
                this.form.shipmentable_type = this.selectedRow ? this.selectedRow.shipmentable_type : null;
                this.form.shipmentable_id = this.selectedRow ? this.selectedRow.shipmentable_id : null;
                this.loadReservationsForDocuement();
            }
        },
        drawerOpened(ref) {
            if (this.$refs[ref] && _.isFunction(this.$refs[ref].opened)) {
                this.$refs[ref].opened()
            }
        },
        loadReservationsForDocuement() {
            this.items = [];
            this.loadingProduct = true;
            this.getReservations(this.selectedRow)
                .then(res => {
                    this.loadingProduct = false;
                    if (res.data && res.data.reservations) {
                        res.data.reservations.forEach(element => {
                            this.pushProduct(element.source, element.available, element.booked, 0, element.quantity - element.issued)
                        });
                    }
                }).catch(err => {
                    this.loadingProduct = false;
                    this.$alert(err)
                })

        },
    }
}