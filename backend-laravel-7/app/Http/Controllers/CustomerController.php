<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Http\Requests\CustomerRequest;
use App\Uploads\UploadKtp;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = new Customer();
        
        if($request->filled("search")){
            $data = $data->where("nama","like","%".$request->search."%");
        }

        $data = $data->orderBy('id','desc')->get();

        return response()->json($data);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        try{
            \DB::beginTransaction();

            $payload = $request->except("ktp");

            $payload["ktp"] = UploadKtp::upload();

            $data = Customer::create($payload);

            \DB::commit();
            return response()->json($data);
        }catch(\Exception $e){
            \DB::rollback();

            return response()->json([
                "message" => $e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        try{
            \DB::beginTransaction();

            $payload = $request->except("ktp");

            if($request->hasFile("ktp")){
                $payload["ktp"] = UploadKtp::upload();                
                UploadKtp::delete($customer->ktp_original);
            }

            $customer->update($payload);

            \DB::commit();
            return response()->json($customer);
        }catch(\Exception $e){
            \DB::rollback();

            return response()->json([
                "message" => $e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        try{
            \DB::beginTransaction();

            UploadKtp::delete($customer->ktp_original);

            $customer->delete();

            \DB::commit();
            return response()->json($customer);
        }catch(\Exception $e){
            \DB::rollback();

            return response()->json([
                "message" => $e->getMessage()
            ],500);
        }
    }
}
