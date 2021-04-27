<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserRequest extends FormRequest
{
    use ResponseAble;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if (!$this->user)
        {
            $rules = [
                'password' => 'required',
                'phone' => 'required|min:13|max:13|unique:users',
                'pin_code' => 'nullable|min:6|max:6|unique:users,pin_code',
            ];
        }else{
            $rules = [
                'phone' => 'required|min:13|max:13|unique:users,phone,' . $this->user->id,
                'pin_code' => 'nullable|min:6|max:6|unique:users,pin_code,' . $this->user->id,
            ];
        }

        return $rules;
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
