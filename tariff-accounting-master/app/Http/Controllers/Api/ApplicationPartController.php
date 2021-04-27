<?php


namespace App\Http\Controllers\Api;

use App\Application;
use App\ApplicationPart;
use App\ContractClientSuspense;
use App\Helper\NumberHelper;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class ApplicationPartController extends Controller
{
    protected $response;

    protected $per_page;

    protected $apiResponse;

    private $message_not_found;

    private $listIndex;

    private $model;

    public function __construct(Response $response, ApiResponse $apiResponse, ApplicationPart $applicationPart)
    {
        //$this->middleware('auth:api');
        $this->response = $response;
        $this->model = $applicationPart;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',1000000) ;
        $this->message_not_found = trans('messages.not_found',['name' => trans('messages.payment')]);
        $this->listIndex = 'payments';
    }

    public function index()
    {
        $list = $this->model;

        if ($str = \request('search'))
        {
            $list = $list->search($str);
        }

        $list = $list->havePaid()->active();
        $list = $list->filter()->sort();

        $list = $list->select('id', 'application_id', 'start_date', 'stop_date', 'amount', 'paid',
            'created_at', 'updated_at');

        $list = $list->with([
            'application:id,client_id,console_number',
            'application.client:id,name',
        ]);

        $list = $list->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data' => [
                    'applicationParts'  => $list->items(),
                    'pagination'    => [
                        'total'         => $list->total(),
                        'current_page'  => $list->currentPage()
                    ],
                ]
            ]
        ]);
    }


    /**
     * @param null $month - bu transaction oyi yoki CronJob bilan ochilvotgan ApplicationPart
     */
    public static function createApplicationPart($application = null, $month = null){
        if( $application && $month ) {
            if( $contract_client = $application->contract_client ) {
                $count_parts = ApplicationPart::where('application_id', $application->id)
                    ->where('status', ApplicationPart::ACTIVE)
                    ->count();

                // agar bor bolsa deme data zaklucheniya oyiga aniq ochilgan (unga qarash shartmas)
                if( $count_parts > 0 ) {
                    $application_part = ApplicationPart::where('application_id', $application->id)
                        ->where('start_date','like','%' . $month . '%')
                        ->where('amount', '>', 0)
                        ->where('status', ApplicationPart::ACTIVE)
                        ->first();

                    // bu $month oyida ApplicationPart yoq bolsa qoshish
                    if( !$application_part ) {

                        // dogovor deystvuyet qilish vaqtini tekshirish: $month dogovorni ostanovka datasi bilan bir xil bolsa shungacha olish
                        if( date('Y-m', strtotime($month)) == date('Y-m', strtotime($contract_client->termination_date)) ) {
                            // priostonovka bormi yoki yo tekshirish
                            $contract_client_suspense_from_date = ContractClientSuspense::where('contract_client_id', $contract_client->id)
                                ->where('from_date','like','%' . $month . '%')
                                ->first();

                            $contract_client_suspense_to_date = ContractClientSuspense::where('contract_client_id', $contract_client->id)
                                ->where('to_date','like','%' . $month . '%')
                                ->first();

                            // priostonovka yo oyni boshidan oxirigacha yoki ortasidan boshlanish
                            if( $contract_client_suspense_from_date ) {
                                if( date('Y-m-d', strtotime($contract_client_suspense_from_date->from_date)) > date('Y-m-01', strtotime($month)) ) {
                                    $start_date = date('Y-m-01',strtotime($month));
                                    $stop_date = date('Y-m-d',strtotime('-1 day', strtotime($contract_client_suspense_from_date->from_date)));
                                    $all_day_quantity = (floatval(date_diff(date_create(date('Y-m-t', strtotime($month))), date_create(date('Y-m-01', strtotime($month))))->format("%a")) + 1);
                                    $day_to_stop_date_quantity = (floatval(date_diff(date_create($stop_date), date_create(date($start_date)))->format("%a")) + 1);
                                    $amount = (floatval($application->amount) / $all_day_quantity) * $day_to_stop_date_quantity;

                                    ApplicationPart::create([
                                        'application_id'    => $application->id,
                                        'start_date'        => $start_date,
                                        'stop_date'         => $stop_date,
                                        'amount'            => NumberHelper::round_up($amount, 2),
                                        'paid'              => 0,
                                        'status'            => ApplicationPart::ACTIVE,
                                    ]);
                                }
                                // agar 01 ga teng bosa deme bu oy polniy priostonovka bolgan oshanchun ApplicationPart yaratilmidi
                            }
                            else if( $contract_client_suspense_to_date ) {
                                if( date('Y-m-d', strtotime($contract_client_suspense_to_date->to_date)) < date('Y-m-d', strtotime($contract_client->termination_date)) ) {
                                    $start_date = date('Y-m-d',strtotime('+1 day', strtotime($contract_client_suspense_to_date->to_date)));
                                    $stop_date = date('Y-m-d',strtotime($contract_client->termination_date));
                                    $all_day_quantity = (floatval(date_diff(date_create(date('Y-m-t', strtotime($month))), date_create(date('Y-m-01', strtotime($month))))->format("%a")) + 1);
                                    $day_to_stop_date_quantity = (floatval(date_diff(date_create($stop_date), date_create($start_date))->format("%a")) + 1);
                                    $amount = (floatval($application->amount) / $all_day_quantity) * $day_to_stop_date_quantity;

                                    ApplicationPart::create([
                                        'application_id'    => $application->id,
                                        'start_date'        => $start_date,
                                        'stop_date'         => $stop_date,
                                        'amount'            => NumberHelper::round_up($amount, 2),
                                        'paid'              => 0,
                                        'status'            => ApplicationPart::ACTIVE,
                                    ]);
                                }
                            }
                            // agar priostonovka yoq bolsa
                            else {
                                $start_date = date('Y-m-01',strtotime($month));
                                $stop_date = date('Y-m-d',strtotime($contract_client->termination_date));
                                $all_day_quantity = (floatval(date_diff(date_create(date('Y-m-t', strtotime($month))), date_create(date('Y-m-01', strtotime($month))))->format("%a")) + 1);
                                $day_to_stop_date_quantity = (floatval(date_diff(date_create($stop_date), date_create($start_date))->format("%a")) + 1);
                                $amount = (floatval($application->amount) / $all_day_quantity) * $day_to_stop_date_quantity;

                                ApplicationPart::create([
                                    'application_id'    => $application->id,
                                    'start_date'        => $start_date,
                                    'stop_date'         => $stop_date,
                                    'amount'            => NumberHelper::round_up($amount, 2),
                                    'paid'              => 0,
                                    'status'            => ApplicationPart::ACTIVE,
                                ]);
                            }

                        }

                        // dogovor deystvuyet qilish vaqtini tekshirish: $month dogovorni ostanovka datasigacha bolsa
                        else if( (date('Y-m', strtotime($month)) < date('Y-m', strtotime($contract_client->termination_date))) && (date('Y-m', strtotime($month)) > date('Y-m', strtotime($contract_client->conclusion_date))) ) {

                            // priostonovka bormi yoki yo tekshirish
                            $contract_client_suspense_from_date = ContractClientSuspense::where('contract_client_id', $contract_client->id)
                                ->where('from_date','like','%' . $month . '%')
                                ->first();

                            $contract_client_suspense_to_date = ContractClientSuspense::where('contract_client_id', $contract_client->id)
                                ->where('to_date','like','%' . $month . '%')
                                ->first();

                            // priostonovka yo oyni boshidan oxirigacha yoki ortasidan boshlanish
                            if( $contract_client_suspense_from_date ) {
                                if( date('Y-m-d', strtotime($contract_client_suspense_from_date->from_date)) > date('Y-m-01', strtotime($month)) ) {
                                    $start_date = date('Y-m-01',strtotime($month));
                                    $stop_date = date('Y-m-d',strtotime('-1 day', strtotime($contract_client_suspense_from_date->from_date)));
                                    $all_day_quantity = (floatval(date_diff(date_create(date('Y-m-t', strtotime($month))), date_create(date('Y-m-01', strtotime($month))))->format("%a")) + 1);
                                    $day_to_stop_date_quantity = (floatval(date_diff(date_create($stop_date), date_create(date($start_date)))->format("%a")) + 1);
                                    $amount = (floatval($application->amount) / $all_day_quantity) * $day_to_stop_date_quantity;

                                    ApplicationPart::create([
                                        'application_id'    => $application->id,
                                        'start_date'        => $start_date,
                                        'stop_date'         => $stop_date,
                                        'amount'            => NumberHelper::round_up($amount, 2),
                                        'paid'              => 0,
                                        'status'            => ApplicationPart::ACTIVE,
                                    ]);
                                }
                                // agar 01 ga teng bosa deme bu oy polniy priostonovka bolgan oshanchun ApplicationPart yaratilmidi
                            }
                            else if( $contract_client_suspense_to_date ) {
                                $start_date = date('Y-m-d',strtotime('+1 day', strtotime($contract_client_suspense_to_date->to_date)));
                                $stop_date = date('Y-m-t',strtotime($month));
                                $all_day_quantity = (floatval(date_diff(date_create(date('Y-m-t', strtotime($month))), date_create(date('Y-m-01', strtotime($month))))->format("%a")) + 1);
                                $day_to_stop_date_quantity = (floatval(date_diff(date_create($stop_date), date_create($start_date))->format("%a")) + 1);
                                $amount = (floatval($application->amount) / $all_day_quantity) * $day_to_stop_date_quantity;

                                ApplicationPart::create([
                                    'application_id'    => $application->id,
                                    'start_date'        => $start_date,
                                    'stop_date'         => $stop_date,
                                    'amount'            => NumberHelper::round_up($amount, 2),
                                    'paid'              => 0,
                                    'status'            => ApplicationPart::ACTIVE,
                                ]);
                            }
                            // agar priostonovka yoq bolsa toliq oy ga ochish
                            else {
                                $start_date = date('Y-m-01',strtotime($month));
                                $stop_date = date('Y-m-t',strtotime($month));
                                $amount = $application->amount;

                                ApplicationPart::create([
                                    'application_id'    => $application->id,
                                    'start_date'        => $start_date,
                                    'stop_date'         => $stop_date,
                                    'amount'            => NumberHelper::round_up($amount, 2),
                                    'paid'              => 0,
                                    'status'            => ApplicationPart::ACTIVE,
                                ]);
                            }
                        }
                    }
                }
                // agar ApplicationPart yoq bolsa qoshish bunda dogovor okanchaniyani qarash shartmas
                else {
                    if( date('Y-m', strtotime($month)) == date('Y-m', strtotime($contract_client->conclusion_date)) ) {
                        // priostonovkalaga qarash
                        $contract_client_suspense_from_date = ContractClientSuspense::where('contract_client_id', $contract_client->id)
                            ->where('from_date','like','%' . $month . '%')
                            ->first();

                        // priostonovka dogovor zaklucheniyasidan oldinmi yoki keyin tekshirish
                        if( $contract_client_suspense_from_date ) {
                            if( date('Y-m-d', strtotime($contract_client_suspense_from_date->from_date)) > date('Y-m-d', strtotime($contract_client->conclusion_date)) ) {
                                $start_date = date('Y-m-d',strtotime($contract_client->conclusion_date));
                                $stop_date = date('Y-m-d',strtotime('-1 day', strtotime($contract_client_suspense_from_date->from_date)));
//                                $all_day_quantity = (floatval(date_diff(date_create(date('Y-m-t', strtotime($month))), date_create(date('Y-m-01', strtotime($month))))->format("%a")) + 1);
//                                $day_to_stop_date_quantity = (floatval(date_diff(date_create($stop_date), date_create(date($start_date)))->format("%a")) + 1);
                                if( date('Y-m-15', strtotime($contract_client->conclusion_date)) >= $start_date ) {
                                    $amount = floatval($application->amount);
                                }
                                else {
                                    $amount = floatval($application->amount) / 2;
                                }

                                ApplicationPart::create([
                                    'application_id'    => $application->id,
                                    'start_date'        => $start_date,
                                    'stop_date'         => $stop_date,
                                    'amount'            => NumberHelper::round_up($amount, 2),
                                    'paid'              => 0,
                                    'status'            => ApplicationPart::ACTIVE,
                                ]);
                            }
                        }
                        // agar priostonovka yoq bolsa dogovor sanasidan oy oxirigacha ochish
                        else {
                            $start_date = date('Y-m-d',strtotime($contract_client->conclusion_date));
                            $stop_date = date('Y-m-t',strtotime($month));
//                            $all_day_quantity = (floatval(date_diff(date_create(date('Y-m-t', strtotime($month))), date_create(date('Y-m-01', strtotime($month))))->format("%a")) + 1);
//                            $day_to_stop_date_quantity = (floatval(date_diff(date_create($stop_date), date_create(date($start_date)))->format("%a")) + 1);
                            if( date('Y-m-15', strtotime($contract_client->conclusion_date)) >= $start_date ) {
                                $amount = floatval($application->amount);
                            }
                            else {
                                $amount = floatval($application->amount) / 2;
                            }

                            ApplicationPart::create([
                                'application_id'    => $application->id,
                                'start_date'        => $start_date,
                                'stop_date'         => $stop_date,
                                'amount'            => NumberHelper::round_up($amount, 2),
                                'paid'              => 0,
                                'status'            => ApplicationPart::ACTIVE,
                            ]);
                        }
                    }
                    // dogovor oldingi oydan yoki undanam oldin deystvuyet qilgan, part esa endi ochilvotti
                    else if( date('Y-m', strtotime($month)) > date('Y-m', strtotime($contract_client->conclusion_date)) ) {
                        // 1-dogo-> zaklucheniya qilgan oyga ApplicationPart ochib keyin boshqa oylada priostonovkalaga qarash

                        $month_of_contract_conclusion_date = date('Y-m', strtotime($contract_client->conclusion_date));

                        //$months_qty = (floatval(date_diff(date_create(date('Y-m', strtotime($month))), date_create(date('Y-m', strtotime($month_of_contract_conclusion_date))))->format("%m")) + 1);
                        $ts1 = strtotime($month_of_contract_conclusion_date);
                        $ts2 = strtotime($month);
                        $year1 = date('Y', $ts1);
                        $year2 = date('Y', $ts2);
                        $month1 = date('m', $ts1);
                        $month2 = date('m', $ts2);
                        $months_qty = (($year2 - $year1) * 12) + ($month2 - $month1) + 1;

                        for( $i = 0; $i < $months_qty; $i++ ) {
                            $current_month = date('Y-m', strtotime("+{$i} month", strtotime(date('Y-m', strtotime($contract_client->conclusion_date)))));

                            if( $i == 0 ) {
                                $start_date = date('Y-m-d',strtotime($contract_client->conclusion_date));
                                $stop_date = date('Y-m-t',strtotime($contract_client->conclusion_date));
//                                $all_day_quantity = (floatval(date_diff(date_create(date('Y-m-t', strtotime($contract_client->conclusion_date))), date_create(date('Y-m-01', strtotime($contract_client->conclusion_date))))->format("%a")) + 1);
//                                $day_to_stop_date_quantity = (floatval(date_diff(date_create($stop_date), date_create(date($start_date)))->format("%a")) + 1);
                                if( date('Y-m-15', strtotime($contract_client->conclusion_date)) >= $start_date ) {
                                    $amount = floatval($application->amount);
                                }
                                else {
                                    $amount = floatval($application->amount) / 2;
                                }

                                ApplicationPart::create([
                                    'application_id'    => $application->id,
                                    'start_date'        => $start_date,
                                    'stop_date'         => $stop_date,
                                    'amount'            => NumberHelper::round_up($amount, 2),
                                    'paid'              => 0,
                                    'status'            => ApplicationPart::ACTIVE,
                                ]);
                            }
                            else {
                                // priostonovkalaga qarash

                                $contract_client_suspense_from_date = ContractClientSuspense::where('contract_client_id', $contract_client->id)
                                    ->where('from_date','like','%' . $current_month . '%')
                                    ->first();

                                $contract_client_suspense_to_date = ContractClientSuspense::where('contract_client_id', $contract_client->id)
                                    ->where('to_date','like','%' . $current_month . '%')
                                    ->first();

                                // priostonovka yo oyni boshidan oxirigacha yoki ortasidan boshlanish
                                if( $contract_client_suspense_from_date ) {
                                    if( date('Y-m-d', strtotime($contract_client_suspense_from_date->from_date)) > date('Y-m-01', strtotime($current_month)) ) {
                                        $start_date = date('Y-m-01',strtotime($current_month));
                                        $stop_date = date('Y-m-d',strtotime('-1 day', strtotime($contract_client_suspense_from_date->from_date)));
                                        $all_day_quantity = (floatval(date_diff(date_create(date('Y-m-t', strtotime($current_month))), date_create(date('Y-m-01', strtotime($current_month))))->format("%a")) + 1);
                                        $day_to_stop_date_quantity = (floatval(date_diff(date_create($stop_date), date_create(date($start_date)))->format("%a")) + 1);
                                        $amount = (floatval($application->amount) / $all_day_quantity) * $day_to_stop_date_quantity;

                                        ApplicationPart::create([
                                            'application_id'    => $application->id,
                                            'start_date'        => $start_date,
                                            'stop_date'         => $stop_date,
                                            'amount'            => NumberHelper::round_up($amount, 2),
                                            'paid'              => 0,
                                            'status'            => ApplicationPart::ACTIVE,
                                        ]);
                                    }
                                    // agar 01 ga teng bosa deme bu oy polniy priostonovka bolgan oshanchun ApplicationPart yaratilmidi
                                }
                                else if( $contract_client_suspense_to_date ) {
                                    $start_date = date('Y-m-d',strtotime('+1 day', strtotime($contract_client_suspense_to_date->to_date)));
                                    $stop_date = date('Y-m-t',strtotime($current_month));
                                    $all_day_quantity = (floatval(date_diff(date_create(date('Y-m-t', strtotime($current_month))), date_create(date('Y-m-01', strtotime($current_month))))->format("%a")) + 1);
                                    $day_to_stop_date_quantity = (floatval(date_diff(date_create($stop_date), date_create($start_date))->format("%a")) + 1);
                                    $amount = (floatval($application->amount) / $all_day_quantity) * $day_to_stop_date_quantity;

                                    ApplicationPart::create([
                                        'application_id'    => $application->id,
                                        'start_date'        => $start_date,
                                        'stop_date'         => $stop_date,
                                        'amount'            => NumberHelper::round_up($amount, 2),
                                        'paid'              => 0,
                                        'status'            => ApplicationPart::ACTIVE,
                                    ]);
                                }
                                // agar priostonovka yoq bolsa oy boshidan oxirigacha ochish
                                else {
                                    $start_date = date('Y-m-01',strtotime($current_month));
                                    $stop_date = date('Y-m-t',strtotime($current_month));
                                    $amount = $application->amount;

                                    ApplicationPart::create([
                                        'application_id'    => $application->id,
                                        'start_date'        => $start_date,
                                        'stop_date'         => $stop_date,
                                        'amount'            => NumberHelper::round_up($amount, 2),
                                        'paid'              => 0,
                                        'status'            => ApplicationPart::ACTIVE,
                                    ]);
                                }
                            }
                        }
                    }
                    // dogovor keyingi oydan deystvuyet qiladi, part esa hozir ochilvotti yani oldindan tolov yoki CronJob ishlavotti
                    else if( date('Y-m', strtotime($month)) < date('Y-m', strtotime($contract_client->conclusion_date)) ) {
                        // bunda faqat dogovor zaklucheniyasi datasidan boshlab oyni oxirigacha ApplicationPart ochish

                        $start_date = date('Y-m-d',strtotime($contract_client->conclusion_date));
                        $stop_date = date('Y-m-t',strtotime($contract_client->conclusion_date));
//                        $all_day_quantity = (floatval(date_diff(date_create(date('Y-m-t', strtotime($contract_client->conclusion_date))), date_create(date('Y-m-01', strtotime($contract_client->conclusion_date))))->format("%a")) + 1);
//                        $day_to_stop_date_quantity = (floatval(date_diff(date_create($stop_date), date_create(date($start_date)))->format("%a")) + 1);
                        if( date('Y-m-15', strtotime($contract_client->conclusion_date)) >= $start_date ) {
                            $amount = floatval($application->amount);
                        }
                        else {
                            $amount = floatval($application->amount) / 2;
                        }

                        ApplicationPart::create([
                            'application_id'    => $application->id,
                            'start_date'        => $start_date,
                            'stop_date'         => $stop_date,
                            'amount'            => NumberHelper::round_up($amount, 2),
                            'paid'              => 0,
                            'status'            => ApplicationPart::ACTIVE,
                        ]);
                    }
                }
            }
        }
    }
}

