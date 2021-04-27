<?php

namespace App\Providers;

use App\Events\Back\BackMaterialToWarehouseFromRealizationMaterialEvent;
use App\Events\Back\BackProductToWarehouseFromShipmentProductEvent;
use App\Events\Copy\CopyMaterialEvent;
use App\Events\Copy\CopyProductEvent;
use App\Events\GetFromWarehouse\GetMaterialForRealizationMaterialEvent;
use App\Events\GetFromWarehouse\GetProductForShipmentProductEvent;
use App\Events\Models\AssemblyCreateEvent;
use App\Events\Models\BuyEvent;
use App\Events\Models\BuyReadyProductEvent;
use App\Events\Models\CreateBuyNotificationEvent;
use App\Events\Models\CreateBuyReadyProductNotificationEvent;
use App\Events\Models\CreateDefectProductEvent;
use App\Events\Models\CreateDistributionCostEvent;
use App\Events\Models\CreateOrderEvent;
use App\Events\Models\CreateRealizationEvent;
use App\Events\Models\CreateReservationEvent;
use App\Events\Models\CreateSaleEvent;
use App\Events\Models\CreateSaleForAssemblyEvent;
use App\Events\Models\CreateShipmentEvent;
use App\Events\Models\CreateWarehouseMaterialEvent;
use App\Events\Models\CreateWarehouseProductEvent;
use App\Events\Models\CreatePaymentEvent;
use App\Events\Models\CreateTransactionEvent;
use App\Events\Models\SaleReadyProductEvent;
use App\Events\Models\CreateApplicationEvent;
use App\Events\Money\DistributionMoneyEvent;
use App\Events\Output\After\AfterOutputMaterialCreatedEvent;
use App\Events\Output\After\AfterOutputMaterialDeletedEvent;
use App\Events\Output\After\AfterOutputProductCreatedEvent;
use App\Events\Output\After\AfterOutputProductDeletedEvent;
use App\Events\Output\OutputMaterialEvent;
use App\Events\Output\OutputProductEvent;
use App\Events\Receive\ReceiveMaterialToWarehouseFromBuyEvent;
use App\Events\Receive\ReceiveProductToWarehouseFromAssemblyEvent;
use App\Events\Receive\ReceiveProductToWarehouseFromBuyEvent;
use App\Events\Receive\ReceiveProductToWarehouseFromSaleEvent;
use App\Events\Reservation\ReservationMaterialForAssemblyEevent;
use App\Events\Reservation\ReservationMaterialForSaleEevent;
use App\Events\Reservation\ReservationProductForAssemblyEvent;
use App\Events\Reservation\ReservationProductForSaleReadyProductEvent;
use App\Events\Update\UpdateDistributionCostEvent;
use App\Events\Update\UpdateOrderEvent;
use App\Events\Update\UpdateShipmentEvent;
use App\Events\Update\UpdateTransactionEvent;
use App\Events\Update\UpdateApplicationEvent;
use App\Events\WriteOff\WriteOffMaterialWhenAssemblyCreatedEvent;
use App\Events\WriteOff\WriteOffMaterialWhenSaleCreatedEvent;
use App\Events\WriteOff\WriteOffProductWhenAssemblyCreatedEvent;
use App\Events\WriteOff\WriteOffProductWhenSaleReadyProductCreatedEvent;
use App\Listeners\Back\BackMaterialToWarehouseFromRealizationMaterialListener;
use App\Listeners\Back\BackProductToWarehouseFromShipmentProductListener;
use App\Listeners\Copy\CopyMaterialListener;
use App\Listeners\Copy\CopyProductListener;
use App\Listeners\GetFromWarehouse\GetMaterialForRealizationMaterialListener;
use App\Listeners\GetFromWarehouse\GetProductForShipmentProductListener;
use App\Listeners\Models\AssemblyCreateEventListener;
use App\Listeners\Models\BuyListener;
use App\Listeners\Models\BuyReadyProductListener;
use App\Listeners\Models\CreateBuyNotificationEventListener;
use App\Listeners\Models\CreateBuyReadyProductNotificationEventListener;
use App\Listeners\Models\CreateDefectProductListener;
use App\Listeners\Models\CreateDistributionCostListener;
use App\Listeners\Models\CreateOrderEventListener;
use App\Listeners\Models\CreateRealizationListener;
use App\Listeners\Models\CreateReservationListener;
use App\Listeners\Models\CreateSaleEventListener;
use App\Listeners\Models\CreateSaleForAssemblyEventListener;
use App\Listeners\Models\CreateShipmentListener;
use App\Listeners\Models\CreateTransactionListener;
use App\Listeners\Models\CreateWarehouseMaterialListner;
use App\Listeners\Models\CreateWarehouseProductListener;
use App\Listeners\Models\CreatePaymentEventListener;
use App\Listeners\Models\SaleReadyProductListener;
use App\Listeners\Models\CreateApplicationListener;
use App\Listeners\Money\DistributionMoneyListener;
use App\Listeners\Output\After\AfterOutputMaterialCreatedListener;
use App\Listeners\Output\After\AfterOutputMaterialDeletedListener;
use App\Listeners\Output\After\AfterOutputProductCreatedListener;
use App\Listeners\Output\After\AfterOutputProductDeletedListener;
use App\Listeners\Output\OutputMaterialListener;
use App\Listeners\Output\OutputProductListener;
use App\Listeners\Receive\ReceiveMaterialToWarehouseFromBuyListener;
use App\Listeners\Receive\ReceiveProductToWarehouseFromAssemblyListener;
use App\Listeners\Receive\ReceiveProductToWarehouseFromBuyListener;
use App\Listeners\Receive\ReceiveProductToWarehouseFromSaleListener;
use App\Listeners\Reservation\ReservationMaterialForAssemblyListener;
use App\Listeners\Reservation\ReservationMaterialForSaleListener;
use App\Listeners\Reservation\ReservationProductForAssemblyListener;
use App\Listeners\Reservation\ReservationProductForSaleReadyProductListener;
use App\Listeners\Update\UpdateOrderListener;
use App\Listeners\Update\UpdateShipmentListener;
use App\Listeners\Update\UpdateTransactionListener;
use App\Listeners\Update\UpdateDistributionCostListener;
use App\Listeners\Update\UpdateApplicationListener;
use App\Listeners\WriteOff\WriteOffMaterialWhenAssemblyCreatedListener;
use App\Listeners\WriteOff\WriteOffMaterialWhenSaleCreatedListener;
use App\Listeners\WriteOff\WriteOffProductWhenAssemblyCreatedListener;
use App\Listeners\WriteOff\WriteOffProductWhenSaleReadyProductCreatedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        /*
         * Get products from warehouse
         */
        GetMaterialForRealizationMaterialEvent::class => [
          GetMaterialForRealizationMaterialListener::class
        ],
        GetProductForShipmentProductEvent::class => [
          GetProductForShipmentProductListener::class
        ],

        /*
         * Models
         */
        CreateOrderEvent::class => [
            CreateOrderEventListener::class
        ],
        AssemblyCreateEvent::class => [
            AssemblyCreateEventListener::class
        ],
        CreateBuyNotificationEvent::class => [
            CreateBuyNotificationEventListener::class
        ],
        CreateBuyReadyProductNotificationEvent::class => [
            CreateBuyReadyProductNotificationEventListener::class
        ],
        CreateSaleForAssemblyEvent::class => [
            CreateSaleForAssemblyEventListener::class
        ],
        CreateSaleEvent::class => [
            CreateSaleEventListener::class
        ],
        CreateDefectProductEvent::class => [
            CreateDefectProductListener::class
        ],
        CreateWarehouseProductEvent::class => [
            CreateWarehouseProductListener::class
        ],
        CreatePaymentEvent::class => [
            CreatePaymentEventListener::class
        ],
        CreateWarehouseMaterialEvent::class => [
            CreateWarehouseMaterialListner::class
        ],
        SaleReadyProductEvent::class => [
          SaleReadyProductListener::class
        ],
        BuyEvent::class => [
            BuyListener::class
        ],
        BuyReadyProductEvent::class => [
          BuyReadyProductListener::class
        ],
        CreateTransactionEvent::class => [
          CreateTransactionListener::class
        ],
        CreateReservationEvent::class => [
            CreateReservationListener::class
        ],
        CreateShipmentEvent::class => [
            CreateShipmentListener::class
        ],
        CreateRealizationEvent::class => [
          CreateRealizationListener::class
        ],
        CreateDistributionCostEvent::class => [
          CreateDistributionCostListener::class
        ],
        CreateApplicationEvent::class => [
            CreateApplicationListener::class
        ],
        \App\Events\Models\Mudofa\CreateTransactionEvent::class => [
            \App\Listeners\Models\Mudofa\CreateTransactionListener::class
        ],

        /**
         * Back
         */
        BackMaterialToWarehouseFromRealizationMaterialEvent::class => [
          BackMaterialToWarehouseFromRealizationMaterialListener::class
        ],
        BackProductToWarehouseFromShipmentProductEvent::class => [
          BackProductToWarehouseFromShipmentProductListener::class
        ],

        /**
         * Update
         */
        UpdateOrderEvent::class => [
            UpdateOrderListener::class
        ],
        UpdateTransactionEvent::class => [
            UpdateTransactionListener::class
        ],
        UpdateShipmentEvent::class => [
          UpdateShipmentListener::class
        ],
        UpdateDistributionCostEvent::class => [
            UpdateDistributionCostListener::class
        ],
        UpdateApplicationEvent::class => [
            UpdateApplicationListener::class
        ],
        \App\Events\Update\Mudofa\UpdateTransactionEvent::class => [
            \App\Listeners\Update\Mudofa\UpdateTransactionListener::class
        ],

        /**
         * Copy
         */
        CopyProductEvent::class => [
            CopyProductListener::class
        ],
        CopyMaterialEvent::class => [
            CopyMaterialListener::class
        ],

        /**
         * Receive to warehouse
         */
        ReceiveProductToWarehouseFromBuyEvent::class => [
            ReceiveProductToWarehouseFromBuyListener::class
        ],
        ReceiveProductToWarehouseFromAssemblyEvent::class => [
            ReceiveProductToWarehouseFromAssemblyListener::class
        ],
        ReceiveProductToWarehouseFromSaleEvent::class => [
            ReceiveProductToWarehouseFromSaleListener::class
        ],
        ReceiveMaterialToWarehouseFromBuyEvent::class => [
            ReceiveMaterialToWarehouseFromBuyListener::class
        ],

        /**
         * Money
         */
        DistributionMoneyEvent::class => [
            DistributionMoneyListener::class
        ],

        \App\Events\Money\Mudofa\DistributionMoneyEvent::class => [
            \App\Listeners\Money\Mudofa\DistributionMoneyListener::class
        ],

        /**
         * Reservation
         */
        ReservationMaterialForSaleEevent::class => [
            ReservationMaterialForSaleListener::class
        ],
        ReservationMaterialForAssemblyEevent::class => [
           ReservationMaterialForAssemblyListener::class
        ],
        ReservationProductForAssemblyEvent::class => [
            ReservationProductForAssemblyListener::class
        ],
        ReservationProductForSaleReadyProductEvent::class => [
          ReservationProductForSaleReadyProductListener::class
        ],

        /**
         * Output
         */
        OutputMaterialEvent::class => [
            OutputMaterialListener::class
        ],
        OutputProductEvent::class => [
            OutputProductListener::class
        ],

        /**
         * Output/After
         */
        AfterOutputMaterialCreatedEvent::class => [
            AfterOutputMaterialCreatedListener::class
        ],
        AfterOutputMaterialDeletedEvent::class => [
            AfterOutputMaterialDeletedListener::class
        ],
        AfterOutputProductCreatedEvent::class => [
            AfterOutputProductCreatedListener::class
        ],
        AfterOutputProductDeletedEvent::class => [
            AfterOutputProductDeletedListener::class
        ],

        /**
         * Write off
         */
        WriteOffProductWhenAssemblyCreatedEvent::class => [
            WriteOffProductWhenAssemblyCreatedListener::class
        ],
        WriteOffMaterialWhenSaleCreatedEvent::class => [
            WriteOffMaterialWhenSaleCreatedListener::class
        ],
        WriteOffMaterialWhenAssemblyCreatedEvent::class => [
            WriteOffMaterialWhenAssemblyCreatedListener::class
        ],
        WriteOffProductWhenSaleReadyProductCreatedEvent::class => [
            WriteOffProductWhenSaleReadyProductCreatedListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
