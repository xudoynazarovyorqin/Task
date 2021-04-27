<?php


namespace App\Http\Requests\Mudofa;


use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use App\Score;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
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
            'transactionable_id'        => 'required|exists:applications,id',
            'amount'                    => 'required|numeric|min:0|not_in:0',
        ];
    }

    public function withValidator($validator)
    {
//        $validator->after(function ($validator) {
//            $real_amount = floatval($this->get('amount'));
//            $items = $this->get('relatedItems',null);
//            if ($items){
//                $sum = 0;
//                foreach($items as $item){
//                    if(isset($item['paying_amount']))
//                        $sum += $item['paying_amount'];
//                }
//                if ($sum > $real_amount){
//                    $validator->errors()->add('relatedItems', __('messages.The distributed amount exceeds the payment amount.'));
//                }
//            }
//        });
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
