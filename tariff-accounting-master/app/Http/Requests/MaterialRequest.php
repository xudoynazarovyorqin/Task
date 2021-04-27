<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class MaterialRequest extends FormRequest
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
            'price'                 => 'required|numeric|min:0',
            'price_currency_id'     => 'required|exists:currencies,id',
            'measurement_id'        => 'required|exists:measurements,id',
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
