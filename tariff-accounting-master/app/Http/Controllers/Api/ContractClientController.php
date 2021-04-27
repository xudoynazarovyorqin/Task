<?php

namespace App\Http\Controllers\Api;

use App\Application;
use App\ApplicationPart;
use App\ContractClientProducts;
use App\ContractClientSuspense;
use App\Events\Money\Mudofa\DistributionMoneyEvent;
use App\Exceptions\ApiModelNotFoundException;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Controller;
use App\ContractClient;
use App\Http\Requests\ContractClientRequest;
use App\Http\Resources\Inventory\ContractClientCollection;
use App\Payment;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ContractClientController extends Controller
{

    protected $response;

    protected $per_page;

    protected $contractClient;

    protected $apiResponse;

    private $message_not_found;

    public function __construct(Response $response, ApiResponse $apiResponse, ContractClient $contractClient)
    {
        $this->middleware('auth:api');
        $this->middleware('permission:contractClients.create')->only('store');
        $this->middleware('permission:contractClients.show')->only('show');
        $this->middleware('permission:contractClients.update')->only('update');
        $this->middleware('permission:contractClients.delete')->only(['destroy']);

        $this->response = $response;
        $this->contractClient = $contractClient;
        $this->apiResponse = $apiResponse;
        $this->per_page = request()->get('per_page',10);
        $this->message_not_found = trans('messages.not_found',['name' => __('messages.contract')]);
    }

    public function index()
    {
        $contractClients = $this->contractClient;
        if ($str = \request('search'))
        {
            $contractClients = $contractClients->search($str);
        }

        $contractClients = $contractClients->filter()->sort();

        $contractClients = $contractClients->select(ContractClient::TABLE_NAME . '.id', 'number', 'begin_date', 'client_id', 'status_id', 'sum', 'remainder',
            'comment', 'parent_id', 'paid', 'conclusion_date', 'termination_date',
            ContractClient::TABLE_NAME . '.created_at', ContractClient::TABLE_NAME . '.updated_at');

        $contractClients = $contractClients->with([
            'client:id,name',
            'status:id,state,status',
            'application:id,console_number,contract_client_id',
        ]);

        $contractClients = $contractClients->paginate($this->per_page);

        return $this->response->withArray([
           'result' => [
               'success' => true,
               'data' => [
                   'contractClients'  => $contractClients->items(),
                   'pagination'    => [
                       'total'         => $contractClients->total(),
                       'current_page'  => $contractClients->currentPage()
                   ],
               ]
           ]
        ]);
    }

    public function inventory()
    {
        $contractClients = $this->contractClient;
        if ($str = \request('search'))
        {
            $contractClients = $contractClients->search($str);
        }

        if ($str = \request('client_id'))
        {
            $contractClients = $contractClients->searchByClientId($str);
        }

        $contractClients = $contractClients->filter();
        $contractClients = $contractClients->orderByIdDesc()->paginate($this->per_page);

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'  => new ContractClientCollection($contractClients)
            ],
        ]);
    }

    public function show(ContractClient $contractClient)
    {
        return $this->response->withArray([
            'result' => [
                'success' => true,
                'data'    => [
                    'contractClient' => new \App\Http\Resources\ContractClient($contractClient,true)
                ]
            ]
        ]);
    }

    public function store(ContractClientRequest $request)
    {
        $contractClient = ContractClient::create([
            'number'             => $request['number'],
            'begin_date'         => $request['begin_date'],
            'client_id'          => $request['client_id'],
            'status_id'          => $request['status_id'],
            'conclusion_date'    => $request['conclusion_date'],
            'termination_date'   => $request['termination_date'],
            'sum'                => $request['sum'],
            'comment'            => $request['comment'],
            'parent_id'          => $request['parent_id']
        ]);

        if ($contract_client_suspenses = $request['contract_client_suspenses'])
        {
            if (is_array($contract_client_suspenses))
            {
                foreach ($contract_client_suspenses as $item)
                {
                    ContractClientSuspense::create([
                        'contract_client_id'    => $contractClient->id,
                        'from_date'             => $item['from_date'],
                        'to_date'               => $item['to_date'],
                        'comment'               => $item['comment'],
                    ]);
                }
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.store_success',['name' => __('messages.contract')]),
                'data'    => [
                    'contractClient' => new \App\Http\Resources\ContractClient($contractClient,true)
                ]
            ]
        ]);
    }

    public function update(ContractClientRequest $request, ContractClient $contractClient)
    {
        $old_conclusion_date = date('Y-m-d', strtotime($contractClient->conclusion_date));
        $new_conclusion_date = date('Y-m-d', strtotime($request['conclusion_date']));

        $contractClient->update([
            'number'             => $request['number'],
            'begin_date'         => $request['begin_date'],
            'client_id'          => $request['client_id'],
            'status_id'          => $request['status_id'],
            'conclusion_date'    => $request['conclusion_date'],
            'termination_date'   => $request['termination_date'],
            'sum'                => $request['sum'],
            'comment'            => $request['comment'],
            'parent_id'          => $request['parent_id']
        ]);

        // agar data zaklucheniya ozgartirilvotgan bolsa
        if( $old_conclusion_date != $new_conclusion_date ) {

            // agar zayavka biriktirilgan bolsa
            if( $application = $contractClient->application ) {

                // 1) dogovor balansga tolangan summalani qaytarish
                $total_paid = ApplicationPart::where('application_id', $application->id)->sum('paid');
                $contractClient->remainder += $total_paid;
                $contractClient->update();


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
                if( $new_conclusion_date && $request['termination_date'] ) {
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

        if ($contract_client_suspenses = $request['contract_client_suspenses'])
        {
            if (is_array($contract_client_suspenses))
            {
                foreach ($contract_client_suspenses as $item)
                {
                    ContractClientSuspense::create([
                        'contract_client_id'    => $contractClient->id,
                        'from_date'             => $item['from_date'],
                        'to_date'               => $item['to_date'],
                        'comment'               => $item['comment'],
                    ]);
                }
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.update_success',['name' => __('messages.contract')]),
                'data'    => [
                    'contractClient' => new \App\Http\Resources\ContractClient($contractClient,true)
                ]
            ]
        ]);
    }

    public function destroy(ContractClient $contractClient)
    {
        if( $contractClient->application ) {
            return $this->response->withArray([
                'result' => [
                    'success' => false
                ],
                'error' => [
                    'code' => ApiResponse::PAGE_NOT_FOUND,
                    'message'   => trans('messages.The application is attached to the contract. Unable to delete a contract'),
                ]
            ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
        }
        else {
            try {
                $contractClient->delete();
            } catch (\Exception $e) {

            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => __('messages.destroy_success',['name' => __('messages.contract')]),
                'data'    => [
                    'message' => trans('messages.successfully_deleted')
                ]
            ]
        ]);
    }

    public function deleteSuspense(Request $request){
        if (!$suspense = ContractClientSuspense::find($request['suspense_id'])){
            throw new ApiModelNotFoundException(trans('messages.not_found',['name' => trans('messages.suspense')]));
        }


        if( $contract_client = $suspense->contract_client ) {
            if( $application = $contract_client->application ) {
                $application_part_from_date = ApplicationPart::where('application_id', $application->id)
                    ->where('start_date','like','%' . date('Y-m', strtotime($suspense->from_date)) . '%')
                    ->where('status', ApplicationPart::ACTIVE)
                    ->first();

                $application_part_to_date = ApplicationPart::where('application_id', $application->id)
                    ->where('start_date','like','%' . date('Y-m', strtotime($suspense->to_date)) . '%')
                    ->where('status', ApplicationPart::ACTIVE)
                    ->first();

                if( $application_part_from_date || $application_part_to_date ) {
                    return $this->response->withArray([
                        'result' => [
                            'success' => false
                        ],
                        'error' => [
                            'code' => ApiResponse::PAGE_NOT_FOUND,
                            'message'   => trans('messages.The suspension period has a payment. Unable to delete suspend'),
                        ]
                    ])->setStatusCode(ApiResponse::PAGE_NOT_FOUND);
                }

                $suspense->delete();
            }
        }

        return $this->response->withArray([
            'result' => [
                'success' => true,
                'message' => trans('messages.destroy_success',['name' => trans('messages.suspense')]),
            ]
        ]);
    }

    public function print(){
        if (!$contrctClient = $this->contractClient->find(\request()->get('id'))){
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

        $print_svg = file_get_contents(storage_path('app/public/mudofa/svg/contract_pechat.html'), true);
        //$print_svg = file_get_contents(storage_path('app/public/mudofa/svg/pichat.svg'), false);

/*        return view('contract_client_print', ['contrctClient' => $contrctClient, 'xml' => "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?>", 'doctype' => "<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.1//EN\" \"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd\">"]);*/
        return view('contract_client_print', ['contrctClient' => $contrctClient, 'print_svg' => $print_svg]);
    }
}
