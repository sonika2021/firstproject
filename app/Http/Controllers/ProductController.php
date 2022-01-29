<?php

namespace App\Http\Controllers;

use App\category;
use App\category_product;
use App\product;
use Illuminate\Http\Request;
use App\Http\Resources\productResource;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = product::with('categories')->get();
      
        return response([ 'products' => productResource::collection($products), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {       
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $category_ids = $request->get('category_id');
        $name = $request->get('name');
        $price = $request->get('price');

        $category_arr = explode(",",$category_ids);        

        $product_arr = array(
            "name" => $name,
            "price" => $price,
        );

        $product = product::create($product_arr);

        foreach($category_arr as $key => $category_id)
        {
            $pro_cat_arr = array(
                "product_id" => $product->id,
                "category_id" => $category_id,
                "created_at" => date('Y-m-d h:i:s'),
                "updated_at" => date('Y-m-d h:i:s')
            );
            category_product::create($pro_cat_arr);
        }

        return response([ 'product' => new productResource($product), 'message' => 'Created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $category_ids = $request->get('category_id');
        $name = $request->get('name');
        $price = $request->get('price');

        $category_arr = explode(",",$category_ids);        

        $product_arr = array(
            "name" => $name,
            "price" => $price,
        );
       
        product::where(['id'=>$id])->update($product_arr);

        category_product::where('product_id',$id)->delete();

        foreach($category_arr as $key => $category_id)
        {
            $pro_cat_arr = array(
                "product_id" => $id,
                "category_id" => $category_id,
                "created_at" => date('Y-m-d h:i:s'),
                "updated_at" => date('Y-m-d h:i:s')
            );
            category_product::create($pro_cat_arr);
        }    

        return response([ 'message' => 'Updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        product::where(['id'=>$id])->delete();
        category_product::where(['product_id'=>$id])->delete();

        return response(['message' => 'Deleted successfully']);
    }
}
