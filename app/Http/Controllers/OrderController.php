<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comments;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $range= $request->get('range');

        if (Auth::user()->isAdmin()) {
            if (empty($search) && empty($range)) {
                $orders = Order::orderBy('id', 'DESC')->simplePaginate(20);
            }else{
                if(empty($range)){
                    $startDate = "2020-01-01 00:00:00";
                    $endDate = date("Y-m-d H:i:s");
                }else{
                    $startDate = Str::substr($range, 0, 10) . " 00:00:00";
                    $endDate = Str::substr($range, 13, 23) . " 00:00:00";
                }
                $orders= Order::where([['order_id', 'LIKE', "%{$search}%"], ['created_at', '>', $startDate], ['created_at', '<', $endDate]])
                ->orwhere([['name', 'LIKE', "%{$search}%"], ['created_at', '>', $startDate], ['created_at', '<', $endDate]])
                ->orwhere([['email', 'LIKE', "%{$search}%"], ['created_at', '>', $startDate], ['created_at', '<', $endDate]])
                    ->orwhere([['survey_title', 'LIKE', "%{$search}%"], ['created_at', '>', $startDate], ['created_at', '<', $endDate]])
                ->orderBy('id', 'DESC')->simplePaginate(20);
                $orders->appends($request->all());    
            }
        } else {
            if (empty($search) && empty($range)) {
                $orders = Order::where('user_id', Auth::user()->id)->orderBy('id', 'DESC')->simplePaginate(20);
            }else{
                if (empty($range)) {
                    $startDate = "2020-01-01 00:00  ";
                    $endDate = date("Y-m-d H:i:s");
                } else {
                    $startDate = Str::substr($range, 0, 10)." 00:00:00";
                    $endDate = Str::substr($range, 13, 23) . " 00:00:00";
                }
                $orders = Order::where([['order_id', 'LIKE', "%{$search}%"] , ['user_id', Auth::user()->id] ,[ 'created_at', '>' , $startDate], ['created_at', '<', $endDate]])
                    ->orwhere([['name', 'LIKE', "%{$search}%"], ['user_id', Auth::user()->id], ['created_at', '>', $startDate], ['created_at', '<', $endDate]])
                    ->orwhere([['email', 'LIKE', "%{$search}%"], ['user_id', Auth::user()->id], ['created_at', '>', $startDate], ['created_at', '<', $endDate]])
                    ->orwhere([['survey_title', 'LIKE', "%{$search}%"], ['user_id', Auth::user()->id], ['created_at', '>', $startDate], ['created_at', '<', $endDate]]) 
                    ->orderBy('id', 'DESC')->simplePaginate(20);
                $orders->appends($request->all());
            }
        }
        return view('admin/orders/index', ['orders' => $orders]);
    }

    public function get(Request $request)
    {
        if (Auth::user()->isAdmin()) {
            $order = Order::where('id', $request->get('order_id'))->first();
            $products = Product::where('order_id', $request->get('order_id'))->get();
        } else {
            $order = Order::where('id', $request->get('order_id'))
                ->where('user_id', Auth::user()->id)->first();
            $order->seen = 1;
            $order->save();    
            $products = Product::where('order_id', $request->get('order_id'))->get();
        }
        if(empty($order)){
            $status = 400;
        }else{
            $status = 200;
        }

        $items = "";

        foreach($products as $product){
            $items .= "<p>" . $product->product_name . "　X　" . $product->units . "点　＝　" . number_format($product->price * $product->units) . "円</p>";
        }

        return response()->json([
            'status' => $status,
            'order' => $order,
            'items' => $items,
            'total' => number_format($order->amount).' 円'
        ]);
    }

    public function update(Request $request)
    {
        if (Auth::user()->isAdmin()) {
            $order = Order::where('id', $request->get('order_id'))->first();
            $order->payment_status =  $request->get('payment_status');
        } else {
            $order = Order::where('id', $request->get('order_id'))
            ->where('user_id', Auth::user()->id)->first();
        }
        if (empty($order)) {
            $status = 400;
        } else {
            $status = 200;
            $order->accept_status =  $request->get('accept_status');
            $order->comment =  $request->get('comment');
            $order->save();
        }

        return response()->json([
            'status' => $status,
            'order' => $order
        ]);
    }

    public function getComment(Request $request)
    {

        $comments = Comments::where('order_id', $request->get('order_id'))->get();

        if(count($comments)>0){
            $status = 200;
            $list = "<table>";

            foreach ($comments as $comment) {
                $list .= "<tr><th>" . date("Y-m-d H:i", strtotime($comment->created_at)) . "</th><td>" . $comment->content . "</td></tr>";
            }

            $list .= "</table>";

        }else{

            $status = 400;
            $list = "";
            
        }

        return response()->json([
            'status' => $status,
            'list' => $list
        ]);
    }

    public function updateComment(Request $request)
    {
        if(!empty($request->get('content'))){
            $comment = new Comments();
            $comment->order_id = $request->get('order_id');
            $comment->user_id = Auth::user()->id;
            $comment->content = $request->get('content');
            $response = $comment->save();

            $order = Order::where('id', $request->get('order_id'))->first();
            $order->comment= $comment->content;
            $order->save();

            if ($response) {
                $status = 200;
            } else {
                $status = 400;
            }
        }else{
            $status = 400;
        }
        return response()->json([
            'status' => $status
        ]);

    }
}


