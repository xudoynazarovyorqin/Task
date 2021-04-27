<?php


namespace App\Http\Controllers\Api;


use App\ApplicationPart;
use App\ApplicationService;
use App\District;
use App\Events\Models\CreateApplicationEvent;
use App\Events\Money\Mudofa\DistributionMoneyEvent;
use App\Events\Update\UpdateApplicationEvent;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\Application;
use App\Http\Requests\ApplicationRequest;
use App\Payment;
use Carbon\Carbon;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Goodoneuz\PayUz\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Shared_File;
use PHPExcel_STYLE_ALIGNMENT;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Settings;
use dompdf;

class ApplicationController extends Controller
{
    protected $response;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $listIndex;

    private $model;

    public function __construct(Response $response, ApiResponse $apiResponse, Application $application)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:applications.create')->only('store');
        $this->middleware('permission:applications.show')->only('show');
        $this->middleware('permission:applications.edit')->only('edit');
        $this->middleware('permission:applications.print')->only('print');
        $this->middleware('permission:applications.update')->only('update');
        $this->middleware('permission:applications.delete')->only(['destroy','multipleDelete']);
        $this->middleware('permission:summary_report.index')->only('summaryReport');

        $this->response = $response;
        $this->model = $application;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000) ;
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.application')]);
        $this->listIndex = 'applications';
    }

    public function index()
    {
        /*** FILTER Countlari ***/
        $count_all = $this->model->filter()->count();

        $count_new = $this->model->filter()->where(function($query) {
            return $query->whereDoesntHave('parts', function (Builder $query) {
                $query->where('status', ApplicationPart::ACTIVE);
            });
        })->count();

        $count_pending = $this->model->filter()->where(function($query) {
            return $query->orwhere(function ($query) {
                return $query->whereDoesntHave('parts', function (Builder $query) {
                    $query->where('start_date','like','%' . date('Y-m', strtotime('+1 month')) . '%')
                        ->where('status', ApplicationPart::ACTIVE);
                });
            })->orwhere(function ($query) {
                return $query->whereHas('parts', function (Builder $query) {
                    $query->where('start_date','like','%' . date('Y-m', strtotime('+1 month')) . '%')
                        ->where('status', ApplicationPart::ACTIVE)
                        ->whereRaw('amount - paid > 0');
                });
            });
        })->count();

        $count_overdue = $this->model->filter()->where(function($query) {
            return $query->orwhere(function ($query) {
                return $query->whereDoesntHave('parts', function (Builder $query) {
                    $query->where('start_date','like', '%' . date('Y-m') . '%')
                        ->where('status', ApplicationPart::ACTIVE);
                });
            })->orwhere(function ($query) {
                return $query->whereHas('parts', function (Builder $query) {
                    $query->where('start_date','like', '%' . date('Y-m') . '%')
                        ->where('status', ApplicationPart::ACTIVE)
                        ->whereRaw('amount - paid > 0');
                });
            });
        })->count();
        /*** End FILTER Countlari ***/

        $list = $this->model;

        if ($str = \request('search'))
        {
            $list = $list->search($str);
        }

        if ($status_by_payment = \request('status_by_payment'))
        {
            $list = $list->filterByPayment($status_by_payment);
        }

        $list = $list->filter()->sort();

        $list = $list->select(Application::TABLE_NAME . '.id AS id', 'number', 'datetime', 'client_id', 'contract_client_id', 'status_id',
            'console_number', Application::TABLE_NAME . '.amount AS amount',
            'object_name', 'district_id', 'quarter_id', 'object_street', 'object_home', 'object_corps', 'object_flat',
            DB::raw('SUM(COALESCE('. ApplicationPart::TABLE_NAME .'.amount,0)) AS total_amount'),
            DB::raw('SUM(COALESCE('. ApplicationPart::TABLE_NAME .'.paid,0)) AS total_paid'),
            Application::TABLE_NAME . '.created_at AS created_at', Application::TABLE_NAME . '.updated_at AS updated_at');

        $list = $list->leftJoin(ApplicationPart::TABLE_NAME, function ($join) {
            $join->on(Application::TABLE_NAME.'.id', '=', ApplicationPart::TABLE_NAME . '.application_id')
                ->whereNull(ApplicationPart::TABLE_NAME . '.deleted_at');
        })->groupBy(Application::TABLE_NAME.'.id');

        $list_for_sum = $list->get();
        $total_amount = $list_for_sum->sum('total_amount');
        $total_paid = $list_for_sum->sum('total_paid');

        $list = $list->with([
            'client:id,name,phone',
            'contract_client:id,number,begin_date,remainder',
            'status:id,state,status',
            'district:id,name',
            'quarter:id,name',
        ]);

        $list = $list->paginate($this->per_page);

//        $total_amount = $list->sum('total_amount');
//        $total_paid = $list->sum('total_paid');


        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data' => [
                    'applications'  => $list->items(),
                    'pagination'    => [
                        'total'         => $list->total(),
                        'current_page'  => $list->currentPage()
                    ],
                    'total_amount'  => floatval($total_amount),
                    'total_paid'    => floatval($total_paid),
                    'counts' => [
                        'count_all'     => $count_all,
                        'count_new'     => $count_new,
                        'count_pending' => $count_pending,
                        'count_overdue' => $count_overdue,
                    ]
                ]
            ]
        ]);
    }

    public function store(ApplicationRequest $request)
    {
        $event = new CreateApplicationEvent();

        $event->setNumber($request['number']);
        $event->setDatetime($request['datetime']);
        $event->setClientId($request['client_id']);
        $event->setContractClientId($request['contract_client_id']);
        $event->setStatusId($request['status_id']);
        $event->setConsoleNumber($request['console_number']);
        $event->setObjectName($request['object_name']);
        $event->setDistrictId($request['district_id']);
        $event->setQuarterId($request['quarter_id']);
        $event->setObjectStreet($request['object_street']);
        $event->setObjectHome($request['object_home']);
        $event->setObjectCorps($request['object_corps']);
        $event->setObjectFlat($request['object_flat']);

        if ($services = $request['application_services']){
            $event->setServices($services);
        }

        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.application')]),
            ]
        ]);
    }

    public function show($id)
    {
        return $this->edit($id);
    }

    public function edit($id){

        if (!$model = $this->model->find($id))
        {
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => $this->message_not_found,
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'application' => new \App\Http\Resources\Application($model,true),
                ],
            ]
        ]);
    }

    public function getAudits($id)
    {
        if (!$model = $this->model->find($id))
        {
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => $this->message_not_found,
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'audits' => new \App\Http\Resources\Relation\AuditCollection($model->audits),
                ],
            ]
        ]);
    }

    public function getParts($id)
    {
        if (!$model = $this->model->find($id))
        {
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => $this->message_not_found,
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        $application_parts = ApplicationPart::where('application_id', $model->id)
            ->orderBy('start_date', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'parts' => new \App\Http\Resources\Relation\ApplicationPartCollection($application_parts),
                ],
            ]
        ]);
    }

    public function getTransactions($id)
    {
        if (!$model = $this->model->with('transactions')->find($id))
        {
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => $this->message_not_found,
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'transactions' => new \App\Http\Resources\Mudofa\Relation\TransactionCollection($model->transactions),
                ],
            ]
        ]);
    }

    public function update(ApplicationRequest $request, Application $application)
    {
        if (!$model = $application)
        {
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => $this->message_not_found,
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        $event = new UpdateApplicationEvent();
        $event->setApplication($model);
        $event->setNumber($request['number']);
        $event->setDatetime($request['datetime']);
        $event->setClientId($request['client_id']);
        $event->setContractClientId($request['contract_client_id']);
        $event->setStatusId($request['status_id']);
        $event->setConsoleNumber($request['console_number']);
        $event->setObjectName($request['object_name']);
        $event->setDistrictId($request['district_id']);
        $event->setQuarterId($request['quarter_id']);
        $event->setObjectStreet($request['object_street']);
        $event->setObjectHome($request['object_home']);
        $event->setObjectCorps($request['object_corps']);
        $event->setObjectFlat($request['object_flat']);

        $services = $request['application_services'];
        if ( $services ){
            $event->setServices($services);
        }

        event($event);

        // agar yengi tarif qoshilvotgan bolsa
        if( is_array($services) && count($services) ) {
            if( $contract_client = $application->contract_client ) {

                // $application ni boshqattan ovolish. chunki ApplicationService observeri da amounti ozgaradi.
                $application = Application::find($application->id);
                // 1) dogovor balansga tolangan summalani qaytarish
                $total_paid = ApplicationPart::where('application_id', $application->id)->sum('paid');
                $contract_client->remainder += $total_paid;
                $contract_client->update();


                // 2) hamma paymentlani va AppPart lani ochirib
                $application_parts_ids = $application->parts->pluck('id')->toArray();

                $payments = Payment::where('paymentable_type', ApplicationPart::TABLE_NAME)
                    ->whereIn('paymentable_id',$application_parts_ids)
                    ->get();
                foreach ($payments as $payment) {
                    $payment->delete();
                }

                ApplicationPart::where('application_id', $application->id)->delete();


                // 3) boshqattan AppPartla yaratish. Agar data zaklucheniyasi va tugashi bor bolsa
                if( $contract_client->conclusion_date && $contract_client->termination_date ) {
                    if( $application->amount > 0 ) {
                        $now_month = date('Y-m');
                        ApplicationPartController::createApplicationPart($application, $now_month);


                        // 4) dogovor balansidan pulni olib boshqattan yoyib chiqish
                        $eventDist = new DistributionMoneyEvent($application);
                        event($eventDist);
                    }
                }
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.application')]),
                'data'    => [
                ]
            ]
        ]);
    }


    public function destroy($id)
    {
        if (!$model = $this->model->find($id))
        {
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => $this->message_not_found,
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        $model->delete();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.application')]),
            ]
        ]);
    }

    public function multipleDelete(Request $request){
        if (is_array($request['items'])) {
            Application::whereIn('id',$request['items'])->each(function($application){
                $application->delete();
            });
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.application')]),
            ]
        ]);
    }

    public function deleteService(){
        if ($application_service = ApplicationService::find(\request()->get('application_service_id',null))){
//            $total_paid = ApplicationPart::where('application_id', $application_service->application_id)->sum('paid');
//            if( $total_paid > 0 ) {
//                return $this->response->withArray([
//                    'result' => [
//                        'success' => false
//                    ],
//                    'error' => [
//                        'code' => ApiResponse::PAGE_NOT_FOUND,
//                        'message'   => trans('messages.There is a payment in this application. Unable to delete service'),
//                    ]
//                ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
//            }
//            else {
//                ApplicationPart::where('application_id', $application_service->application_id)->where('status', ApplicationPart::ACTIVE)->delete();
//                $application_service->delete();
//            }

            $application_service->delete();

            if( $application = $application_service->application ) {
                if( $contract_client = $application->contract_client ) {

                    // 1) dogovor balansga tolangan summalani qaytarish
                    $total_paid = ApplicationPart::where('application_id', $application->id)->sum('paid');
                    $contract_client->remainder += $total_paid;
                    $contract_client->update();


                    // 2) hamma paymentlani va AppPart lani ochirib
                    $application_parts_ids = $application->parts->pluck('id')->toArray();

                    $payments = Payment::where('paymentable_type', ApplicationPart::TABLE_NAME)
                        ->whereIn('paymentable_id',$application_parts_ids)
                        ->get();
                    foreach ($payments as $payment) {
                        $payment->delete();
                    }

                    ApplicationPart::where('application_id', $application->id)->delete();


                    // 3) boshqattan AppPartla yaratish. Agar data zaklucheniyasi va tugashi bor bolsa
                    if( $contract_client->conclusion_date && $contract_client->termination_date ) {
                        if( $application->amount > 0 ) {
                            $now_month = date('Y-m');
                            ApplicationPartController::createApplicationPart($application, $now_month);


                            // 4) dogovor balansidan pulni olib boshqattan yoyib chiqish
                            $eventDist = new DistributionMoneyEvent($application);
                            event($eventDist);
                        }
                    }
                }
            }
        }else{
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => []
                ],
                'error' => [
                    'message' => __('messages.not_found',['name' => __('messages.service')]),
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.service')]),
                'data'    => []
            ]
        ]);
    }

    public function chart(){
        if (!$type = \request('type')){
            return response()->json([
                'result'  => [
                    'success' => false,
                ],
                'error' => [
                    'message' => __('messages.not_found',['name' => __('messages.diagram_type')])
                ]
            ]);
        }

        $data = [];
        $data_one = [];
        $data_two = [];
        switch (\request('type')){
            case self::WEEKLY:
                $data  = ChartController::weekly(Application::class,'amount');
                $data_one = ChartController::dayTotalSum(Application::class,'amount');
                $data_two = ChartController::weekTotalSum(Application::class,'amount');
                break;
            case self::MONTHLY:
                $data  = ChartController::monthly(Application::class,'amount');
                $data_one = ChartController::weekTotalSum(Application::class,'amount');
                $data_two = ChartController::monthTotalSum(Application::class,'amount');
                break;
            case self::YEARLY:
                $data  = ChartController::yearly(Application::class,'amount');
                $data_one = ChartController::monthTotalSum(Application::class,'amount');
                $data_two = ChartController::yearTotalSum(Application::class,'amount');
                break;
            case self::PERIOD:
                $data  = ChartController::period(Application::class, \request('from_date'), \request('to_date'), 'amount'); break;
        }

        return response()->json([
            'result' => [
                'success' => true,
                'data' => [
                    'chart_data' => $data,
                    'data_one' => $data_one,
                    'data_two' => $data_two,
                ]
            ],
        ]);
    }

    public function print(){

        if (!$model = $this->model->find(\request('id',null))){
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => $this->message_not_found,
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        return view('application_invoice',['application' => $model]);
    }

    public function getLastId(){
        $ls = Application::latest('id')->first();
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'last_id' => (($ls ? $ls->id : 0) + 1)
            ]
        ]);
    }

    /**
     * CronJob orqali buyoqqa kelinadi
    */
    public static function createApplicationPartEveryMonth() {
        //$applications = Application::with('contract_client')->where('amount', '>', 0)->has('contract_client')->get();
        $now_month = date('Y-m');
        Application::with('contract_client')->where('amount', '>', 0)->has('contract_client')->chunkById(50, function ($applications) use ($now_month) {
            foreach ($applications as $application) {
                // agar dogovor boshlanish va tugash voqti bolsa part qoshish va pulni tarqatish
                if( $application->contract_client && $application->contract_client->conclusion_date && $application->contract_client->termination_date ) {
                    ApplicationPartController::createApplicationPart($application, $now_month);

                    // dogovor balansidan pulni olib otqazvorish
                    $eventDist = new DistributionMoneyEvent($application);
                    event($eventDist);
                }
            }
        });
    }

    /**
     * CronJob orqali buyoqqa kelinadi Telegram Bot pul tolash haqida ga sms yuborish
     */
    public static function sendSmsToTelegramBotAboutTopUpBalance() {
        $console_numbers = DB::table('telegram_bot_storages')->pluck('console_number');

        Application::with('contract_client:id,remainder')
            ->where('amount', '>', 0)
            ->whereIn('console_number', $console_numbers)
            ->has('contract_client')
            ->chunkById(50, function ($applications) {
                foreach ($applications as $application) {
                    $contract_client_balance = $application->contract_client->remainder;
                    $application_part = ApplicationPart::where('application_id', $application->id)
                        ->whereRaw('amount - paid > 0')
                        ->where('start_date', 'like', '%' . date('Y-m', strtotime('+1 month')) . '%')
                        ->first();

                    if( $application_part ) {
                        if( $contract_client_balance < $application_part->amount - $application_part->paid ) {
                            $debit = $application_part->amount - $application_part->paid - $contract_client_balance;

                            $text = "Уважаемый абонент. Попросим пополнить баланс на сумму <b>" . number_format(floatval($debit),2,".", " ") . "</b>.";

                            $secret_key = '168fd4ed98f55c0e116cd2608f82cc23';
                            $url = 'https://mudofabot.goodone.uz/telegram/send/message/aboutTopUpBalance';
                            $time = date('Y-m-d H:i:s');
                            $auth = md5($time . $secret_key . $text);

                            $message = array();
                            $message['access_token'] = $auth;
                            $message['console_number'] = $application->console_number;
                            $message['message'] = $text;
                            $message['time'] = $time;
                            $message = json_encode($message, JSON_UNESCAPED_UNICODE);

                            $ch = curl_init($url);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "$message");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $data = curl_exec($ch);
                            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            curl_close($ch);
                        }
                    }
                    else {
                        if( $contract_client_balance < $application->amount ) {
                            $debit = $application->amount - $contract_client_balance;

                            $text = "Уважаемый абонент. Попросим пополнить баланс на сумму <b>" . number_format(floatval($debit),2,".", " ") . "</b>.";

                            $secret_key = '168fd4ed98f55c0e116cd2608f82cc23';
                            $url = 'https://mudofabot.goodone.uz/telegram/send/message/aboutTopUpBalance';
                            $time = date('Y-m-d H:i:s');
                            $auth = md5($time . $secret_key . $text);

                            $message = array();
                            $message['access_token'] = $auth;
                            $message['console_number'] = $application->console_number;
                            $message['message'] = $text;
                            $message['time'] = $time;
                            $message = json_encode($message, JSON_UNESCAPED_UNICODE);

                            $ch = curl_init($url);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "$message");
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $data = curl_exec($ch);
                            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            curl_close($ch);
                        }
                    }
                }
            });
    }

    public function summaryReport()
    {
        $from_date = \request('from_date', date('Y-m-01'));
        $from_date_prev_day = date('Y-m-d', strtotime('-1 day', strtotime($from_date)));
        $to_date = \request('to_date', date('Y-m-d'));

        $applications_by_district = Application::query()
            ->leftJoin('application_parts', function ($join) use($from_date_prev_day) {
                $join->on('applications.id', '=', 'application_parts.application_id')
                    //->whereDate('start_date', '<=', $from_date_prev_day) // from_date gacha bolgan partlani hisoblashi kere
                    ->whereNull('application_parts.deleted_at');
            })
            ->leftJoin('contract_clients', 'applications.contract_client_id', '=', 'contract_clients.id')
            ->leftJoin('districts', 'applications.district_id', '=', 'districts.id')
            ->select('applications.id', 'applications.district_id', 'districts.name AS district_name')
            ->selectRaw('SUM(COALESCE(application_parts.amount, 0)) as part_amount')
            ->selectRaw('SUM(COALESCE(application_parts.paid, 0)) as part_paid')
            ->selectRaw('SUM(COALESCE(application_parts.amount - application_parts.paid, 0)) as part_not_paid')
            ->selectRaw('SUM(COALESCE(contract_clients.remainder, 0)) as contract_remainder')
            ->groupBy('applications.id') // Left Join application_parts uchun
            ->groupBy('districts.id') // Left Join districts uchun
            ->get()
            ->groupBy(['district_id']) // collection orqali rayon boyicha gruppalsh
            ->map(function ($row) { // collection orqali massivni boshqattan tuzib chiqish
                return [
                    'district_name' => isset($row[0]) ? $row[0]['district_name'] : '',
                    'district_id' => isset($row[0]) ? $row[0]['district_id'] : null,
                    'total_part_amount' =>  $row->sum('part_amount'),
                    'total_part_paid' =>  $row->sum('part_paid'),
                    'total_part_not_paid' =>  $row->sum('part_not_paid'),
                    'total_contract_remainder' =>  $row->sum('contract_remainder'),
                ];
            });

        // bu oyning AppPart paid summasini hisoblash
        $applications_by_district2 = Application::query()
            ->leftJoin('application_parts', function ($join) use($from_date) {
                $join->on('applications.id', '=', 'application_parts.application_id')
                    ->whereDate('start_date', '>=', $from_date) // from_date gacha bolgan partlani hisoblashi kere
                    ->whereNull('application_parts.deleted_at');
            })
            ->leftJoin('districts', 'applications.district_id', '=', 'districts.id')
            ->select('applications.id', 'applications.district_id', 'districts.name AS district_name')
            ->selectRaw('SUM(COALESCE(application_parts.amount, 0)) as part_amount')
            ->selectRaw('SUM(COALESCE(application_parts.paid, 0)) as part_paid')
            ->selectRaw('SUM(COALESCE(application_parts.amount - application_parts.paid, 0)) as part_not_paid')
            ->groupBy('applications.id') // Left Join application_parts uchun
            ->groupBy('districts.id') // Left Join districts uchun
            ->get()
            ->groupBy(['district_id']) // collection orqali rayon boyicha gruppalsh
            ->map(function ($row) {
                return [
                    'district_id' => isset($row[0]) ? $row[0]['district_id'] : null,
                    'total_part_amount' =>  $row->sum('part_amount'),
                ];
            });


        $applications_by_district = $applications_by_district->map(function ($item, $key) use($from_date, $to_date, $applications_by_district2) {
            $district_id = $item['district_id'];
            $transaction_amount_from_to = Transaction::whereHasMorph('transactionable',Application::class, function ($query) use ($district_id, $from_date, $to_date){
                    return $query->where('district_id',$district_id);
                })
                ->where('state', Transaction::STATE_COMPLETED)
                ->whereDate('created_at','>=',Carbon::parse($from_date)->toDateString())
                ->whereDate('created_at','<=',Carbon::parse($to_date)->toDateString())
                ->sum('amount');

            $transaction_amount_in_to_date = Transaction::whereHasMorph('transactionable',Application::class, function ($query) use ($district_id, $from_date, $to_date){
                    return $query->where('district_id',$district_id);
                })
                ->where('state', Transaction::STATE_COMPLETED)
                ->whereDate('created_at','=',Carbon::parse($to_date)->toDateString())
                ->sum('amount');

            $item['transaction_amount_from_to'] = floatval($transaction_amount_from_to);
            $item['transaction_amount_in_to_date'] = floatval($transaction_amount_in_to_date);

            // Qarzini qayta hisoblash
            $custom_item = null;
            $custom_item = $applications_by_district2->filter(function($item2) use($district_id) {
                return $item2['district_id'] == $district_id;
            })->first();

            if( $custom_item ) {
                $item['total_part_not_paid'] = floatval($item['total_part_not_paid']) + floatval($transaction_amount_from_to) - floatval($custom_item['total_part_amount']) - floatval($item['total_contract_remainder']);
            }

            $item['total_part_not_paid_in_to_date'] = $item['total_part_not_paid'] - $item['transaction_amount_from_to'];
            $item['percent'] = ($item['total_part_not_paid'] > 0) ? (round(100 * ($item['transaction_amount_from_to'] / $item['total_part_not_paid']),2)) : 0;

            return $item;
        });

        $total_total_part_not_paid = $applications_by_district->sum('total_part_not_paid');
        $total_transaction_amount_from_to = $applications_by_district->sum('transaction_amount_from_to');
        $total_total_part_not_paid_in_to_date = $applications_by_district->sum('total_part_not_paid_in_to_date');
        $total_transaction_amount_in_to_date = $applications_by_district->sum('transaction_amount_in_to_date');
        $total_percent = ($total_total_part_not_paid > 0) ? (round(100 * $total_transaction_amount_from_to / $total_total_part_not_paid)) : 0;

        $month_name = \App\Helper\MonthNameInRussian::monthNameToRussianWithCapitalLetter(date("M", strtotime($to_date)));

        // excel datasini yaratish
        $excel_data = [];
//        foreach ($applications_by_district as $key => $value) {
//            Log::info($value);
//        }


        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data' => [
                    'from_date'  => date('d.m.Y', strtotime($from_date)),
                    'to_date'  => date('d.m.Y', strtotime($to_date)),
                    'month_name' => $month_name,
                    'applications_by_district'  => $applications_by_district,
                    'totals' => [
                        'total_total_part_not_paid' => $total_total_part_not_paid,
                        'total_transaction_amount_from_to' => $total_transaction_amount_from_to,
                        'total_total_part_not_paid_in_to_date' => $total_total_part_not_paid_in_to_date,
                        'total_transaction_amount_in_to_date' => $total_transaction_amount_in_to_date,
                        'total_percent' => $total_percent,
                    ]
                ]
            ]
        ]);
    }

    public function debitListExcel()
    {
        if( $district_id = \request('district_id') ) {
            $district = District::select('id', 'name')->find($district_id);

            $applications = $this->model->select(Application::TABLE_NAME . '.id AS id', 'client_id', 'contract_client_id', 'district_id',
                    'object_street', 'object_home', 'object_flat',
                    DB::raw('SUM(COALESCE('. ApplicationPart::TABLE_NAME .'.amount,0) - COALESCE('. ApplicationPart::TABLE_NAME . '.paid,0)) AS total_not_paid'));

            $applications = $applications->with([
                'client:id,name,phone',
                'contract_client:id,number,begin_date,remainder',
            ])
            ->leftJoin(ApplicationPart::TABLE_NAME, function ($join) {
                $join->on(Application::TABLE_NAME.'.id', '=', ApplicationPart::TABLE_NAME . '.application_id')
                    ->whereNull(ApplicationPart::TABLE_NAME . '.deleted_at');
            })
            ->groupBy(Application::TABLE_NAME.'.id');

            $applications = $applications->where('district_id', $district_id)->get();

            error_reporting(E_ALL);
            ini_set('display_errors', TRUE);
            ini_set('display_startup_errors', TRUE);

            if (PHP_SAPI == 'cli')
                die('This example should only be run from a Web Browser');

            /** Include PHPExcel */

            // Create new PHPExcel object
            $objPHPExcel = new PHPExcel();

            // Set document properties
            $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

            // Add some data

            $objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
            $objPHPExcel->getActiveSheet()->mergeCells('D1:D2');
            $objPHPExcel->getActiveSheet()->mergeCells('E1:E2');
            $objPHPExcel->getActiveSheet()->mergeCells('F1:F2');

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '№')
                ->setCellValue('C1', $district->name . '-' . date('Y') . 'Г')
                ->setCellValue('C2', 'КВАРТИРЫ')
                ->setCellValue('D1', 'АДРЕС')
                ->setCellValue('E1', 'Номер телефона')
                ->setCellValue('F1', 'Дебет')
                ->setCellValue('G1', date('Y') . 'Г')
                ->setCellValue('G2', 'Кредит');

            // Adding information

            $y_count = 3;
            $poryadkoviy_nomer = 1;

            foreach ($applications as $application)
            {
                $x_count = 'A';

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$y_count, $poryadkoviy_nomer);

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B'.$y_count, ($application->contract_client) ? $application->contract_client->number : '' );

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('C'.$y_count, ($application->client) ? $application->client->name : '' );

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('D'.$y_count, $application->object_street . ' ' . $application->object_home . '-' . $application->object_flat );

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('E'.$y_count, ($application->client) ? $application->client->phone : '' );

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('F'.$y_count, ($application->total_not_paid) ? number_format($application->total_not_paid, 2, '.', ' ') : '' );

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('G'.$y_count, ($application->contract_client && $application->contract_client->remainder) ? number_format($application->contract_client->remainder, 2, '.', ' ') : '' );

                $poryadkoviy_nomer++;
                $y_count++;
            }


            // Стили для верхней надписи (первая строка)

            $sheet = $objPHPExcel->getActiveSheet();

            $sheet->getColumnDimension('A')->setWidth(8);
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->getColumnDimension('C')->setWidth(40);
            $sheet->getColumnDimension('D')->setWidth(30);
            $sheet->getColumnDimension('E')->setWidth(30);
            $sheet->getColumnDimension('F')->setWidth(30);
            $sheet->getColumnDimension('G')->setWidth(30);

//        Header styles

            $style_header = array(
                // Шрифт
                'font'=>array(
                    'bold' => true,
                ),
                'alignment' => array(
                    'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                    'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                ),
                // Background color
                'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'FFFF00')
                )
            );
            $sheet->getStyle('A1:'.$sheet->getHighestDataColumn().'2')->applyFromArray($style_header);

//        Body styles
            $poryadkoviy_nomer += 1;

            $style = array(
                // Выравнивание
                'alignment' => array(
                    'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_LEFT,
                    'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
                ),
            );
            $sheet->getStyle('A3:'.$sheet->getHighestDataColumn().$poryadkoviy_nomer)->applyFromArray($style);

            $sheet->getStyle('A1:G'.$poryadkoviy_nomer)->applyFromArray(
                array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                        )
                    )
                )
            );

            // Rename worksheet
            $objPHPExcel->getActiveSheet()->setTitle('Отчёт');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            // If you're serving to IE over SSL, then the following may be needed
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
            header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header ('Pragma: public'); // HTTP/1.0

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="report.xls"');
            $objWriter->save('php://output');
        }
    }
}
