<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class AssemblyRequest extends FormRequest
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
            'items'                 => 'present|array',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|numeric',
            'additional_materials'          => 'present|array',
            'additional_materials.*.material_id'  => 'required|exists:materials,id',
            'additional_materials.*.quantity'     => 'required|numeric',
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
