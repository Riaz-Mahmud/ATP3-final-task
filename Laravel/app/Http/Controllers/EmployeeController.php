<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function Login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required|max:50',
            'password' => [
                'required',
                'min:4', 
                'max:20', 
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }else{
            
            $user=DB::table('emp_info')
            ->where('username',$request->input('username'))
            ->where('status',1)
            ->first();
            
            if($user){

                if($user->password == $request->password){

                    return response()->json($user, 200);

                }else{

                    return response()->json(['code'=>401, 'message' => 'No data found']);

                }
            }else{
                return response()->json(['code'=>401, 'message' => 'User Not Found']);
            }
        }
    }

    public function AllEmployee(Request $request){

        $data=DB::table('emp_info')
            ->where('status',1)
            ->get();
            
        if($data){
            return response()->json($data, 200);
        }else{
            return response()->json(['code'=>401, 'message' => 'No Product Found!']);
        }
    }

    public function CreateEmployee(Request $request){
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'username' => 'required',
                'password' => 'required'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }else{

            $data=array();
            $data['full_name']=$request->name;
            $data['username']=$request->username;
            $data['password']=$request->password;
            $data['type']=2;

            $available=DB::table('emp_info')
                    ->where('username',$request->username)
                    ->first();

            if($available){
                return response()->json(['code'=>401, 'message' => 'Username already taken!']);
            }else{
  
                $user=DB::table('emp_info')->insert($data);
                if($user){
                    return response()->json(['code'=>200, 'message' => 'OK']);
                }else{
                    return response()->json(['code'=>401, 'message' => 'Something going wrong!']);
                }
            }
        }
    }

    public function DeleteEmployee(Request $request){
        $validator = Validator::make($request->all(),
            [
                'user_id'    => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }else{

            $data=array();
            $data['status']=0;

            $update= DB::table('emp_info')
                ->where('id',$request->user_id)
                ->update($data);

            if($update){

                return response()->json(['code'=>200, 'message' => 'OK']);
                
            }else{

                return response()->json(['code'=>401, 'message' => 'No data found']);
            }
        }
    }

    public function EditEmployee(Request $request){
        $validator = Validator::make($request->all(),
            [
                'user_id'    => 'required',
                'name'    => 'required'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }else{

            $data=array();
            $data['full_name']=$request->name;

            $update= DB::table('emp_info')
                ->where('id',$request->user_id)
                ->update($data);

            if($update){

                return response()->json(['code'=>200, 'message' => 'OK']);
                
            }else{

                return response()->json(['code'=>401, 'message' => 'No data found']);
            }
        }
    }
}
