<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class SaleRequest extends FormRequest
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
            'level_id'              => 'required|exists:levels,id',
            'items'                 => 'present|array',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|numeric|min:0|not_in:0',
            'additional_materials'          => 'present|array',
            'additional_materials.*.material_id'  => 'required|exists:materials,id',
            'additional_materials.*.quantity'     => 'required|numeric|min:0|not_in:0',
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
