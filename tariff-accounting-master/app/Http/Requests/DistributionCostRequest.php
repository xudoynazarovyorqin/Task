<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use App\Material;
use App\Product;
use App\Transaction;
use App\WarehouseMaterial;
use App\WarehouseProduct;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DistributionCostRequest extends FormRequest
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
        $data = [
            'datetime'  => 'required|date',
            'type'      => [
                'required',
                Rule::in([Product::TABLE_NAME,Material::TABLE_NAME])
            ],
            'from_date'  => 'required|date',
            'to_date'  => 'required|date',
            'items'        => 'required|array',
            'transactions'        => 'required|array',
            'transactions.*.transaction_id' => [
                'required',
                Rule::exists(Transaction::TABLE_NAME,'id')->whereNull('deleted_at')
            ],
        ];

        if ($this['type'] == Material::TABLE_NAME) {
            $data['items.*.additional_priceable_id'] = [
                'required',
                Rule::exists(WarehouseMaterial::TABLE_NAME,'id')->whereNull('deleted_at')
            ];
        }

        if ($this['type'] == Product::TABLE_NAME) {
            $data['items.*.additional_priceable_id'] = [
                'required',
                Rule::exists(WarehouseProduct::TABLE_NAME,'id')->whereNull('deleted_at')
            ];
        }

        return $data;
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
