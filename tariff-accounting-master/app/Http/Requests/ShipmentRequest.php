<?php

namespace App\Http\Requests;

use App\Assembly;
use App\Product;
use App\Reservation;
use App\Sale;
use App\Shipment;
use App\ShipmentProduct;
use App\WarehouseProduct;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\ApiResponse\ApiResponse;
use App\Http\Controllers\Traits\ResponseAble;

class ShipmentRequest extends FormRequest
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
            Shipment::ABLE_TYPE     => 'required',
            Shipment::ABLE_ID       => 'required',
            'items'                 => 'required|array',
            'items.*.product_id'    => 'required|distinct|exists:products,id',
            'items.*.quantity'      => 'required|min:0',
            'items.*.issued_from_booked'=> 'required|min:0',
        ];
    }

    public function withValidator($validator)
    {

        $validator->after(function ($validator) {
            $shipment = $this->shipment;

            $items = $this->get('items',null);

            if ($type = $this->get(Shipment::ABLE_TYPE))
            {
                if ($id = $this->get(Shipment::ABLE_ID)){
                    if ($type == Sale::TABLE_NAME){
                        if (!$sale = Sale::find($id)){
                            $validator->errors()->add(Shipment::ABLE_TYPE, __('messages.not_found',['name' => __('messages.sale')]));
                        }
                    }elseif ($type == Assembly::TABLE_NAME){
                        if (!$assembly = Assembly::find($id)){
                            $validator->errors()->add(Shipment::ABLE_TYPE, __('messages.not_found',['name' => __('messages.assembly')]));
                        }
                    }
                }
            }

            if (is_array($items)){
                if (!$shipment){
                    /**
                     * Items validation for create
                     */
                    foreach ($items as $key => $item){
                        if ($item['quantity'] == 0 && $item['issued_from_booked'] == 0){
                            $validator->errors()->add('items.' . $key, __('messages.At least one must be greater than zero'));
                        }

                        if ($product = Product::find($item['product_id'])){
                            /**
                             * Check warehouse has or no enough product.
                             */
                            $rm_m = WarehouseProduct::where('product_id',$product->id)->whereRaw('remainder - booked > 0')->sum(DB::raw('remainder - booked'));
                            if ($item['quantity'] > $rm_m){
                                $validator->errors()->add('items.' . $key . '.quantity', __('messages.The is not enough in the warehouse',['name' => $product->name]));
                            }

                            /**
                             * Check product booked in warehouse products
                             */
                            $booked = Reservation::where(Reservation::ABLE_TYPE,$this->get(Shipment::ABLE_TYPE))
                                ->where(Reservation::ABLE_ID,$this->get(Shipment::ABLE_ID))
                                ->whereHasMorph('sourceable',WarehouseProduct::class,function ($query) use ($product){
                                    return $query->where('product_id',$product->id);
                                })
                                ->whereRaw('quantity - issued > 0')->sum(DB::raw('quantity - issued'));

                            if ($item['issued_from_booked'] > $booked){
                                $validator->errors()->add('items.' . $key . '.issued_from_booked', __('messages.Booked product of this document quantity not enough',['name' => $product->name]));
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

                        if ($product = Product::find($item['product_id'])){
                            /**
                             * Check warehouse has or no enough product.
                             */
                            $rm_m = Warehouseproduct::where('product_id',$product->id)->whereRaw('remainder - booked > 0')->sum(DB::raw('remainder - booked'));
                            $rm_m += ShipmentProduct::where('shipment_id',$shipment->id)->where('product_id',$product->id)->sum('quantity');

                            if ($item['quantity'] > $rm_m){
                                $validator->errors()->add('items.' . $key . '.quantity', __('messages.The is not enough in the warehouse',['name' => $product->name]));
                            }

                            /**
                             * Check product booked in warehouse products
                             */
                            $booked = Reservation::where(Reservation::ABLE_TYPE,$this->get(Shipment::ABLE_TYPE))
                                ->where(Reservation::ABLE_ID,$this->get(Shipment::ABLE_ID))
                                ->whereHasMorph('sourceable',WarehouseProduct::class,function ($query) use ($product){
                                    return $query->where('product_id',$product->id);
                                })
                                ->whereRaw('quantity - issued > 0')->sum(DB::raw('quantity - issued'));

                            $booked += ShipmentProduct::where('shipment_id',$shipment->id)->where('product_id',$product->id)->sum('issued_from_booked');

                            if ($item['issued_from_booked'] > $booked){
                                $validator->errors()->add('items.' . $key . '.issued_from_booked', __('messages.Booked product of this document quantity not enough',['name' => $product->name]));
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
