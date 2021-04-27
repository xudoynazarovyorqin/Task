<?php

namespace App\Providers;

use App\Application;
use App\ApplicationPart;
use App\ApplicationService;
use App\Assembly;
use App\AssemblyAdditionalMaterial;
use App\AssemblyItem;
use App\AssemblyItemMaterial;
use App\AssemblyItemProduct;
use App\Buy;
use App\BuyMaterial;
use App\BuyReadyProductList;
use App\Client;
use App\ContractClientSuspense;
use App\Cost;
use App\Currency;
use App\DistributionCost;
use App\DistributionTransaction;
use App\NotEnoughMaterial;
use App\NotEnoughProduct;
use App\Observers\ApplicationObserver;
use App\Observers\ApplicationServiceObserver;
use App\Observers\AssemblyAdditionalMaterialObserver;
use App\Observers\AssemblyItemMaterialObserver;
use App\Observers\AssemblyItemObserver;
use App\Observers\AssemblyItemProductObserver;
use App\Observers\BuyMaterialObserver;
use App\Observers\BuyReadyProductListObserver;
use App\Observers\ClientObserver;
use App\Observers\ContractClientSuspenseObserver;
use App\Observers\CurrencyObserver;
use App\Observers\DistributionCostObserver;
use App\Observers\DistributionTransactionObserver;
use App\Observers\NotEnoughMaterialObserver;
use App\Observers\NotEnoughProductObserver;
use App\Observers\OrderCostObserver;
use App\Observers\OrderObserver;
use App\Observers\OrderProductObserver;
use App\Observers\OutputMaterialObserver;
use App\Observers\OutputProductObserver;
use App\Observers\PaymentObserver;
use App\Observers\ProductMaterialObserver;
use App\Observers\RealizationMaterialObserver;
use App\Observers\RealizationObserver;
use App\Observers\ReservationObserver;
use App\Observers\SaleAdditionalMaterialObserver;
use App\Observers\SaleCostObserver;
use App\Observers\SaleReadyProductItemObserver;
use App\Observers\ScoreObserver;
use App\Order;
use App\OrderCost;
use App\OrderProduct;
use App\OutputMaterial;
use App\OutputProduct;
use App\Payment;
use App\ProductMaterial;
use App\Provider;
use App\Realization;
use App\RealizationMaterial;
use App\Reservation;
use App\Role;
use App\Sale;
use App\SaleAdditionalMaterial;
use App\SaleCost;
use App\SaleReadyProductItem;
use App\Score;
use App\User;
use App\Country;
use App\Product;
use App\Permission;
use App\SaleHistory;
use App\SaleProduct;
use App\Transaction;
use App\UserAuthLog;
use App\DefectProduct;
use App\ContractClient;
use App\BuyReadyProduct;
use App\ContractProvider;
use App\SaleReadyProduct;
use App\WarehouseProduct;
use App\WarehouseMaterial;
use App\SaleProductMaterial;
use App\SaleMaterialWarehouse;
use App\Shipment;
use App\ShipmentProduct;
use App\Observers\BuyObserver;
use App\Observers\CostObserver;
use App\Observers\RoleObserver;
use App\Observers\SaleObserver;
use App\Observers\UserObserver;
use App\Observers\CountryObserver;
use App\Observers\ProductObserver;
use App\Observers\PermissionObserver;
use App\Observers\SaleHistoryObserver;
use App\Observers\SaleProductObserver;
use App\Observers\TransactionObserver;
use App\Observers\UserAuthLogObserver;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;
use App\Observers\DefectProductObserver;
use App\Observers\ContractClientObserver;
use App\Observers\BuyReadyProductObserver;
use App\Observers\ContractProviderObserver;
use App\Observers\SaleReadyProductObserver;
use App\Observers\WarehouseProductObserver;
use App\Observers\WarehouseMaterialObserver;
use App\Observers\SaleProductMaterialObserver;
use App\Observers\SaleMaterialWarehouseObserver;
use App\Observers\ShipmentObserver;
use App\Observers\ShipmentProductObserver;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Product::observe(ProductObserver::class);
        Sale::observe(SaleObserver::class);
        SaleProduct::observe(SaleProductObserver::class);
        SaleProductMaterial::observe(SaleProductMaterialObserver::class);
        SaleHistory::observe(SaleHistoryObserver::class);
        SaleMaterialWarehouse::observe(SaleMaterialWarehouseObserver::class);
        User::observe(UserObserver::class);
        Permission::observe(PermissionObserver::class);
        Cost::observe(CostObserver::class);
        Role::observe(RoleObserver::class);
        Country::observe(CountryObserver::class);
        WarehouseMaterial::observe(WarehouseMaterialObserver::class);
        WarehouseProduct::observe(WarehouseProductObserver::class);
        Buy::observe(BuyObserver::class);
        BuyReadyProduct::observe(BuyReadyProductObserver::class);
        ContractProvider::observe(ContractProviderObserver::class);
        ContractClient::observe(ContractClientObserver::class);
        SaleReadyProduct::observe(SaleReadyProductObserver::class);
        Transaction::observe(TransactionObserver::class);
        UserAuthLog::observe(UserAuthLogObserver::class);
        DefectProduct::observe(DefectProductObserver::class);
        Payment::observe(PaymentObserver::class);
        Shipment::observe(ShipmentObserver::class);
        ShipmentProduct::observe(ShipmentProductObserver::class);
        Client::observe(ClientObserver::class);
        SaleAdditionalMaterial::observe(SaleAdditionalMaterialObserver::class);
        SaleCost::observe(SaleCostObserver::class);
        AssemblyItem::observe(AssemblyItemObserver::class);
        AssemblyItemProduct::observe(AssemblyItemProductObserver::class);
        AssemblyItemMaterial::observe(AssemblyItemMaterialObserver::class);
        OutputMaterial::observe(OutputMaterialObserver::class);
        OutputProduct::observe(OutputProductObserver::class);
        Order::observe(OrderObserver::class);
        OrderProduct::observe(OrderProductObserver::class);
        OrderCost::observe(OrderCostObserver::class);
        AssemblyAdditionalMaterial::observe(AssemblyAdditionalMaterialObserver::class);
        NotEnoughProduct::observe(NotEnoughProductObserver::class);
        NotEnoughMaterial::observe(NotEnoughMaterialObserver::class);
        ProductMaterial::observe(ProductMaterialObserver::class);
        BuyReadyProductList::observe(BuyReadyProductListObserver::class);
        BuyMaterial::observe(BuyMaterialObserver::class);
        SaleReadyProductItem::observe(SaleReadyProductItemObserver::class);
        Currency::observe(CurrencyObserver::class);
        Score::observe(ScoreObserver::class);
        Reservation::observe(ReservationObserver::class);
        Realization::observe(RealizationObserver::class);
        RealizationMaterial::observe(RealizationMaterialObserver::class);
        DistributionCost::observe(DistributionCostObserver::class);
        DistributionTransaction::observe(DistributionTransactionObserver::class);
        Application::observe(ApplicationObserver::class);
        ApplicationService::observe(ApplicationServiceObserver::class);
        ContractClientSuspense::observe(ContractClientSuspenseObserver::class);

        Relation::morphMap([
            'orders' => Order::class,

            'buys' => Buy::class,
            'buy_materials' => BuyMaterial::class,

            'buy_ready_products' => BuyReadyProduct::class,
            'buy_ready_product_lists' => BuyReadyProductList::class,

            'clients' => Client::class,
            'providers' => Provider::class,

            'contract_clients' => ContractClient::class,
            'contract_providers' => ContractProvider::class,

            'payments' => Payment::class,
            'costs' => Cost::class,

            'shipments' => Shipment::class,
            'shipment_products' => ShipmentProduct::class,

            'assemblies' => Assembly::class,
            'assembly_items' => AssemblyItem::class,

            'sales' => Sale::class,
            'sale_products' => SaleProduct::class,

            'sale_ready_products' => SaleReadyProduct::class,
            'sale_ready_product_items' => SaleReadyProductItem::class,

            'warehouse_products' => WarehouseProduct::class,
            'warehouse_materials' => WarehouseMaterial::class,

            'transactions' => Transaction::class,

            Reservation::TABLE_NAME => Reservation::class,

            RealizationMaterial::TABLE_NAME => RealizationMaterial::class,

            Application::TABLE_NAME => Application::class,
            ApplicationPart::TABLE_NAME => ApplicationPart::class,
        ]);
    }
}
