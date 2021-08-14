<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ItemRequest extends FormRequest
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
    public function rules()
    {
        $rules = [
            "nama_item" => "required",
            "unit" => "required|in:kg,pcs",
            "stock" => "required|integer",
            "harga_satuan" => "required|integer",
            "barang" => "required|image|mimes:jpg,png,jpeg|max:10024|dimensions:max_width=5000,max_height=5000"
        ];

        if($this->method() == "PUT"){
            if(!$this->hasFile("barang")){
                unset($rules["barang"]);
            }
        }

        return $rules;
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
