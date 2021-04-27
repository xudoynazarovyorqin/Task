<template>
    <div>
        <!-- Modal -->
        <div class="modal fade" id="show_paymentable_items" tabindex="-1" role="dialog" aria-labelledby="show_paymentable_itemsLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Наименование</th>
                                    <th>Ед. изм.</th>
                                    <th>Количество</th>
                                    <th>Цена</th>
                                    <th>Общая цена</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in paymentable_items" :key="index + 'paymentable_items'">
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.measurement }}</td>
                                    <td>{{ item.quantity }}</td>
                                    <td>{{ item.price | formatMoney }}</td>
                                    <td>{{ item.total_price | formatMoney }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->
    </div>
</template>
<script>
  export default {
    props: {
        paymentable_items: { type: Array }
    },
    data() {
      return {
        
      }
    },
  }
</script>