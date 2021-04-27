<?php

namespace App\Http\Requests;

use App\ApplicationPart;
use App\ContractClient;
use App\ContractClientSuspense;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ContractClientRequest extends FormRequest
{
    use ResponseAble;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //'number'            => 'required|unique:contract_clients' . ($this->contractClient ? (',number,' . $this->contractClient->id) : ''),
            'number' => [
                'required',
                Rule::unique(ContractClient::TABLE_NAME,'number')->ignore($this->contractClient ? $this->contractClient->id : '')->whereNull('deleted_at')
            ],
            'client_id'         => 'required|exists:clients,id',
            'begin_date'        => 'required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $conclusion_date = $this->get('conclusion_date',null);
            $termination_date = $this->get('termination_date',null);

            // agar dog->zaklucheniya datalari kiritilgan bolsa tekshirish
            if( $conclusion_date && $termination_date ) {

                // agar izmenit qvotganda data zaklucheniya ozgarvotgan bolsa
//                if( $this->contractClient ) {
//                    // agar eski datasi bilan yengi bir xil bolmasa
//                    if( date('Y-m-d', strtotime($conclusion_date)) != date('Y-m-d', strtotime($this->contractClient->conclusion_date)) ) {
//                        // agar zayavka biriktirilgan bolsa
//                        if ($this->contractClient->application) {
//                            $total_paid = ApplicationPart::where('application_id', $this->contractClient->application->id)->sum('paid');
//
//                            // tolov qilingan bolsa xatolik qaytarish
//                            if( $total_paid > 0 ) {
//                                $validator->errors()->add('conclusion_date', __('messages.The attached application to this contract is payment. Impossible to change the date of imprisonment'));
//                            }
//
//                            /*** yoki 2-variant ***/
//                            // ContractClientController->updateda update qvotganda ozgarganligini tekshirib agar ozgargan bolsa tolangan summani olib dogovor balansiga qaytarib Payment la ula orqali ACTIVE ApplicationPart lani ochirib boshqattan yengi chislo boyicha yaratib chiqish
//                        }
//                    }
//                }

                // Dogovorni boshlanish va tugash vaqtlari togri kiritilganini tekshirish
                if ( date('Y-m-d', strtotime($conclusion_date)) > date('Y-m-d', strtotime($termination_date)) ) {
                    $validator->errors()->add('conclusion_date', __('messages.Conclusion date and termination date are not correct'));
                }

                $contract_client_suspenses = $this->get('contract_client_suspenses',null);

                if ( is_array($contract_client_suspenses) && count($contract_client_suspenses) > 0 ) {
                    // Dogovor applicationga biriktirilganmi tekshirish bomasa priostonovka ocha olmaslik
                    if ($this->contractClient) {
                        if ($this->contractClient->application) {
                            foreach ($contract_client_suspenses as $key => $item){

                                // Priostonovkani boshlanish va tugash vaqtlari togri kiritlganini tekshirish
                                if ( date('Y-m-d', strtotime($item['from_date'])) > date('Y-m-d', strtotime($item['to_date'])) ) {
                                    $validator->errors()->add('items.' . $key, __('messages.Suspension dates are not correct'));
                                }

                                // Priostonovka dogovorni deystvuyet qiladigan periodidami tekshirish
                                if ( (date('Y-m-d', strtotime($item['from_date'])) < date('Y-m-d', strtotime($conclusion_date)))
                                    || (date('Y-m-d', strtotime($item['from_date'])) > date('Y-m-d', strtotime($termination_date)))
                                    || (date('Y-m-d', strtotime($item['to_date'])) < date('Y-m-d', strtotime($conclusion_date)))
                                    || (date('Y-m-d', strtotime($item['to_date'])) > date('Y-m-d', strtotime($termination_date))) ) {
                                    $validator->errors()->add('items.' . $key, __('messages.Suspension is not included in the contract'));
                                }


                                $from_date = date('Y-m-d', strtotime($item['from_date']));
                                $to_date = date('Y-m-d', strtotime($item['to_date']));

                                // Kiritilayotgan priostonovkaga mos bazada boshqa priostonovka yomi
                                $is_have_suspense = ContractClientSuspense::where('contract_client_id', $this->contractClient->id)
                                    ->where(function($query) use ($from_date, $to_date) {
                                        return $query->orwhere(function ($query) use ($from_date) {
                                            return $query->whereDate('from_date','<=',$from_date)->whereDate('to_date','>=',$from_date);
                                        })->orwhere(function ($query) use ($to_date) {
                                            return $query->whereDate('from_date','<=',$to_date)->whereDate('to_date','>=',$to_date);
                                        })->orwhere(function ($query) use ($from_date, $to_date) {
                                            return $query->whereDate('from_date','<=',$from_date)->whereDate('to_date','>=',$to_date);
                                        });
                                    })->first();

                                if( $is_have_suspense ) {
                                    $validator->errors()->add('items.' . $key, __('messages.In this period has a suspension'));
                                }


                                // Priostovkani from va to larining oylariga qarab ApplicationPart active bor bolsa xatolik berish
                                $application_part_from_date = ApplicationPart::where('application_id', $this->contractClient->application->id)
                                    ->where('start_date','like','%' . date('Y-m', strtotime($item['from_date'])) . '%')
                                    ->where('status', ApplicationPart::ACTIVE)
                                    ->first();

                                $application_part_to_date = ApplicationPart::where('application_id', $this->contractClient->application->id)
                                    ->where('start_date','like','%' . date('Y-m', strtotime($item['to_date'])) . '%')
                                    ->where('status', ApplicationPart::ACTIVE)
                                    ->first();

                                if( $application_part_from_date || $application_part_to_date ) {
                                    $validator->errors()->add('items.' . $key, __('messages.The suspension period has a payment. Unable to add suspend'));
                                }
                            }
                        }
                        else {
                            $validator->errors()->add('items', __('messages.The contract is not tied to the Application. Unable to add suspend'));
                        }
                    }
                    else {
                        $validator->errors()->add('items', __('messages.The contract is not tied to the Application. Unable to add suspend'));
                    }
                }
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        return $this->sendError(
            $errors,
            __('messages.validation_error'),
            ApiResponse::VALIDATION_ERROR
        );
    }
}
