<?php

namespace App\Http\Controllers;

use App\category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\categoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    
    public function index()
    {
      $categories = category::with('childs')->where(['parent_id'=>'0'])->get();

      $cat_arr = json_decode(json_encode($categories),true);
      
      foreach($cat_arr as $ckey => $cval)
      {
        if(count($cval['childs']) > 0)
        {
            foreach($cval['childs'] as $child => $child_arr)
            {
                $categories[$ckey]['childs'][$child]['childs'] = category::with('childs')->where(['parent_id'=>$child_arr['id']])->get(); 
            }
        }

      }
      
      return response([ 'categories' => categoryResource::collection($categories), 'message' => 'Retrieved successfully'], 200);
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
            'title' => 'required|max:255',
            'parent_id' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $category = category::create($data);

        return response([ 'category' => new categoryResource($category), 'message' => 'Created successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CEO  $ceo
     * @return \Illuminate\Http\Response
     */
    public function show(categoryResource $category)
    {
        return response([ 'category' => new categoryResource($category), 'message' => 'Retrieved successfully'], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CEO  $ceo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        category::where(['id'=>$id])->update($request->all());

        return response([ 'message' => 'Updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\CEO $ceo
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    // public function destroy($id)
    // {
    //     category::where(['id'=>$id])->orWhere(['parent_id'=> $id])->delete();

    //     return response(['message' => 'Deleted successfully']);
    // }

    public function destroy($id)
    {        
        $ids_arr[] = $id;       

        $ids_arr = $this->getCategories($id,$ids_arr);
    
        category::destroy($ids_arr);

        return response(['message' => 'Deleted successfully']);
    }
    
    public function getCategories($id,$ids_arr)
    {         
        $categories = category::where(['parent_id'=>$id])->get();       
        $cat_arr = json_decode(json_encode($categories),true);
      
     
        if(count($cat_arr) > 0)
        {
            foreach($cat_arr as $ckey => $cval)
            {                  
                $ids_arr[] = $cval['id'];                   
                $ids_arr = $this->getCategories($cval['id'],$ids_arr);
            }        
        }
        return $ids_arr;
    }
}
