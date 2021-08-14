<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Http\Requests\ItemRequest;
use App\Uploads\UploadBarang;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = new Item();

        if($request->filled("search")){
            $data = $data->where("nama_item","like","%".$request->search."%");
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
    public function store(ItemRequest $request)
    {
        try{
            \DB::beginTransaction();
            
            $payload = $request->except("barang");

            $payload["barang"] = UploadBarang::upload();

            $data = Item::create($payload);

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
    public function show(Item $item)
    {
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request,Item $item)
    {
        try{
            \DB::beginTransaction();

            $payload = $request->except("barang");

            if($request->hasFile("barang")){
                $payload["barang"] = UploadBarang::upload();                
                UploadBarang::delete($item->barang_original);
            }

            $item->update($payload);

            \DB::commit();
            return response()->json($item);
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
    public function destroy(Item $item)
    {
        try{
            \DB::beginTransaction();

            UploadBarang::delete($item->barang_original);

            $item->delete();

            \DB::commit();
            return response()->json($item);
        }catch(\Exception $e){
            \DB::rollback();

            return response()->json([
                "message" => $e->getMessage()
            ],500);
        }
    }
}
