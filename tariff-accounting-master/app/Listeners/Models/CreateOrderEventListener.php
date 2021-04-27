<?php

namespace App\Listeners\Models;

use App\Assembly;
use App\Events\Models\AssemblyCreateEvent;
use App\Events\Models\CreateOrderEvent;
use App\Events\Models\CreateSaleEvent;
use App\Order;
use App\OrderCost;
use App\OrderProduct;
use App\Sale;

class CreateOrderEventListener
{

    public function __construct()
    {
        //
    }

    public function handle(CreateOrderEvent $event)
    {
        $order_product_list = [];
        /**
         * Create order
         */
        $order = Order::create([
            'number'            => $event->getNumber(),
            'datetime'          => $event->getDatetime(),
            'client_id'         => $event->getClientId(),
            'contract_client_id'=> $event->getContractClientId(),
            'state_id'          => $event->getStateId(),
            'priority_id'       => $event->getPriorityId(),
            'begin_date'        => $event->getBeginDate(),
            'end_date'          => $event->getEndDate(),
            'production_type'   => $event->getProductionType(),
            'amount'            => 0,
            'paid'              => 0,
        ]);
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

        $create_production_event = null;

        if ($order->production_type == Order::PRODUCTION){
            $create_production_event = new CreateSaleEvent();
            $create_production_event->setRequest([
                'owner'         => Sale::FOR_CLIENT,
                'datetime'      => $order->datetime,
                Sale::ABLE_TYPE => Sale::SALE_TYPE_ORDERS,
                Sale::ABLE_ID   => $order->id,
                'begin_date'    => $order->begin_date,
                'end_date'      => $order->end_date,
                'state_id'      => $order->state_id,
                'priority_id'   => $order->priority_id,
            ]);
            $create_production_event->setProducts($order_product_list);
            $create_production_event->setEmployees($event->getEmployees());
            $create_production_event->setAdditionalMaterials($event->getAdditionalMaterials());
            event($create_production_event);

        }elseif($order->production_type == Order::ASSEMBLY){
            $create_production_event = new AssemblyCreateEvent();
            $create_production_event->setRequest([
                'owner'             => Sale::FOR_CLIENT,
                'datetime'      => $order->datetime,
                Assembly::ABLE_TYPE => Assembly::ASSEMBLY_TYPE_ORDERS,
                Assembly::ABLE_ID   => $order->id,
                'begin_date'    => $order->begin_date,
                'end_date'      => $order->end_date,
                'state_id'      => $order->state_id,
                'priority_id'   => $order->priority_id,
            ]);
            $create_production_event->setItems($order_product_list);
            $create_production_event->setAdditionalMaterials($event->getAdditionalMaterials());
            $create_production_event->setEmployees($event->getEmployees());
            event($create_production_event);
        }
    }
}
