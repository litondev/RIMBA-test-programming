<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerRequest extends FormRequest
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
            "nama" => "required",
            "contact" => "required",
            "alamat" => "required",
            "diskon" => "nullable|integer",
            "type_diskon" => "required|in:fix,percent,none",
            "ktp" => "required|image|mimes:jpg,png,jpeg|max:10024|dimensions:max_width=5000,max_height=5000"
        ];

        if($this->method() == "PUT"){
            if(!$this->hasFile("ktp")){
                unset($rules["ktp"]);
            }
        }

        if($this->type_diskon != "none"){
            $rules["diskon"] = "required|integer";            
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
