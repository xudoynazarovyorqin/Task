<?php

namespace App\Http\Requests;

use App\Assembly;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;
use App\Material;
use App\Realization;
use App\RealizationMaterial;
use App\Reservation;
use App\Sale;
use App\WarehouseMaterial;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RealizationRequest extends FormRequest
{
    use ResponseAble;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id'               => 'required|exists:users,id',
            'datetime'              => 'required',
            Realization::ABLE_TYPE  => 'required',
            Realization::ABLE_ID    => 'required',
            'items'                 => 'required|array',
            'items.*.material_id'   => 'required|distinct|exists:materials,id',
            'items.*.quantity'      => 'required|min:0',
            'items.*.issued_from_booked'=> 'required|min:0',
        ];
    }

    public function withValidator($validator)
    {

        $validator->after(function ($validator) {
            $realization = $this->realization;

            $items = $this->get('items',null);

            if ($type = $this->get(Realization::ABLE_TYPE))
            {
                if ($id = $this->get(Realization::ABLE_ID)){
                    if ($type == Sale::TABLE_NAME){
                        if (!$sale = Sale::find($id)){
                            $validator->errors()->add(Realization::ABLE_TYPE, __('messages.not_found',['name' => __('messages.sale')]));
                        }
                    }elseif ($type == Assembly::TABLE_NAME){
                        if (!$assembly = Assembly::find($id)){
                            $validator->errors()->add(Realization::ABLE_TYPE, __('messages.not_found',['name' => __('messages.assembly')]));
                        }
                    }
                }
            }

            if (is_array($items)){
                if (!$realization){
                    /**
                     * Items validation for create
                     */
                    foreach ($items as $key => $item){
                        if ($item['quantity'] == 0 && $item['issued_from_booked'] == 0){
                            $validator->errors()->add('items.' . $key, __('messages.At least one must be greater than zero'));
                        }

                        if ($material = Material::find($item['material_id'])){
                            /**
                             * Check warehouse has or no enough material.
                             */
                            $rm_m = WarehouseMaterial::where('material_id',$material->id)->whereRaw('remainder - booked > 0')->sum(DB::raw('remainder - booked'));
                            if ($item['quantity'] > $rm_m){
                                $validator->errors()->add('items.' . $key . '.quantity', __('messages.The is not enough in the warehouse',['name' => $material->name]));
                            }

                            /**
                             * Check material booked in warehouse materials
                             */
                            $booked = Reservation::where(Reservation::ABLE_TYPE,$this->get(Realization::ABLE_TYPE))
                                ->where(Reservation::ABLE_ID,$this->get(Realization::ABLE_ID))
                                ->whereHasMorph('sourceable',WarehouseMaterial::class,function ($query) use ($material){
                                    return $query->where('material_id',$material->id);
                                })
                                ->whereRaw('quantity - issued > 0')->sum(DB::raw('quantity - issued'));

                            if ($item['issued_from_booked'] > $booked){
                                $validator->errors()->add('items.' . $key . '.issued_from_booked', __('messages.Booked material of this document quantity not enough',['name' => $material->name]));
                            }
                        }
                    }
                }
                else{
                    /**
                     * Items validation for update
                     */
                    foreach ($items as $key => $item){
                        if ($item['quantity'] == 0 && $item['issued_from_booked'] == 0){
                            $validator->errors()->add('items.' . $key, __('messages.At least one must be greater than zero'));
                        }

                        if ($material = Material::find($item['material_id'])){
                            /**
                             * Check warehouse has or no enough material.
                             */
                            $rm_m = WarehouseMaterial::where('material_id',$material->id)->whereRaw('remainder - booked > 0')->sum(DB::raw('remainder - booked'));
                            $rm_m += RealizationMaterial::where('realization_id',$realization->id)->where('material_id',$material->id)->sum('quantity');

                            if ($item['quantity'] > $rm_m){
                                $validator->errors()->add('items.' . $key . '.quantity', __('messages.The is not enough in the warehouse',['name' => $material->name]));
                            }

                            /**
                             * Check material booked in warehouse materials
                             */
                            $booked = Reservation::where(Reservation::ABLE_TYPE,$this->get(Realization::ABLE_TYPE))
                                ->where(Reservation::ABLE_ID,$this->get(Realization::ABLE_ID))
                                ->whereHasMorph('sourceable',WarehouseMaterial::class,function ($query) use ($material){
                                    return $query->where('material_id',$material->id);
                                })
                                ->whereRaw('quantity - issued > 0')->sum(DB::raw('quantity - issued'));

                            $booked += RealizationMaterial::where('realization_id',$realization->id)->where('material_id',$material->id)->sum('issued_from_booked');

                            if ($item['issued_from_booked'] > $booked){
                                $validator->errors()->add('items.' . $key . '.issued_from_booked', __('messages.Booked material of this document quantity not enough',['name' => $material->name]));
                            }
                        }
                    }
                }
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
