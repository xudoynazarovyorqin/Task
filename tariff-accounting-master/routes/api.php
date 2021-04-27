<?php


use App\Buy;
use App\WarehouseMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/test',function (Buy $buy){
    $code = md5("MEX MASH MCHJ" . now()->toDateString());
    return $code;
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'namespace' => 'Api',
    //'middleware' => 'cors'
],function (){
    Route::get('/create-domain',function (Buy $buy){
        $code = md5( (\request()->get('name') . now()->toDateString()));
        return (substr($code,rand(1,15),rand(6,15)) . str_slug(\request()->get('name')));
    });

    Route::group([
        'name'   => 'auth',
        'prefix' => 'auth',
    ],function (){
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::get('refresh', 'AuthController@refresh');
    });
    Route::group([
        'name'   => 'users',
    ],function (){
        Route::group([
            'prefix'   => 'users',
        ],function (){
            Route::get('inventory','UserController@inventory');
        });
        Route::post('users/validation', 'UserController@validation');
        Route::apiResource('users', 'UserController');
    });

    /* Permission's routes */
    Route::group([
        'name'   => 'permissions',
    ],function (){
        Route::group([
            'prefix'   => 'permissions',
        ],function (){
            Route::get('inventory','PermissionController@inventory');
            Route::get('parents', 'PermissionController@parents');
        });
        Route::apiResource('permissions', 'PermissionController');
    });
    /* Permission's routes */

    /* Role's routes */
    Route::group([
        'name'   => 'roles',
    ],function (){
        Route::group([
            'prefix'   => 'roles',
        ],function (){
            Route::get('inventory','RoleController@inventory');
        });
        Route::apiResource('roles', 'RoleController');
    });
    /* Role's routes */

    /* Material's routes */
    Route::group([
        'name'   => 'materials',
    ],function (){
        Route::group([
            'prefix'   => 'materials',
        ],function () {
            Route::post('copy','MaterialController@copy');
            Route::get('inventory','MaterialController@inventory');
            Route::get('get/types', 'MaterialController@getTypes')->name('materials.types');
            Route::get('get/reworking/materials', 'MaterialController@getReworkingMaterials');
        });
        Route::apiResource('materials', 'MaterialController');
    });
    /* Material's routes */

    /* Cost's routes */
    Route::group([
        'name'   => 'costs',
    ],function (){
        Route::group([
            'prefix'   => 'costs',
        ],function (){
            Route::get('inventory','CostController@inventory');
            Route::get('chart','CostController@chart');
        });
        Route::apiResource('costs', 'CostController');
    });
    /* Cost's routes */

    /* Measurement's routes */
    Route::group([
        'name'   => 'measurements',
    ],function (){
        Route::group([
            'prefix'   => 'measurements',
        ],function () {
            Route::get('inventory','MeasurementController@inventory');
        });
        Route::apiResource('measurements', 'MeasurementController');
    });
    /* Measurement's routes */

    /* Product's routes */
    Route::group([
        'name'   => 'products',
    ],function (){
        Route::group([
            'prefix'   => 'products',
        ],function (){
            Route::get('remainder','ProductController@remainder');
            Route::post('copy','ProductController@copy');
            Route::get('inventory','ProductController@inventory');
        });
        Route::apiResource('products', 'ProductController');
    });
    /* Product's routes */

    /* State's routes */
    Route::group([
        'name'   => 'states',
    ],function (){
        Route::group([
            'prefix'   => 'states',
        ],function (){
            Route::get('inventory','StateController@inventory');
        });

        Route::apiResource('states', 'StateController');
    });
    /* State's routes */

    /*Warehouse's routes */
    Route::group([
        'name'   => 'warehouses',
    ],function (){
        Route::group([
            'prefix'   => 'warehouses',
        ],function (){
            Route::get('inventory','WarehouseController@inventory');
        });
        Route::apiResource('warehouses', 'WarehouseController');
        Route::get('/all/types','WarehouseController@getTypes');
    });
    /* Warehouse's routes */

    /*Order routes */
    Route::group([
        'name'   => 'orders',
    ],function (){
        Route::group([
            'prefix'   => 'orders',
        ],function () {
            Route::get('getLastId','OrderController@getLastId');
            Route::get('chart','OrderController@chart');
            Route::get('inventory','OrderController@inventory');
            Route::post('checkDate','OrderController@checkDate');
            Route::post('deleteAdditionalMaterial','OrderController@deleteAdditionalMaterial');
            Route::post('deleteProduct','OrderController@deleteProduct');
            Route::post('deleteCost','OrderController@deleteCost');
            Route::get('/{order}/edit','OrderController@edit');
            Route::get('print','OrderController@print');
            Route::get('comments','OrderController@comments');
            Route::post('comments','OrderController@commentStore');
            Route::post('multipleDelete','OrderController@multipleDelete');
        });
        Route::apiResource('orders', 'OrderController');
    });
    /* end order routes */

     /*Sale's routes */
     Route::group([
        'name'   => 'sales',
    ],function (){
         Route::group([
            'prefix'   => 'sales',
            ],function () {
             Route::get('items', 'SaleController@items');
             Route::get('getLastId', 'SaleController@getLastId');
             Route::post('report','SaleController@report');
             Route::post('report-show','SaleController@reportShow');
             Route::get('/{id}/edit','SaleController@edit');
             Route::post('deleteProduct','SaleController@deleteProduct');
             Route::post('deleteAdditionalMaterial','SaleController@deleteAdditionalMaterial');
             Route::post('multipleDelete','SaleController@multipleDelete');
             Route::get('print','SaleController@print');
             Route::post('getSaleProducts','SaleController@getSaleProducts');
             Route::post('getDefectProducts','SaleController@getDefectProducts');
             Route::post('getManufacturedProducts','SaleController@getManufacturedProducts');
             Route::get('printSaleMaterialWarehouse','SaleController@printSaleMaterialWarehouse');
             Route::post('backMaterialsToWarehouse','SaleController@backMaterialsToWarehouse');
             Route::get('comments','SaleController@comments');
             Route::post('comments','SaleController@commentStore');
             Route::post('histories/store','SaleController@historyStore');
             Route::post('manufacturedStore','SaleController@manufacturedStore');
             Route::post('defectStore','SaleController@defectStore');
             Route::post('deleteDefectProduct','SaleController@deleteDefectProduct');

         });
         Route::apiResource('sales', 'SaleController');
     });
    /* Sale's routes */

    /*Sale's routes */
    Route::group([
        'name'   => 'assembly',
    ],function (){
        Route::group([
            'prefix' => 'assembly'
        ],function () {
            Route::get('/{id}/edit','AssemblyController@edit');
            Route::get('getLastId','AssemblyController@getLastId');
            Route::post('deleteProduct','AssemblyController@deleteProduct');
            Route::post('deleteAdditionalMaterial','AssemblyController@deleteAdditionalMaterial');
            Route::post('getAssemblyItems','AssemblyController@getAssemblyItems');
            Route::post('getManufacturedProducts','AssemblyController@getManufacturedProducts');
            Route::post('getDefectProducts','AssemblyController@getDefectProducts');
            Route::post('report','AssemblyController@report');
            Route::post('report-show', 'AssemblyController@reportShow');
            Route::get('print','AssemblyController@print');
            Route::get('comments','AssemblyController@comments');
            Route::post('comments','AssemblyController@commentStore');
            Route::post('multipleDelete','AssemblyController@multipleDelete');
            Route::post('manufacturedStore','AssemblyController@manufacturedStore');
            Route::post('defectStore','AssemblyController@defectStore');
            Route::post('deleteDefectProduct','AssemblyController@deleteDefectProduct');
        });
        Route::apiResource('assembly', 'AssemblyController');
    });
    /* Sale's routes */

    /*Defect product's routes */
     Route::group([
        'name'   => 'defect_products',
    ],function (){
         Route::group([
            'prefix'   => 'defect_products',
         ],function (){
             Route::get('history/{product_id}','DefectProductController@history');
             Route::post('create/from/shipment','DefectProductController@createDefectFromShipment');
             Route::post('get/shipment','DefectProductController@getShipment');
         });
         Route::apiResource('defect_products', 'DefectProductController');
    });
    /* Defect product's routes */

    /*SaleReadyProduct's routes */
    Route::group([
        'name'   => 'saleReadyProducts',
    ],function (){
        Route::group([
            'prefix'   => 'saleReadyProducts',
        ],function () {
            Route::get('getLastId','SaleReadyProductController@getLastId');
            Route::get('inventory','SaleReadyProductController@inventory');
            Route::get('print','SaleReadyProductController@print');
            Route::post('multipleDelete','SaleReadyProductController@multipleDelete');
            Route::get('chart', 'SaleReadyProductController@chart');
            Route::post('deleteProduct','SaleReadyProductController@deleteProduct');
        });
        Route::apiResource('saleReadyProducts', 'SaleReadyProductController');
    });
    /* SaleReadyProduct's routes */

    /* Buy's routes */
    Route::group([
        'name'   => 'buys',
    ],function (){
        Route::group([
            'prefix'   => 'buys',
        ],function (){
            Route::get('warehouse_materials','BuyController@warehouse_materials');
            Route::get('items','BuyController@items');
            Route::get('inventory','BuyController@inventory');
            Route::get('print','BuyController@print');
            Route::post('multipleDelete','BuyController@multipleDelete');
            Route::get('chart', 'BuyController@chart');
            Route::get('getLastId', 'BuyController@getLastId');
            Route::post('receive','BuyController@receive');
            Route::get('get/statuses', 'BuyController@getStatuses')->name('buys.statuses');
        });
        Route::post('buy/material/delete','BuyController@deleteMaterial');
        Route::apiResource('buys', 'BuyController');
    });
    /* Buy's routes */

    /* BuyReadyProduct's routes */
    Route::group([
        'name'   => 'buyReadyProducts',
    ],function (){
        Route::group([
            'prefix'   => 'buyReadyProducts',
        ],function (){
            Route::get('warehouse_products','BuyReadyProductController@warehouse_products');
            Route::get('items','BuyReadyProductController@items');
            Route::get('inventory','BuyReadyProductController@inventory');
            Route::get('print','BuyReadyProductController@print');
            Route::get('chart', 'BuyReadyProductController@chart');
            Route::post('multipleDelete','BuyReadyProductController@multipleDelete');
            Route::get('getLastId', 'BuyReadyProductController@getLastId');
            Route::get('get/statuses', 'BuyReadyProductController@getStatuses')->name('buyReadyProducts.statuses');
            Route::post('receive','BuyReadyProductController@receive');
            Route::post('product/delete','BuyReadyProductController@deleteProduct');
        });
        Route::apiResource('buyReadyProducts', 'BuyReadyProductController');
    });
    /* BuyReadyProduct's routes */

    /* WarehouseMaterial's routes */
    Route::group([
        'name'   => 'warehouseMaterials',
    ],function (){
        Route::group([
            'prefix'   => 'warehouseMaterials',
        ],function (){
            Route::post('coming/materials','WarehouseMaterialController@comingMaterials');
            Route::post('create/coming','WarehouseMaterialController@createComing');
        });
        Route::apiResource('warehouseMaterials', 'WarehouseMaterialController');
    });
    /* WarehouseMaterial's routes */

    /* WarehouseProduct's routes */
    Route::group([
        'name'   => 'warehouseProducts',
    ],function (){
        Route::group([
            'prefix'   => 'warehouseProducts',
        ],function (){
            Route::post('coming/products','WarehouseProductController@comingProducts');
        });
        Route::apiResource('warehouseProducts', 'WarehouseProductController');
    });
    /* WarehouseProduct's routes */

    /* Client's routes */
    Route::group([
        'name'   => 'clients',
    ],function (){
        Route::group([
            'prefix'   => 'clients',
        ],function (){
            Route::get('inventory','ClientController@inventory');
            Route::get('/get/object/data/{client_id}','ClientController@getObjectData');
            Route::get('get/types', 'ClientController@getTypes')->name('clients.types');
        });
        Route::apiResource('clients', 'ClientController');
    });
    /* Client's routes */


    /* Country's routes */
    Route::group([
        'name'   => 'countries',
    ],function (){
        Route::group([
            'prefix'   => 'countries',
        ],function (){
            Route::get('inventory','CountryController@inventory');
        });
        Route::apiResource('countries', 'CountryController');
    });
    /* Country's routes */

    /* Priority's routes */
    Route::group([
        'name'   => 'priority',
    ],function (){
        Route::group([
            'prefix'   => 'priority',
        ],function (){
            Route::get('inventory','PriorityController@inventory');
        });
        Route::apiResource('priority', 'PriorityController');
    });
    /* Priority's routes */

    /* Categories's routes */
    Route::group([
        'name'   => 'categories',
    ],function (){
        Route::group([
            'prefix'   => 'categories',
        ],function (){
            Route::get('inventory','CategoryController@inventory');
        });
        Route::apiResource('categories', 'CategoryController');
    });
    /* Categories's routes */

    /* Provider's routes */
    Route::group([
        'name'   => 'providers',
    ],function (){
        Route::group([
            'prefix'   => 'providers',
        ],function (){
            Route::get('inventory','ProviderController@inventory');
            Route::get('get/types', 'ProviderController@getTypes')->name('providers.types');
        });
        Route::apiResource('providers', 'ProviderController');
    });
    /* Provider's routes */

    /* WarehouseType's routes */
    Route::group([
        'name'   => 'warehouseTypes',
    ],function (){
        Route::group([
            'prefix'   => 'warehouseTypes',
        ],function (){
            Route::get('inventory','WarehouseTypeController@inventory');
        });
        Route::apiResource('warehouseTypes', 'WarehouseTypeController');
    });
    /* WarehouseType's routes */

    /* LevelController's routes */
    Route::group([
        'name'   => 'levels',
    ],function (){
        Route::group([
            'prefix'   => 'levels',
        ],function (){
            Route::get('inventory','LevelController@inventory');
        });
        Route::apiResource('levels', 'LevelController');
    });
    /* LevelController's routes */

    /* ContractClients's routes */
    Route::group([
        'name'   => 'contractClients',
    ],function (){
        Route::group([
            'prefix'   => 'contractClients',
        ],function (){
            Route::get('inventory', 'ContractClientController@inventory');
            Route::get('print','ContractClientController@print');
            Route::post('delete/suspense','ContractClientController@deleteSuspense');
        });
        Route::apiResource('contractClients', 'ContractClientController');
    });
    /* ContractClients's routes */

    /* ContractProviders's routes */
    Route::group([
        'name'   => 'contractProviders',
    ],function (){
        Route::group([
            'prefix'   => 'contractProviders',
        ],function () {
            Route::get('inventory', 'ContractProviderController@inventory');
        });
        Route::apiResource('contractProviders', 'ContractProviderController');
    });
    /* ContractProviders's routes */

    /* Reports's routes */
    Route::group([
        'prefix'   => 'report',
    ],function () {
        Route::get('materials', 'ReportController@reportMaterials')->name('report.materials');
        Route::get('export/excel/materials', 'ReportController@exportExcelMaterials')->name('excel.materials');
        Route::get('products', 'ReportController@reportProducts')->name('report.products');
        Route::get('export/excel/products', 'ReportController@exportExcelProducts')->name('excel.products');
    });
    /* Reports's routes */

    /* BuyNotification's routes */
    Route::group([
        'name'   => 'buyNotifications',
    ],function () {
        Route::group([
            'prefix'   => 'buyNotifications',
        ],function () {
            Route::post('cancel','BuyNotificationController@cancel');
            Route::get('count','BuyNotificationController@count');
        });
        Route::apiResource('buyNotifications', 'BuyNotificationController');
    });
    /* BuyNotification's routes */

    /* BuyReadyProductNotification's routes */
    Route::group([
        'name'   => 'buyReadyProductNotifications',
    ],function () {
        Route::group([
            'prefix'   => 'buyReadyProductNotifications',
        ],function () {
            Route::get('count','BuyReadyProductNotificationController@count');
            Route::post('cancel','BuyReadyProductNotificationController@cancel');
        });
        Route::apiResource('buyReadyProductNotifications', 'BuyReadyProductNotificationController');
    });
    /* BuyReadyProductNotification's routes */

    /* SettingController's routes */
    Route::apiResource('settings', 'SettingController');
    /* SettingController's routes */


    /* Audit's routes */
    Route::group([
        'name'   => 'audits',
    ],function (){
        Route::get('audits/auditList', 'AuditController@auditList')->name('audits.auditList');
        Route::apiResource('audits', 'AuditController');
        Route::get('download/change/values/{audit_id}', 'AuditController@downloadChangeValues')->name('audit.changeValues');
    });
    /* Audit's routes */

    /* Currency's routes */
    Route::group([
        'name'   => 'currencies',
    ],function (){
        Route::group([
            'prefix'   => 'currencies',
        ],function (){
           Route::get('inventory','CurrencyController@inventory');
        });
        Route::apiResource('currencies', 'CurrencyController');
    });
    /* Currency's routes */

    /* PaymentType's routes */
    Route::group([
        'name'   => 'paymentTypes',
    ],function (){
        Route::group([
            'prefix'   => 'paymentTypes',
        ],function (){
            Route::get('inventory','PaymentTypeController@inventory');
        });
        Route::apiResource('paymentTypes', 'PaymentTypeController');
    });
    /* PaymentType's routes */

    /* Transaction's routes */
//    Route::group([
//        'name'   => 'transactions',
//    ],function (){
//        Route::group([
//            'prefix'   => 'transactions',
//        ],function (){
//            Route::post('multipleDelete','TransactionController@multipleDelete');
//            Route::get('incomingDocuments','TransactionController@getIncomingDocuments');
//            Route::get('outgoingDocuments','TransactionController@getOutgoingDocuments');
//            Route::get('getLastId','TransactionController@getLastId');
//            Route::get('createPayment','TransactionController@createpayment');
//            Route::post('multipleDelete','TransactionController@multipleDelete');
//        });
//        Route::apiResource('transactions', 'TransactionController');
//    });
    /* Payment's routes */

     /* UserAuthLog's routes */
    Route::group([
        'name'   => 'userAuthLogs',
    ],function (){
        Route::apiResource('userAuthLogs', 'UserAuthLogController');
    });
    /* UserAuthLog's routes */

    /* EmployeeGroup's routes */
    Route::group([
        'name'   => 'employeeGroups',
    ],function (){
        Route::apiResource('employeeGroups', 'EmployeeGroupController');
    });
    /* EmployeeGroup's routes */

    /* SaleProducedWarehouse's routes */
    Route::group([
        'name'   => 'saleProducedWarehouses',
    ],function (){
        Route::apiResource('saleProducedWarehouses', 'SaleProducedWarehouseController');
    });
    /* SaleProducedWarehouse's routes */

    /* CostTransaction's routes */
    Route::group([
        'name'   => 'costTransactions',
    ],function (){
        Route::apiResource('costTransactions', 'CostTransactionController');
    });
    /* CostTransaction's routes */

    /* Shipment's routes */
    Route::group([
        'name'   => 'shipments',
    ],function (){
        Route::group([
            'prefix'   => 'shipments',
        ],function (){
            Route::get('items', 'ShipmentController@items');
            Route::get('getLastId', 'ShipmentController@getLastId');
            Route::get('reservations','ShipmentController@getReservations');
            Route::get('documents','ShipmentController@getDocuments');
            Route::get('print','ShipmentController@print');
            Route::post('multipleDelete','ShipmentController@multipleDelete');
        });
        Route::apiResource('shipments', 'ShipmentController');
    });
    /* Shipment's routes */

    /* Reason's routes */
    Route::group([
        'name'   => 'reasons',
    ],function (){
        Route::apiResource('reasons', 'ReasonController');
    });
    /* Reason's routes */

    /* Statistic's routes */
    Route::group([
        'name'   => 'statistics',
        'prefix' => 'statistics',
    ],function (){
        Route::get('product','StatisticController@productStatistic');
        Route::get('material','StatisticController@materialStatistic');
    });
    /* Statistic's routes */

    /**
     * Scores api
     */
    Route::group([
        'name' => 'scores',
    ],function (){
        Route::group([
            'prefix' => 'scores',
        ],function () {
            Route::get('inventory','ScoreController@inventory');
        });
        Route::apiResource('scores','ScoreController');
    });

    /**
     * DistributionCost api
     */
    Route::group([
        'name' => 'distributionCosts',
    ],function (){
        Route::group([
            'prefix' => 'distributionCosts',
        ],function () {
            Route::get('warehouseProducts','DistributionCostController@getWarehouseProducts');
            Route::get('warehouseMaterials','DistributionCostController@getWarehouseMaterials');
            Route::get('costTransactions','DistributionCostController@getCostTransactions');
            Route::post('multipleDelete','DistributionCostController@multipleDelete');
            Route::get('getLastId', 'DistributionCostController@getLastId');
        });
        Route::apiResource('distributionCosts','DistributionCostController');
    });

    /**
     * Realization api
     */
    Route::group([
        'name' => 'realizations',
    ],function (){
        Route::group([
            'prefix' => 'realizations',
        ],function () {
            Route::get('getLastId', 'RealizationController@getLastId');
            Route::get('items','RealizationController@items');
            Route::get('reservations','RealizationController@getReservations');
            Route::get('documents','RealizationController@getDocuments');
            Route::post('multipleDelete','RealizationController@multipleDelete');
        });
        Route::apiResource('realizations','RealizationController');
    });

    /**
     * Service's routes
     */
    Route::group([
        'name'   => 'services',
    ],function (){
        Route::group([
            'prefix'   => 'services',
        ],function () {
            Route::get('inventory','ServiceController@inventory');
        });
        Route::apiResource('services', 'ServiceController');
    });

    /**
     * Application's routes
     */
    Route::group([
        'name'   => 'applications',
    ],function (){
        Route::group([
            'prefix'   => 'applications',
        ],function () {
            Route::get('getLastId','ApplicationController@getLastId');
            Route::get('chart','ApplicationController@chart');
            Route::post('deleteService','ApplicationController@deleteService');
            Route::get('/{application}/edit','ApplicationController@edit');
            Route::get('/{application}/get/audits','ApplicationController@getAudits');
            Route::get('/{application}/get/parts','ApplicationController@getParts');
            Route::get('/{application}/get/transactions','ApplicationController@getTransactions');
            Route::get('print','ApplicationController@print');
            Route::get('/summary/report','ApplicationController@summaryReport');
            Route::get('/debit/list/excel','ApplicationController@debitListExcel');
            Route::post('multipleDelete','ApplicationController@multipleDelete');
        });
        Route::apiResource('applications', 'ApplicationController');
    });

    /**
     * District's routes
     */
    Route::group([
        'name'   => 'districts',
    ],function (){
        Route::group([
            'prefix'   => 'districts',
        ],function () {
            Route::get('inventory','DistrictController@inventory');
        });
        Route::apiResource('districts', 'DistrictController');
    });

    /**
     * Quarter's routes
     */
    Route::group([
        'name'   => 'quarters',
    ],function (){
        Route::group([
            'prefix'   => 'quarters',
        ],function () {
            Route::get('inventory','QuarterController@inventory');
        });
        Route::apiResource('quarters', 'QuarterController');
    });


    /* Mudofa routes */
    Route::group([
        'namespace' => 'Mudofa',
    ],function (){
        /* Transactions routes */
        Route::group([
            'name'   => 'transactions',
        ],function (){
            Route::group([
                'prefix'   => 'transactions',
            ],function (){
                Route::post('multipleDelete','TransactionController@multipleDelete');
                Route::get('incomingDocuments','TransactionController@getIncomingDocuments');
                Route::get('outgoingDocuments','TransactionController@getOutgoingDocuments');
                Route::get('getLastId','TransactionController@getLastId');
                Route::get('createPayment','TransactionController@createpayment');
                Route::get('get/amounts/and/counts','TransactionController@getAmountsAndCounts');
                Route::post('multipleDelete','TransactionController@multipleDelete');
                Route::post('get/application/document','TransactionController@getApplicationDocument');
                Route::post('save/return/transaction','TransactionController@saveReturnTransaction');
                Route::post('create/transaction/from/file','TransactionController@createTransactionFromFile');
            });
            Route::apiResource('transactions', 'TransactionController');
        });
    });

    /* ApplicationPart's routes */
    Route::group([
        'name'   => 'applicationParts',
    ],function (){
        Route::group([
            'prefix'   => 'applicationParts',
        ],function (){

        });
        Route::apiResource('applicationParts', 'ApplicationPartController');
    });

    /* Mudofa routes */

});


Route::group([
    'namespace' => 'Stock',
    'prefix' => 'stock',
    'middleware' => 'cors',
],function (){

    /* Auth */
    Route::group([
        'name'   => 'auth',
        'prefix' => 'auth',
    ],function (){
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::post('user', 'AuthController@user');
    });
    /* Auth */

    /* Dashboard */
    Route::group([
        'name'   => 'dashboards',
        'prefix' => 'dashboards',
    ],function (){
        Route::get('index', 'DashboardController@index');
    });
    /* Dashboard */

    /* Sale */
    Route::group([
        'name'   => 'sales',
    ],function (){
        Route::group([
            'prefix'   => 'sales',
        ],function () {
            Route::get('printSaleMaterialWarehouse','SaleController@printSaleMaterialWarehouse');
            Route::get('print/barcode','SaleController@printBarcode');
            Route::post('produced/warehouse','SaleController@saleProducedWarehouse');
            Route::post('defect/create','SaleController@saleDefectCreate');
            Route::post('report/materials', 'SaleController@reportMaterial');
        });
        Route::apiResource('sales', 'SaleController');
    });
    /* Sale */

    /* Assembly */
    Route::group([
        'name'   => 'assemblies',
    ],function (){
        Route::group([
            'prefix'   => 'assemblies',
        ],function () {
            Route::get('printReport','AssemblyController@printReport');
            Route::get('print/barcode','AssemblyController@printBarcode');
            Route::post('produced/warehouse','AssemblyController@assemblyProducedWarehouse');
            Route::post('defect/create','AssemblyController@assemblyDefectCreate');
            Route::post('report', 'AssemblyController@report');
        });
        Route::apiResource('assemblies', 'AssemblyController');
    });
    /* Assembly */

    /* Warehouse */
    Route::group([
        'name'   => 'warehouses',
    ],function (){
        Route::group([
            'prefix'   => 'warehouses',
        ],function (){
            Route::get('inventory','WarehouseController@inventory');
        });
        Route::apiResource('warehouses', 'WarehouseController');
    });
    /* Warehouse */

    /* Reason */
    Route::group([
        'name'   => 'reasons',
    ],function (){
        Route::apiResource('reasons', 'ReasonController');
    });
    /* Reason */
});
