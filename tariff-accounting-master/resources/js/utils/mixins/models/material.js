import warehouseTypes from '@selects/crm-warehouse-type';
import measurements from '@selects/crm-measurement';
import countries from '@selects/crm-country';
import currencies from '@inventory/crm-currency-select';
import price from '@inputs/crm-material-price-input';
import { mapGetters, mapActions } from 'vuex';

export default {
    components: {
        warehouseTypes,
        measurements,
        countries,
        currencies,
        price
    },
    props: ['material'],
    computed: {
        ...mapGetters({
            money_material: 'money_material',
            getForm: 'materials/form',
            money_material: 'money_material',
            rules: "materials/rules",
            model: "materials/model",
            columns: "materials/columns",
            types: "materials/types",
            measurements: 'measurements/inventory',
            currencies: 'currencies/inventory'
        }),
        addMeasurementLabel: function() {
            let m = _.find(this.measurements, ['id', this.form.measurement_id]);
            return m ? '1 ' + m.name + ' = ' : '';
        },
        addMeasurementName: function() {
            let m = _.find(this.measurements, ['id', this.form.additional_measurement_id]);
            return m ? m.name : '';
        }
    },
    mounted() {
        if (this.types && this.types.length === 0) this.loadTypes();
    },
    methods: {
        ...mapActions({
            loadTypes: "materials/getTypes",
            empty: 'materials/empty',
            updateInventory: 'materials/inventory',
        }),
        submit(close = true) {
            this.$refs["form"].validate(valid => {
                if (valid) {
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.listChanged();
                            this.waitingStop();
                            this.updateInventory();
                            if (close) {
                                this.close();
                            }
                        })
                        .catch(err => {
                            this.waitingStop();
                            this.$alert(err);
                        });
                }
            });
        },
        afterLeave() {
            this.empty()
        }
    }
}