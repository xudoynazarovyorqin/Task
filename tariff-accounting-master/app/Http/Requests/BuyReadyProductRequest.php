<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class BuyReadyProductRequest extends FormRequest
{
    use ResponseAble;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'datetime'              => 'required|date',
            'provider_id'           => 'required|exists:providers,id',
            'contract_provider_id'  => 'nullable|exists:contract_providers,id',
            'status_id'             => 'required|exists:states,id',
            'items'                 => 'present|array',
            'items.*.product_id'    => 'required|distinct|exists:products,id',
            'items.*.currency_id'   => 'required|exists:currencies,id',
            'items.*.rate'          => 'required|numeric|min:0|not_in:0',
            'items.*.price'         => 'required|numeric|min:0|not_in:0',
            'items.*.qty_weight'    => 'required|numeric|min:0|not_in:0',
        ];
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
