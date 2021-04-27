<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ProductRequest extends FormRequest
{
    use ResponseAble;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'                  => 'required|string|max:250',
            'purchase_price'        => 'required|numeric|min:0',
            'purchase_currency_id'  => 'required|exists:currencies,id',
            'selling_price'         => 'required|numeric|min:0',
            'selling_currency_id'  => 'required|exists:currencies,id',

            'semi_products'         => 'present|array',
            'semi_products.*.semi_product_id'   => 'required|exists:products,id',
            'semi_products.*.quantity'     => 'required|numeric|min:0',

            'product_materials'                => 'present|array',
            'product_materials.*.material_id'  => 'required|exists:materials,id',
            'product_materials.*.quantity'     => 'required|numeric|min:0',
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
