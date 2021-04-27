<?php


namespace App\Http\Controllers\Api\Mudofa;


use App\Application;
use App\ApplicationPart;
use App\ContractClient;
use App\Helper\NumberHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Payment;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Goodoneuz\PayUz\Http\Classes\DataFormat;
use Goodoneuz\PayUz\Models\PaymentSystem;
use Goodoneuz\PayUz\Services\PaymentSystemService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Events\Models\Mudofa\CreateTransactionEvent;
use App\Events\Update\Mudofa\UpdateTransactionEvent;
use App\Events\Money\Mudofa\DistributionMoneyEvent;
use App\Http\Controllers\Api\ApplicationPartController;
use App\Http\Requests\Mudofa\TransactionRequest;
use Goodoneuz\PayUz\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TransactionController extends Controller
{
    protected $response;

    protected $transaction;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $model;

    public function __construct(Response $response, ApiResponse $apiResponse,Transaction $transaction)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:transactions.create')->only('store');
        $this->middleware('permission:transactions.show')->only('show');
        $this->middleware('permission:transactions.update')->only('update');
        $this->middleware('permission:transactions.delete')->only(['destroy']);

        $this->response = $response;
        $this->apiResponse = $apiResponse;
        $this->transaction = $transaction;
        $this->per_page = request()->get('per_page',1000000);
        $this->message_not_found = trans('messages.not_found', ['name' => trans('messages.transaction')]);
        $this->model = $transaction;
    }

    public function index()
    {
        $transactions = $this->transaction;

        if ($str = \request('search'))
            $transactions = $transactions->search($str);

        $transactions = $transactions->filter()->sort();
        //$transactions = $transactions->incomingOutgoing();

        $transactions = $transactions->select('id','payment_system','system_transaction_id', 'click_paydoc_id','amount','currency_code','state','updated_time','comment','detail',
            'transactionable_type','transactionable_id','created_at','updated_at')
            ->with([
                //'transactionable:id,console_number',
                'transactionable.client:id,name',
                'transactionable.contract_client:id,number,begin_date',
            ]);

        $transactions = $transactions->paginate($this->per_page);

        $items = $transactions->items();

        $amount_payme = Transaction::filter()->where('payment_system', PaymentSystem::PAYME)->isCompleted()->sum('amount');
        $amount_click = Transaction::filter()->where('payment_system', PaymentSystem::CLICK)->isCompleted()->sum('amount');
        $amount_paynet = Transaction::filter()->where('payment_system', PaymentSystem::PAYNET)->isCompleted()->sum('amount');
        $amount_cash = Transaction::filter()->where('payment_system', PaymentSystem::CASH)->isCompleted()->sum('amount');

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'transactions' => $items,
                    'pagination' => [
                        'total' => $transactions->total(),
                        'current_page' => $transactions->currentPage()
                    ],
                    'amounts' => [
                        'amount_payme'      => $amount_payme,
                        'amount_click'      => $amount_click,
                        'amount_paynet'     => $amount_paynet,
                        'amount_cash'       => $amount_cash,
                    ]
                ]
            ]
        ]);
    }

    public function store(TransactionRequest $request)
    {
        $event = new CreateTransactionEvent();
        $event->setPaymentSystem(PaymentSystem::CASH);
        $event->setSystemTransactionId($request->get('system_transaction_id',''));
        $event->setAmount($request->get('amount',0.0));
        $event->setCurrencyCode(Transaction::CURRENCY_CODE_UZS);
        $event->setState(Transaction::STATE_COMPLETED);
        $event->setUpdatedTime(DataFormat::timestamp(true));
        $event->setComment($request->get('comment',''));
        $event->setDetail(null);
        $event->setTransactionableType(Application::TABLE_NAME);
        $event->setTransactionableId($request->get('transactionable_id',null));

        if (is_array($request->get('relatedItems')))
            $event->setRelatedItems($request->get('relatedItems'));

        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.transaction')]),
            ]
        ]);
    }

    public function show(Transaction $transaction)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'transaction' => new \App\Http\Resources\Mudofa\Transaction($transaction,true),
                ],
            ]
        ]);
    }

    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $event = new UpdateTransactionEvent();
        $event->setId($transaction->id);
        $event->setPaymentSystem(PaymentSystem::CASH);
        $event->setSystemTransactionId('');
        $event->setAmount($request->get('amount',0.0));
        $event->setCurrencyCode(Transaction::CURRENCY_CODE_UZS);
        $event->setState(Transaction::STATE_COMPLETED);
        $event->setUpdatedTime(DataFormat::timestamp(true));
        $event->setComment($request->get('comment',''));
        $event->setDetail(null);
        $event->setTransactionableType(Application::TABLE_NAME);
        $event->setTransactionableId($request->get('transactionable_id',null));

        if (is_array($request->get('relatedItems')))
            $event->setRelatedItems($request->get('relatedItems'));

        event($event);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.transaction')]),
            ]
        ]);
    }

    public function destroy(Transaction $transaction)
    {
        try {
            $transaction->delete();
        } catch (\Exception $e) {

        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.transaction')]),
            ]
        ]);
    }

    public function getLastId(){
        $ls = Transaction::latest('id')->first();
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'last_id' => (($ls ? $ls->id : 0) + 1)
            ]
        ]);
    }

    public function getApplicationDocument(Request $request){
        $application = null;
        if( isset($request['contract_client_id']) ) {
            $application = Application::select(Application::TABLE_NAME . '.id AS id', 'client_id', 'contract_client_id', 'console_number',
                DB::raw('SUM(COALESCE('. ApplicationPart::TABLE_NAME .'.amount,0) - COALESCE('. ApplicationPart::TABLE_NAME . '.paid,0)) AS total_not_paid'));

            $application = $application->leftJoin(ApplicationPart::TABLE_NAME, function ($join) {
                $join->on(Application::TABLE_NAME.'.id', '=', ApplicationPart::TABLE_NAME . '.application_id')
                    ->whereNull(ApplicationPart::TABLE_NAME . '.deleted_at');
                })
                ->groupBy(Application::TABLE_NAME.'.id');

            $application = $application->where('contract_client_id', $request['contract_client_id'])->first();
        }
        else if ( isset($request['console_number']) ) {
            $application = Application::select(Application::TABLE_NAME . '.id AS id', 'client_id', 'contract_client_id', 'console_number',
                DB::raw('SUM(COALESCE('. ApplicationPart::TABLE_NAME .'.amount,0) - COALESCE('. ApplicationPart::TABLE_NAME . '.paid,0)) AS total_not_paid'));

            $application = $application->leftJoin(ApplicationPart::TABLE_NAME, function ($join) {
                $join->on(Application::TABLE_NAME.'.id', '=', ApplicationPart::TABLE_NAME . '.application_id')
                    ->whereNull(ApplicationPart::TABLE_NAME . '.deleted_at');
                })
                ->groupBy(Application::TABLE_NAME.'.id');

            $application = $application->where('console_number', $request['console_number'])->first();
        }

        if( !$application ) {
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => trans('messages.not_found',['name' => trans('messages.application')]),
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'application' => $application
            ]
        ]);
    }

    public function saveReturnTransaction(Request $request){
        if( $transaction = Transaction::find($request['transaction_id']) ) {
            // Agar tranzaksiya Comlete yani zaversheno bolsa
            if( $transaction->state == Transaction::STATE_COMPLETED ) {
                // Agar tranzaksiya Nalichniy bolsa
                if( $transaction->payment_system == PaymentSystem::CASH ) {
                    $transaction->comment = $request['comment'];
                    $transaction->state = Transaction::STATE_CANCELLED_AFTER_COMPLETE;
                    $transaction->update();

                    // Vozvrat qilgandan keyin pullarni ayirib qoyish uchun metod yozish
                    $this->returnPaymentsAfterCancelTransaction($transaction);
                }
                // Agar tranzaksiya Click dan bolsa
                else if( $transaction->payment_system == PaymentSystem::CLICK ) {
                    $click_config = PaymentSystemService::getPaymentSystemParamsCollect(PaymentSystem::CLICK);

                    $timestamp = time();
                    $merchant_user_id = $click_config['merchant_user_id'];
                    $secret_key = $click_config['secret_key'];
                    $serves_id = $click_config['service_id'];
                    $AUTH = $merchant_user_id . ':' . sha1($timestamp . $secret_key) . ':' . $timestamp;
                    $click_id = $transaction->click_paydoc_id;

                    $url = 'https://api.click.uz/v2/merchant/payment/reversal/' . $serves_id . '/' . $click_id;

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HTTP_VERSION, '1.1');
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Accept: application/json',
                            'Content-Type: application/json',
                            'Auth: ' . $AUTH
                        )
                    );
                    curl_setopt($ch, CURLOPT_POSTFIELDS, '');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec($ch);
                    $result = json_decode($result, true);
                    curl_close($ch);

                    // agar uspeshno bolgan bolsa yoki kabinetni ozidan vozvrat qilingan bolsa bazadanam togirlash
                    if( $result['error_code'] == 0 || $result['error_code'] == -18 ) {
                        $transaction->comment = $request['comment'];
                        $transaction->state = Transaction::STATE_CANCELLED_AFTER_COMPLETE;
                        $transaction->update();

                        // Vozvrat qilgandan keyin pullarni ayirib qoyish uchun metod yozish
                        $this->returnPaymentsAfterCancelTransaction($transaction);
                    }
                    // agar xatolik bolsa click tizimini xatoligi bilan borga qoshib qaytarish
                    else {
                        return $this->response->withArray([
                            'result' => [
                                'success' => false,
                                'data'    => null
                            ],
                            'error' => [
                                'message'   => trans('messages.Unable to return transaction. Error from Click system') . ': ' . $result['error_note'],
                                'code'      => ApiResponse::PAGE_NOT_FOUND
                            ]
                        ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
                    }
                }
                else {
                    return $this->response->withArray([
                        'result' => [
                            'success' => false,
                            'data'    => null
                        ],
                        'error' => [
                            'message'   => trans('messages.Unable to return transaction. This is a transaction from a payment system'),
                            'code'      => ApiResponse::PAGE_NOT_FOUND
                        ]
                    ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
                }
            }
            else {
                return $this->response->withArray([
                    'result' => [
                        'success' => false,
                        'data'    => null
                    ],
                    'error' => [
                        'message'   => trans('messages.Unable to return transaction. This transaction is not completed or has already been canceled'),
                        'code'      => ApiResponse::PAGE_NOT_FOUND
                    ]
                ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
            }
        }
        else {
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => trans('messages.not_found',['name' => trans('messages.transaction')]),
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.successful return',['name' => trans('messages.transaction')]),
            ]
        ]);
    }

    public function multipleDelete(Request $request){
        if (is_array($request['items'])) {
            Transaction::whereIn('id',$request['items'])->each(function($item){
                $item->delete();
            });
        }
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.transaction')]),
            ]
        ]);
    }

    public function createPayment() {
        return number_format('0', 0, '.', ' ');

//        $click_config = PaymentSystemService::getPaymentSystemParamsCollect(PaymentSystem::CLICK);
//
//        $timestamp = time();
//        $merchant_user_id = $click_config['merchant_user_id'];
//        $secret_key = $click_config['secret_key'];
//        $serves_id = $click_config['service_id'];
//        $AUTH = $merchant_user_id . ':' . sha1($timestamp . $secret_key) . ':' . $timestamp;
//        $click_id = '1183796505';
//
//        $url = 'https://api.click.uz/v2/merchant/payment/reversal/' . $serves_id . '/' . $click_id;
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_HTTP_VERSION, '1.1');
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            'Accept: application/json',
//            'Content-Type: application/json',
//            'Auth: ' . $AUTH
//            )
//        );
//        curl_setopt($ch, CURLOPT_POSTFIELDS, '');
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $result = curl_exec($ch);
//        $result = json_decode($result, true);
//        curl_close($ch);
//
//        Log::info($result);
//
//        return $result;

//        $transaction = \Goodoneuz\PayUz\Models\Transaction::find(1);
//        if( $transaction ) {
//            $this->createPaymentFromElectronTransaction($transaction);
//        }
    }

    public static function createPaymentFromElectronTransaction(Transaction $transaction) {
        if( $application = $transaction->transactionable ) {
            if( $contract_client = $application->contract_client ) {
                // Oldin dogovorga pulni qoshish
                $contract_client->sum += $transaction->amount;
                $contract_client->remainder += $transaction->amount;
                $contract_client->update();

                $now_month = date('Y-m', strtotime($transaction->created_at));

                // agar dogovor boshlanish va tugash voqti bolsa part qoshish va pulni tarqatish
                if( $contract_client->conclusion_date && $contract_client->termination_date ) {
                    if( $application->amount > 0 ) {
                        ApplicationPartController::createApplicationPart($application, $now_month);
                    }

                    $eventDist = new DistributionMoneyEvent($application);
                    event($eventDist);
                }
            }
        }
    }

    /**
     * @param Transaction $transaction
     * @param $items
     */
    public static function createPaymentFromSystemTransaction(Transaction $transaction, $items) {
        if( $application = $transaction->transactionable ) {
            if( $contract_client = $application->contract_client ) {
                // Oldin dogovorga pulni qoshish
                $contract_client->sum += $transaction->amount;
                $contract_client->remainder += $transaction->amount;
                $contract_client->update();

                $now_month = date('Y-m', strtotime($transaction->created_at));

                // agar dogovor boshlanish va tugash voqti bolsa part qoshish va pulni tarqatish
                if( $contract_client->conclusion_date && $contract_client->termination_date ) {
                    if( $application->amount > 0 ) {
                        ApplicationPartController::createApplicationPart($application, $now_month);
                    }

                    $eventDist = new DistributionMoneyEvent($application);
                    $eventDist->setItems($items);
                    event($eventDist);
                }
            }
        }
    }

    /**
     * @param Transaction $transaction
     * @param $items
     */
    public static function returnPaymentsAfterCancelTransaction(Transaction $transaction) {
        $return_amount = $transaction->amount;
        if( $application = $transaction->transactionable ) {
            if( $contract_client = $application->contract_client ) {
                // Oldin dogovordan pulni ayirish qolsa keyin paymentlani udalit qilish
                if( $return_amount >= $contract_client->remainder ) {
                    $contract_client_remainder = $contract_client->remainder;
                    $contract_client->sum -= $return_amount;
                    $contract_client->remainder = 0;
                    $contract_client->update();


                    // dogovordigi balans pulini olib qoganini paymentladan tekshirish
                    $return_amount -= $contract_client_remainder;

                    // Application paymentlarini ochirish yoki ozgartirish
                    $payments = Payment::where('paymentable_type', ApplicationPart::TABLE_NAME)
                        ->whereIn('paymentable_id',$application->parts->pluck('id')->toArray())
                        ->orderBy('paymentable_id', 'DESC')
                        ->orderBy('created_at', 'DESC')
                        ->get();

                    foreach ($payments as $payment ) {
                        if( $return_amount > 0 ) {
                            if( round($return_amount, 2) >= round($payment->amount, 2) ) {
                                $return_amount -= $payment->amount;
                                $payment->delete();
                            }
                            else {
                                // paymentdan qolgan summani ayirish
                                $payment->amount = round($payment->amount - $return_amount, 2);
                                $payment->update();

                                // application_part da ham tolangan summani togirlash
                                if( $application_part = $payment->paymentable ) {
                                    $application_part->paid = round($application_part->paid - $return_amount, 2);
                                    $application_part->update();
                                }

                                $return_amount = 0;

                                // va chiqib ketish
                                break;
                            }
                        }
                        else {
                            break;
                        }
                    }
                }
                else {
                    // faqat dogovor balansidan ayirib qoyish. Paymentlaga teymaslik
                    $contract_client->sum -= $return_amount;
                    $contract_client->remainder -= $return_amount;
                    $contract_client->update();
                }
            }
        }
    }

    public function getAmountsAndCounts() {
        $amount_payme = Transaction::where('payment_system', PaymentSystem::PAYME)->isCompleted()->sum('amount');
        $amount_click = Transaction::where('payment_system', PaymentSystem::CLICK)->isCompleted()->sum('amount');
        $amount_paynet = Transaction::where('payment_system', PaymentSystem::PAYNET)->isCompleted()->sum('amount');
        $amount_cash = Transaction::where('payment_system', PaymentSystem::CASH)->isCompleted()->sum('amount');

        $count_payme = Transaction::where('payment_system', PaymentSystem::PAYME)->isCompleted()->count();
        $count_click = Transaction::where('payment_system', PaymentSystem::CLICK)->isCompleted()->count();
        $count_paynet = Transaction::where('payment_system', PaymentSystem::PAYNET)->isCompleted()->count();
        $count_cash = Transaction::where('payment_system', PaymentSystem::CASH)->isCompleted()->count();

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'amounts' => [
                        'amount_payme'      => $amount_payme,
                        'amount_click'      => $amount_click,
                        'amount_paynet'     => $amount_paynet,
                        'amount_cash'       => $amount_cash,
                    ],
                    'counts' => [
                        'count_payme'      => $count_payme,
                        'count_click'      => $count_click,
                        'count_paynet'     => $count_paynet,
                        'count_cash'       => $count_cash,
                    ]
                ]
            ]
        ]);
    }

    public function createTransactionFromFile(Request $request) {
        if(!$request->hasFile('file')) {
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => 'No File Uploaded',
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        $file = $request->file('file');

        if(!$file->isValid()) {
            return $this->response->withArray([
                'result' => [
                    'success' => false,
                    'data'    => null
                ],
                'error' => [
                    'message'   => 'File is not valid!',
                    'code'      => ApiResponse::PAGE_NOT_FOUND
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }

        $path = $file->store('public/transaction_files');
        $full_path = Storage::disk()->path($path);

        $reader = IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($full_path);
        $highestRow = $spreadsheet->getActiveSheet()->getHighestRow();

        for($i = 4; $i <= $highestRow; $i++)
        {
            $activeSheetData = $spreadsheet->getActiveSheet()
                ->rangeToArray(
                    'B' . $i . ':'.$spreadsheet->getActiveSheet()->getHighestColumn().($i),
                    null,
                    true,
                    true,
                    true
                );

            $contract_number = preg_replace('/_x([0-9a-fA-F]{4})_/', '&#x$1;', $activeSheetData[$i]['B']);
            $contract_number = preg_replace('/[^0-9]/', '', $contract_number);

            $amount = preg_replace('/_x([0-9a-fA-F]{4})_/', '&#x$1;', $activeSheetData[$i]['BO']);
            $amount = floatval(preg_replace('/[^0-9]/', '', $amount));
            //preg_match_all('!\d+!', $amount, $amount);

            if( $contract_number && $amount ) {
                $insert_array[] = array('contract_number' => $contract_number, 'amount' => $amount);
            }

            if( $i % 10 == 0 )
            {
                foreach ($insert_array as $key => $value) {
                    $contract_number1 = $value['contract_number'];
                    $application = Application::whereHas('contract_client', function (Builder $query) use($contract_number1) {
                        $query->where('number', $contract_number1);
                    })->first();

                    if( $application ) {
                        $event = new CreateTransactionEvent();
                        $event->setPaymentSystem(PaymentSystem::CASH);
                        $event->setSystemTransactionId('');
                        $event->setAmount($value['amount']);
                        $event->setCurrencyCode(Transaction::CURRENCY_CODE_UZS);
                        $event->setState(Transaction::STATE_COMPLETED);
                        $event->setUpdatedTime(DataFormat::timestamp(true));
                        $event->setComment('');
                        $event->setDetail(null);
                        $event->setTransactionableType(Application::TABLE_NAME);
                        $event->setTransactionableId($application->id);

                        event($event);
                    }
                }

                $insert_array = array();
            }

            if( $i == $highestRow )
            {
                foreach ($insert_array as $key => $value) {
                    $contract_number1 = $value['contract_number'];
                    $application = Application::whereHas('contract_client', function (Builder $query) use($contract_number1) {
                        $query->where('number', $contract_number1);
                    })->first();

                    if( $application ) {
                        $event = new CreateTransactionEvent();
                        $event->setPaymentSystem(PaymentSystem::CASH);
                        $event->setSystemTransactionId('');
                        $event->setAmount($value['amount']);
                        $event->setCurrencyCode(Transaction::CURRENCY_CODE_UZS);
                        $event->setState(Transaction::STATE_COMPLETED);
                        $event->setUpdatedTime(DataFormat::timestamp(true));
                        $event->setComment('');
                        $event->setDetail(null);
                        $event->setTransactionableType(Application::TABLE_NAME);
                        $event->setTransactionableId($application->id);

                        event($event);
                    }
                }

                break;
            }
        }

        Storage::delete($path);

        return response()->json([
            'success' => 'Success upload',
        ]);
    }
}

