<?php

namespace App\Listeners\Update;

use App\Assembly;
use App\Events\Models\AssemblyCreateEvent;
use App\Events\Models\CreateSaleEvent;
use App\Events\Update\UpdateOrderEvent;
use App\Order;
use App\OrderCost;
use App\OrderProduct;
use App\Sale;
use Illuminate\Support\Facades\Log;

class UpdateOrderListener
{

    public function __construct()
    {
        //
    }


    public function handle(UpdateOrderEvent $event)
    {
        if ($order = $event->getOrder()){
            $order->update([
                'number'            => $event->getNumber(),
                'datetime'          => $event->getDatetime(),
                'client_id'         => $event->getClientId(),
                'contract_client_id'=> $event->getContractClientId(),
                'state_id'          => $event->getStateId(),
                'priority_id'       => $event->getPriorityId(),
                'begin_date'        => $event->getBeginDate(),
                'end_date'          => $event->getEndDate(),
                'production_type'   => $event->getProductionType(),
            ]);
            $order_product_list = [];
            /**
             * Create order products
             */
            if ($order_products = $event->getProducts()){
                if (is_array($order_products)){
                    foreach ($order_products as $order_product){
                        $item = [
                            'order_id'      => $order->id,
                            'product_id'    => $order_product['product_id'],
                            'price'         => $order_product['price'],
                            'quantity'      => $order_product['quantity'],
                            'currency_id'   => $order_product['currency_id'],
                            'rate'          => $order_product['rate'],
                            'ready'         => 0
                        ];
                        $order_product = OrderProduct::create($item);
                        $item['order_product_id'] = $order_product->id;
                        array_push($order_product_list,$item);
                    }
                }
            }
            /**
             * Create order products
             */
            if ($order_costs = $event->getCosts()){
                if (is_array($order_costs)){
                    foreach ($order_costs as $order_cost){
                        OrderCost::create([
                            'order_id'      => $order->id,
                            'cost_id'       => $order_cost['cost_id'],
                            'amount'        => $order_cost['amount'],
                            'currency_id'   => $order_cost['currency_id'],
                            'rate'          => $order_cost['rate'],
                        ]);
                    }
                }
            }

            $update_production_event = null;

            if ($order->production_type == Order::PRODUCTION){
                if ($sale = Sale::where(Sale::ABLE_ID,$order->id)->where(Sale::ABLE_TYPE,Sale::SALE_TYPE_ORDERS)->first()){
                    $update_production_event = new CreateSaleEvent();
                    $update_production_event->setSale($sale);
                    $update_production_event->setNew(false);
                    $update_production_event->setRequest([
                        'begin_date'    => $order->begin_date,
                        'end_date'      => $order->end_date,
                        'state_id'      => $order->state_id,
                        'priority_id'   => $order->priority_id,
                    ]);
                    $update_production_event->setProducts($order_product_list);
                    $update_production_event->setEmployees($event->getEmployees());
                    $update_production_event->setAdditionalMaterials($event->getAdditionalMaterials());
                    event($update_production_event);
                }
            }elseif($order->production_type == Order::ASSEMBLY){
                if ($assembly = Assembly::where(Assembly::ABLE_ID,$order->id)->where(Assembly::ABLE_TYPE,Assembly::ASSEMBLY_TYPE_ORDERS)->first()){
                    $update_production_event = new AssemblyCreateEvent();
                    $update_production_event->setAssembly($assembly);
                    $update_production_event->setNew(false);
                    $update_production_event->setRequest([
                        'begin_date'    => $order->begin_date,
                        'end_date'      => $order->end_date,
                        'state_id'      => $order->state_id,
                        'priority_id'   => $order->priority_id,
                    ]);
                    $update_production_event->setItems($order_product_list);
                    $update_production_event->setAdditionalMaterials($event->getAdditionalMaterials());
                    $update_production_event->setEmployees($event->getEmployees());
                    event($update_production_event);
                }
            }
        }
    }
}
