<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ScoreRequest extends FormRequest
{
    use ResponseAble;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'currency_id'          => 'required|exists:currencies,id',
            'name'                 => 'required|string|max:255',
            'branch_name'          => 'nullable|string|max:255',
            'mfo'                  => 'nullable|string|max:255',
            'number'               => 'nullable|string|max:255',
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
