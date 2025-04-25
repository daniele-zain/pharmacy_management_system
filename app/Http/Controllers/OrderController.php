<?php

namespace App\Http\Controllers;

use App;
use App\Models\category;
use App\Models\Medicine;
use App\Models\Order;
use App\Models\Status;
use App\Notifications\OrderReceived;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;


class OrderController extends Controller
{
    public function create_order(request $request)
    {
        if (Auth::user()->type == 0){
            return response('You do not have permission',400);
        }
        $input=$request->all();
        $validator= Validator::make($input,[
            'id'=>'required',
            'quantity'=>'required'
        ]);
        if($validator->fails()){
            return response('Something Went Wrong ! Please Check medicine Info',400);
        }

        $med = Medicine::find($input['id']); // Assuming Medicine is the model for your table


        if(is_null($med)){
            return response('something went wrong ! , please try again');
        }

        if($med->quantity < $input['quantity']){
            return response('sorry,quantity is greater than that we have');
        }

        $order=Order::create( [
            'quantity'=>$input['quantity'],
            'price'=> $input['quantity']*$med->price,
            'payment'=>0
            ]

        );

        $order->pharmacist_id=Auth::user()->id;
        $order->medicine_id=$input['id'];
        $order->status_id=1;

        $order->save();


        return response($order,200);
    }



    public function show_all_orders()
    {
        $pharmacist_id=Auth::user()->id;
        $orders = DB::table('orders')
        ->join('statuses', 'orders.status_id', '=', 'statuses.id')
        ->join('medicines', 'orders.medicine_id', '=', 'medicines.id')
        ->SELECT('orders.*','statuses.name as status' ,'medicines.generic_name')
        ->where('orders.pharmacist_id','=',$pharmacist_id)
        ->get();

        if(count($orders)==0){
            return response('No Current orders To Show',200);
        }
        else{
            return response($orders,200);
        }
    }

    public function show_all_orders_with_update(Request $request)
    {
        $warehouse_manager_id=Auth::user()->id;

        $type = DB::table('users')
            ->where('id', $warehouse_manager_id)
            ->value('type');

        if($type==1){
            return response('you do not have permission to update',200);
        }


        $order = Order::find($request->order_id);
        $med = Medicine::find($order->medicine_id);

        $input=$request->all();
        $validator= Validator::make($input,[
            'order_id'=>'required',
            'new_status_id'=>'nullable|in:1,2,3',
            'new_payment'=>'nullable|in:0,1'
        ]);

        if($validator->fails()){
            return response('something went wrong ! , pleas try again',0);
        }

        $updated_order = app(Order::class)->find($input['order_id']);
        if($input['new_status_id']!=null){
            $updated_order->status_id=$input['new_status_id'];

            if($input['new_status_id'] == 2){

                $med->quantity = $med->quantity - $order->quantity;

                $med->save();
            }

        }
        if($input['new_payment']!=null ){
            $updated_order->payment=$input['new_payment'];
        }

        $updated_order->save();

        $orders = DB::table('orders')
        ->join('statuses', 'orders.status_id', '=', 'statuses.id')
        ->join('medicines', 'orders.medicine_id', '=', 'medicines.id')
        ->SELECT('orders.*','statuses.name as status' ,'medicines.generic_name')
        ->where('medicines.warehouse_manager_id','=',$warehouse_manager_id)
        ->get();

        if(count($orders)==0){
            return response('No Current orders To Show',200);
        }
        else{
            return response($orders,200);
        }
    }
    /*
    public function sendOrderNotification($orderId)
    {
    $order = Order::find($orderId);

    // Create an instance of the notification and pass the order ID
    $notification = new OrderReceived($order->id);

    // Send the notification to the user
    $user = $order->user; // Assuming the order has a relationship to the user
    Notification::send($user, $notification);
    return "Order notification sent successfully";
    }*/

    public function weekly_report()
    {
        $pharmacist_id=Auth::user()->id;

        // Get the start of the current week
        $startOfWeek = Carbon::now()->startOfWeek();

        $orders = DB::table('orders')
        ->join('statuses', 'orders.status_id', '=', 'statuses.id')
        ->join('medicines', 'orders.medicine_id', '=', 'medicines.id')
        ->SELECT('orders.*','statuses.name as status' ,'medicines.generic_name')
        ->where('orders.pharmacist_id','=',$pharmacist_id)
        ->where('orders.created_at', '>=', $startOfWeek)
        ->get();

        if(count($orders)==0){
            return response('No Current orders To Show',200);
        }
        else{
            return response($orders,200);
        }
    }

    public function monthly_report()
    {
        $pharmacist_id=Auth::user()->id;

        // Get the start of the current month
        $startOfMonth = Carbon::now()->startOfMonth();

        $orders = DB::table('orders')
        ->join('statuses', 'orders.status_id', '=', 'statuses.id')
        ->join('medicines', 'orders.medicine_id', '=', 'medicines.id')
        ->SELECT('orders.*','statuses.name as status' ,'medicines.generic_name')
        ->where('orders.pharmacist_id','=',$pharmacist_id)
        ->where('orders.created_at', '>=', $startOfMonth)
        ->get();

        if(count($orders)==0){
            return response('No Current orders To Show',200);
        }
        else{
            return response($orders,200);
        }
    }

}
