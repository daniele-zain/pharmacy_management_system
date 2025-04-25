<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show_all_meds()
    {
        $meds = DB::table('medicines')
        ->join('categories', 'medicines.category_id', '=', 'categories.id')
        ->SELECT('medicines.*', 'categories.class')
        ->get();

        if(count($meds)==0){
            return response('No Current Items To Show',200);
        }
        else{
            return response($meds,200);
        }
    }

    public function show_categorysMeds( $category_id)
    {
        $meds = DB::table('medicines')
        ->join('categories', 'medicines.category_id', '=', 'categories.id')
        ->SELECT('medicines.*', 'categories.class')
        ->WHERE('medicines.category_id' ,$category_id  )
        ->get();

        if(count($meds)==0){
            return response('No Current Items To Show',200);
        }
        else{
            return response($meds,200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_med(Request $request)
    {
        if (Auth::user()->type == 1){
            return response('You do not have permission',400);
        }
        $input=$request->all();
        $validator= Validator::make($input,[
            'warehouse_manager_id'=>'nullable',
            'generic_name'=>'required',
            'scientific_name'=>'required',
            'description'=>'nullable',
            'price'=>'required',
            'quantity'=>'required',
            'company'=>'required',
            'category_id'=>'required',
            'expiration_date'=>'required',
            ]);
        if($validator->fails()){
            return response('Something Went Wrong ! Please Check medicine Info',400);
        }
        $id=Auth::user()->id;
        $med=Medicine::create([
           'generic_name'=>$input['generic_name'],
           'scientific_name'=>$input['scientific_name'],
           'description'=>$input['description'],
           'price'=>$input['price'],
           'quantity'=>$input['quantity'],
           'company'=>$input['company'],
           'category_id'=>$input['category_id'],
           'expiration_date'=>$input['expiration_date'],
        ]);

        $med->warehouse_manager_id=$id;
        $med->save();
        return response($med,200);
    }

    /**
     * Display the specified resource.
     */
    public function show_med($id)
    {
        $med=DB::table('medicines')
        ->join('categories', 'medicines.category_id', '=', 'categories.id')
        ->SELECT('medicines.*', 'categories.class')
        ->where('medicines.id',$id)
        ->get();

        if(is_null($med)){
            return response('medicine Not Found');
        }

        return response($med,200);
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_med( $id)
    {
        if (Auth::user()->type == 1){
            return response('You do not have permission',400);
        }

        $med=Medicine::find($id);
        if(is_null($med)){
            return response('medicine Not Found',403);
        }
        if(!($med->warehouse_manager_id == Auth::user()->id)){
            return response('You can not delete this medicine',400);
        }
        $med->delete();
        return response('medicine Deleted',200);
    }

    public function search_med($name, $class)
    {
        $category_id = category::where('class' , 'like', '%'.$name.'%')->id;
        return Medicine::where('generic_name', 'like', '%'.$name.'%')
        ->orWhere('category_id', $category_id)
        ->get();
    }
}
