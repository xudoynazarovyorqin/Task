<?php

namespace App\Http\Requests;

use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use App\Score;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class TransactionRequest extends FormRequest
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
            'payment_type_id'      => 'required|exists:payment_types,id',
            'amount'               => 'required|numeric|min:0|not_in:0',
            'rate'                 => 'required|numeric|min:0|not_in:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $real_amount = floatval($this->get('amount')) * floatval($this->get('rate'));
            $items = $this->get('relatedItems',null);
            if ($items){
                $sum = 0;
                foreach($items as $item){
                    if(isset($item['paying_amount']))
                        $sum += $item['paying_amount'];
                }
                if ($sum > $real_amount){
                    $validator->errors()->add('relatedItems', __('messages.The distributed amount exceeds the payment amount.'));
                }
            }

            if ($score_id = $this->get('score_id',null)){
                if($score = Score::find($score_id)){
                    if ($score->currency_id != $this->get('currency_id')){
                        $validator->errors()->add('currency_id', __('messages.Payment currency and score currency not same'));
                    }
                }else{
                    $validator->errors()->add('score_id', __('messages.not_found',['name' => __('message.Score')]));
                };
            }
        });
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
