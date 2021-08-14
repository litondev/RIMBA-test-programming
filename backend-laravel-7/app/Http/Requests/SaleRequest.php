<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaleRequest extends FormRequest
{
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
    public function rules(){
        $sales = [
            "code_transaksi" => "required",        
            "customer_id" => "required|array|min:1",
            "customer_id.*" => "required",
            "qty" => "required|integer",
            "total_diskon" => "required|integer",
            "total_harga" => "required|integer",
            "total_bayar" => "required|integer",
            "item" => "required|array|min:1",
            "item.*" => "required"
        ];

        return $sales;
    }

    /** 
     * Custom Error
     *
     * @return json     
    */
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(
            response()->json([                
                "message" => $validator->errors()->first()
            ],422)
        );
    }
}
