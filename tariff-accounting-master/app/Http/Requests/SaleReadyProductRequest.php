<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SaleReadyProductRequest extends FormRequest
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
            'client_id'             => 'required|exists:clients,id',
            'state_id'              => 'required|exists:states,id',
            'items'                 => 'present|array',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.selling_price' => 'required|numeric|min:0',
            'items.*.quantity'      => 'required|numeric|min:0',
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
