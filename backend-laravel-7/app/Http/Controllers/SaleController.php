<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\{
    Sale,
    ItemSale,
    Item
};
use App\Http\Requests\SaleRequest;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = new Sale();    
        
        $data = $data->with("customer");

        $data = $data->orderBy('id','desc')->get();
        
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaleRequest $request)
    {
        try{
            \DB::beginTransaction();
    
            $payload = $request->only("code_transaksi","qty","total_harga","total_diskon","total_bayar");

            $payload["tanggal_transaksi"] = now()->toDateTimeString();

            $payload["customer_id"] = $request->customer_id["id"];

            $data = Sale::create($payload);

            foreach($request->item as $data_item){            
                $item = Item::where("id",$data_item["id"])->first();

                $item->update([
                    "stock" => intval($item->stock) - intval($request->qty)
                ]);

                ItemSale::create([
                    "sale_id" => $data->id,
                    "item_id" => $item->id
                ]);
            }

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
    public function show(Sale $sale)
    {
        $sale->load(["customer","item_sales","item_sales.item"]);

        return response()->json($sale);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        try{
            \DB::beginTransaction();

            $sale->delete();

            \DB::commit();
            return response()->json($sale);
        }catch(\Exception $e){
            \DB::rollback();

            return response()->json([
                "message" => $e->getMessage()
            ],500);
        }
    }
}
