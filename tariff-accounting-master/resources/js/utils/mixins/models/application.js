import clients from '@selects/crm-client';
import contracts from '@selects/crm-contract-client';
import states from '@selects/crm-state';
import services from '@selects/crm-service';
import districts from '@selects/crm-district';
import quarters from '@selects/crm-quarter';
import amount from '@inputs/crm-amount-input';
import { mapGetters, mapActions } from "vuex";

export default {
    components: {
        clients,
        contracts,
        states,
        services,
        districts,
        quarters,
        amount,
    },
    data() {
        return {
            application_services: [],
        }
    },
    computed: {
        ...mapGetters({
            model: "applications/model",
            getForm: 'applications/form',
            columns: "applications/columns",
            lastId: 'applications/lastId',
            application_service: "applications/application_service",
            rules: "applications/rules",
            old_application_services: 'applications/application_services',
            clients: 'clients/inventory',
        }),
        totalAmount: function() {
            return _.sumBy([...this.old_application_services, ...this.application_services], function(o) {
                return _.round((+o.price), 2);
            })
        }
    },
    methods: {
        ...mapActions({
            clientObjectData: "clients/getObjectData",
            empty: "applications/empty",
        }),
        submit(close = true) {
            /**
             * Get application services
             */
            this.form["application_services"] = this.application_services.map((item) => {
                return { service_id: item.service.id, price: item.price };
            });

            this.$refs["form"].validate(valid => {
                if (valid && this.customValidation()) {
                    this.waiting = true;
                    this.save(this.form)
                        .then(res => {
                            this.$alert(res);
                            this.listChanged();
                            this.waitingStop();
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

        loadClientObjectData() {
            if( this.form.client_id ) {
                this.clientObjectData(this.form.client_id)
                .then(res => {
                    let current_client = res.data.client;

                    if( current_client ) {
                        this.form.object_name = current_client.object_name;
                        this.form.district_id = current_client.district_id;
                        this.form.quarter_id = current_client.quarter_id;
                        this.form.object_street = current_client.object_street;
                        this.form.object_home = current_client.object_home;
                        this.form.object_corps = current_client.object_corps;
                        this.form.object_flat = current_client.object_flat;
                    }
                    else {
                        this.form.object_name = '';
                        this.form.district_id = null;
                        this.form.quarter_id = null;
                        this.form.object_street = '';
                        this.form.object_home = '';
                        this.form.object_corps = '';
                        this.form.object_flat = '';
                    }
                })
                .catch(err => {
                    this.$alert(err);
                });
            }
        },

        appendService(service) {
            let application_service = JSON.parse(JSON.stringify(this.application_service));
            application_service.service = service;
            application_service.price = service.price;
            this.application_services.push(application_service);
        },

        removeService(line) {
            if (this.application_services.length > 0) this.application_services.splice(this.application_services.indexOf(line), 1);
        },

        customValidation() {
            if ( !this.is_edit && (!_.size(this.application_services)) ) {
                this.$message({
                    message: this.$t('message.service_validation_error'),
                    type: 'warning'
                });
                return false;
            }
            return true;
        },
        afterLeave() {
          if (_.isFunction(this.empty)) {
              this.empty();
          }
        }
    },
};
