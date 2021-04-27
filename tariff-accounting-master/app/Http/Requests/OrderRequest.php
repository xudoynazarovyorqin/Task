<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class OrderRequest extends FormRequest
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
            'order_products'        => 'present|array',
            'order_products.*.product_id'   => 'required|exists:products,id',
            'order_products.*.price'        => 'required|numeric|min:0|not_in:0',
            'order_products.*.quantity'     => 'required|numeric|min:0|not_in:0',
            'additional_materials'          => 'present|array',
            'additional_materials.*.material_id'  => 'required|exists:materials,id',
            'additional_materials.*.quantity'     => 'required|numeric|min:0|not_in:0',
            'order_costs'                   => 'present|array',
            'order_costs.*.cost_id'         => 'required|exists:costs,id',
            'order_costs.*.amount'          => 'required|numeric|min:0|not_in:0',
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
