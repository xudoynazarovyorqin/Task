<?php

namespace App\Http\Requests;

use App\Application;
use App\ApplicationPart;
use App\ContractClient;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ApplicationRequest extends FormRequest
{
    use ResponseAble;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $client_id = $this->get('client_id',null);

        return [
            'datetime'              => 'required|date',
            'client_id'             => 'required|exists:clients,id',
            //'contract_client_id'    => 'required|unique:applications' . ($this->application ? (',contract_client_id,' . $this->application->id) : ''),
            'contract_client_id' => [
                'required',
                Rule::unique(Application::TABLE_NAME,'contract_client_id')->ignore($this->application ? $this->application->id : '')->whereNull('deleted_at'),
                Rule::exists(ContractClient::TABLE_NAME, 'id')->where(function ($query) use($client_id) {
                    $query->where('client_id', $client_id);
                }),
            ],
            'status_id'             => 'required|exists:states,id',
            //'console_number'        => 'required|unique:applications' . ($this->application ? (',console_number,' . $this->application->id) : ''),
            'console_number' => [
                'required',
                Rule::unique(Application::TABLE_NAME,'console_number')->ignore($this->application ? $this->application->id : '')->whereNull('deleted_at')
            ],
            'application_services'              => 'present|array',
            'application_services.*.service_id' => 'required|exists:services,id',
            'application_services.*.price'      => 'required|numeric|min:0|not_in:0',
        ];
    }

//    public function withValidator($validator)
//    {
//        $validator->after(function ($validator) {
//            // izmenit qilvotganda agar tarif ozgartirilvotti lekin oplata qilingan bolsa xatolik berish
//            if( $this->application ) {
//                $application_services = $this->get('application_services',null);
//
//                if( is_array($application_services) && count($application_services) > 0 ) {
//                    $total_paid = ApplicationPart::where('application_id', $this->application->id)->sum('paid');
//                    // agar oplata bosa xatolik
//                    if( $total_paid > 0 ) {
//                        $validator->errors()->add('application_services', trans('messages.There is a payment in this application. Unable to create service'));
//                    }
//                    // aks holda eski application_part lani ochirib yuborish
//                    else {
//                        ApplicationPart::where('application_id', $this->application->id)->where('status', ApplicationPart::ACTIVE)->delete();
//                    }
//                }
//            }
//        });
//    }

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
