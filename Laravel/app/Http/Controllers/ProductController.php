<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function AllProduct(Request $request){

        $data=DB::table('products')
            ->where('status',1)
            ->get();
            
        if($data){
            return response()->json($data, 200);
        }else{
            return response()->json(['code'=>401, 'message' => 'No Product Found!']);
        }
    }

    public function CreateProduct(Request $request){
        $validator = Validator::make($request->all(),
            [
                'product_name' => 'required',
                'price' => 'required',
                'description' => 'required'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }else{

            $data=array();
            $data['product_name']=$request->product_name;
            $data['price']=$request->price;
            $data['description']=$request->description;

            $user=DB::table('products')->insert($data);

            if($user){
                return response()->json(['code'=>200, 'message' => 'OK']);
            }else{
                return response()->json(['code'=>401, 'message' => 'Something going wrong!']);
            }
        }
    }

    public function EditProduct(Request $request){
        $validator = Validator::make($request->all(),
            [
                'id' => 'required',
                'product_name' => 'required',
                'price' => 'required',
                'description' => 'required'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }else{

            $data=array();
            $data['product_name']=$request->product_name;
            $data['price']=$request->price;
            $data['description']=$request->description;

            $update= DB::table('products')
                ->where('id',$request->id)
                ->update($data);

            if($update){

                return response()->json(['code'=>200, 'message' => 'OK']);
                
            }else{

                return response()->json(['code'=>401, 'message' => 'Something going wrong!']);
            }
        }
    }

    public function DeleteProduct(Request $request){
        $validator = Validator::make($request->all(),
            [
                'id' => 'required'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }else{

            $data=array();
            $data['status']=0;

            $update= DB::table('products')
                ->where('id',$request->id)
                ->update($data);

            if($update){
                return response()->json(['code'=>200, 'message' => 'OK']);
            }else{
                return response()->json(['code'=>401, 'message' => 'Something going wrong!']);
            }
        }
    }
}
