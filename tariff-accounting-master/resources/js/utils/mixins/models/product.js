    import { mapGetters, mapActions } from 'vuex';
    import warehouseTypes from '@selects/crm-warehouse-type';
    import measurements from '@selects/crm-measurement';
    import countries from '@selects/crm-country';
    import categories from '@selects/crm-category';
    import materials from '@selects/crm-material';
    import currencies from '@inventory/crm-currency-select';
    import ProductPrice from '@inputs/crm-product-price-input';

    export default {
        props: ['product'],
        components: {
            warehouseTypes,
            measurements,
            countries,
            categories,
            materials,
            currencies,
            ProductPrice
        },
        data() {
            return {
                product_materials: [],
                semi_products: [],
                active_step: 1
            }
        },
        computed: {
            ...mapGetters({
                money_product: "money_product",
                getForm: 'products/form',
                rules: "products/rules",
                model: "products/model",
                columns: "products/columns",
                product_material: "products/product_material",
                semi_product: "products/semi_product",
                old_product_materials: "products/product_materials",
                old_semi_products: "products/semi_products",
                currencies: 'currencies/inventory'
            })
        },
        methods: {
            ...mapActions({
                empty: 'products/empty',
                updateInventory: 'products/inventory',
            }),
            submit(close = true) {
                /**
                 * Get product materials
                 */
                this.form['product_materials'] = this.product_materials.map((item) => {
                    return { material_id: item.material.id, quantity: item.quantity, inverse_quantity: item.inverse_quantity }
                });
                /**
                 * Get Semi products
                 */
                this.form['semi_products'] = this.semi_products.map((item) => {
                    return { semi_product_id: item.product.id, quantity: item.quantity }
                });
                this.$refs['form'].validate((valid) => {
                    if (valid && !this.waiting) {
                        this.waiting = true;
                        this.save(this.form)
                            .then(res => {
                                this.$alert(res);
                                this.listChanged()
                                this.waitingStop()
                                this.updateInventory();
                                if (close) {
                                    this.close()
                                } else {
                                    if (this.is_new === false) this.load();
                                }
                            })
                            .catch(err => {
                                this.waitingStop()
                                this.$alert(err);
                            });
                    }
                });
            },
            appendMaterial(material) {
                let product_material = JSON.parse(JSON.stringify(this.product_material));
                product_material.material = material;
                this.product_materials.push(product_material);
            },
            appendSemiProduct(product) {
                let semi_product = JSON.parse(JSON.stringify(this.semi_product));
                semi_product.product = product;
                this.semi_products.push(semi_product);
            },
            removeMaterial(line) {
                if (this.product_materials.length > 0)
                    this.product_materials.splice(this.product_materials.indexOf(line), 1);
            },
            next() {
                if (this.active_step == 1)
                    this.$refs["form"].validate(valid => {
                        if (valid) {
                            this.active_step++;
                        }
                    });
                else
                    this.active_step++;
            },
            prev() {
                this.active_step--;
            },
            removeSemiProduct(line) {
                if (this.semi_products.length > 0)
                    this.semi_products.splice(this.semi_products.indexOf(line), 1);
            },
            afterLeave() {
                this.empty()
            }
        }
    }