<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use App\OrderModel;
use App\OrderDetailModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
  private $orderModel;
  private $orderDetailModel;

  public function __construct()
  {
    $this->orderModel = new OrderModel();
    $this->orderDetailModel = new OrderDetailModel();
  }

	public function addOrder(Request $request){
    //Validate
    $validation = Validator::make($request->input('customer'),[
      'email' => 'required|email',
      'phone' => ['required', 'regex:/(0[3|5|7|8|9])([0-9]{8})$/'],
      'birthday' => 'required',
      'gender' => 'required|in:male,female'
    ]);

    if($validation->fails()) {
      return response()->json(['message' => $validation->messages()->first()], 422);
    }

    $validation = Validator::make($request->input('shippingAddress'),[
      'name' => 'required',
      'phone' => ['required', 'regex:/(0[3|5|7|8|9])([0-9]{8})$/'],
      'address' => 'required'
    ]);

    if($validation->fails()) {
      return response()->json(['message' => $validation->messages()->first()], 422);
    }

    $data = $request->all();
		DB::beginTransaction();
		try{
      //add customer
      $customer = new \App\Customer();
      $customer->name = $data['customer']['name'];
      $customer->birthday = date('Y-m-d', strtotime($data['customer']['date_of_birth']));
      $customer->email = $data['customer']['email'];
      $customer->address = $data['customer']['address'];
      $customer->facebook = $data['customer']['facebook'];
      $customer->gender = $data['customer']['gender'];
      $customer->phone = $data['customer']['phone'];
      $customer->created_at = date('Y-m-d H:i:s');
      $customer->updated_at = date('Y-m-d H:i:s');
      $customer->save();

      //add shippingAddress
      $shippingAddress = new \App\ShippingAddress();
      $shippingAddress->name = $data['shippingAddress']['name'];
      $shippingAddress->address = $data['shippingAddress']['address'];
      $shippingAddress->note = $data['shippingAddress']['note'];
      $shippingAddress->phone = $data['shippingAddress']['phone'];
      $shippingAddress->created_at = date('Y-m-d H:i:s');
      $shippingAddress->updated_at = date('Y-m-d H:i:s');
      $shippingAddress->save();

      //add order
      $order = new \App\OrderModel();
      $order->user_id = $data['user'];
      $order->customer_id = $customer->id;
      $order->shipping_id = $shippingAddress->id;
      $order->status = $data['order']['status'];
      $order->shipping_status = $data['order']['shipping_status'];
      $order->created_at = date('Y-m-d H:i:s');
      $order->updated_at = date('Y-m-d H:i:s');
      $order->save();

      //add order detail
      $query = [];
      $total = 0;
      foreach ($data['cart'] as $index => $eachItem) {
        $images = [];
        foreach ($eachItem['images'] as $key => $eachImage) {
          $name = 'order_'.$order->id.'_'.$index.'_'.$key.'.jpg';
          \Image::make($eachImage)->save(public_path('images/').$name);
          $images[] = 'http://my.in-ao.com:9090/images/'.$name;
        }

        $query[] = [
          'order_id' => $order->id,
          'type' => $eachItem['type'],
          'size' => $eachItem['size'],
          'number' => $eachItem['number'],
          'image' => json_encode($images),
          'price' => $eachItem['total'],
          'note' => $eachItem['note'],
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s'),
        ];
        $total += $eachItem['total'];
      }
      $order->total_money	 = $total;
      $order->save();
      DB::table('order_detail')->insert($query);
			DB::commit();
			return response()->json(['status' => true, 'message' => 'success']);
		}catch(\Exception $exception){
      Log::error('['.date('Y-m-d H:i:s').'] --- add order get error: '.$exception->getMessage());
			DB::rollBack();
			return response()->json(['status' => false, 'message' => 'Không thể tạo đơn hàng vào lúc này, '.$exception->getMessage()]);
		}

		return response()->json([], 200);
	}

	public function updateOrder($id, Request $request){
    //Validate
    $validation = Validator::make($request->input('customer'),[
      'email' => 'required|email',
      'phone' => ['required', 'regex:/(0[3|5|7|8|9])([0-9]{8})$/'],
      'birthday' => 'required',
      'gender' => 'required|in:male,female'
    ]);

    if($validation->fails()) {
      return response()->json(['message' => $validation->messages()->first()], 422);
    }

    $validation = Validator::make($request->input('shippingAddress'),[
      'name' => 'required',
      'phone' => ['required', 'regex:/(0[3|5|7|8|9])([0-9]{8})$/'],
      'address' => 'required'
    ]);

    if($validation->fails()) {
      return response()->json(['message' => $validation->messages()->first()], 422);
    }

    $data = $request->all();
		DB::beginTransaction();
		try{
      //add customer
      $customer = new \App\Customer();
      $customer = $customer->find($data['customer']['id']);
      $customer->name = $data['customer']['name'];
      $customer->birthday = date('Y-m-d', strtotime($data['customer']['birthday']));
      $customer->email = $data['customer']['email'];
      $customer->address = $data['customer']['address'];
      $customer->facebook = $data['customer']['facebook'];
      $customer->gender = $data['customer']['gender'];
      $customer->phone = $data['customer']['phone'];
      $customer->updated_at = date('Y-m-d H:i:s');
      $customer->save();

      //add shippingAddress
      $shippingAddress = new \App\ShippingAddress();
      $shippingAddress = $shippingAddress->find($data['shippingAddress']['id']);
      $shippingAddress->name = $data['shippingAddress']['name'];
      $shippingAddress->address = $data['shippingAddress']['address'];
      $shippingAddress->note = $data['shippingAddress']['note'];
      $shippingAddress->phone = $data['shippingAddress']['phone'];
      $shippingAddress->updated_at = date('Y-m-d H:i:s');
      $shippingAddress->save();

      //add order
      $order = new \App\OrderModel();
      $order = $order->find($id);
      $order->customer_id = $customer->id;
      $order->shipping_id = $shippingAddress->id;
      $order->status = $data['order']['status'];
      $order->shipping_status = $data['order']['shipping_status'];
      $order->index = $data['order']['index'];
      $order->updated_at = date('Y-m-d H:i:s');
      $order->save();

      //add order detail
      $query = [];
      $total = 0;
      foreach ($data['cart'] as $index => $eachItem) {
        foreach ($eachItem['images'] as $key => $eachImage) {
          $name = 'order_'.$order->id.'_'.$index.'_'.$key.'.jpg';
          \Image::make($eachImage)->save(public_path('images/').$name);
          $images[] = 'http://my.in-ao.com:9090/images/'.$name;
        }
        $total += $eachItem['total'];
        $query = DB::table('order_detail');
        if ($query->where('id', $eachItem['id'])->exists()) {
          continue;
        }
        $eachItem['order_id'] = $id;
        $eachItem['image'] = json_encode($images);
        unset($eachItem['images']);
        unset($eachItem['id']);
        unset($eachItem['total']);
        $query->insert($eachItem);
      }
      $order->total_money	 = $total;
      $order->save();
			DB::commit();
			return response()->json(['status' => true, 'message' => 'success']);
		}catch(\Exception $exception){
      Log::error('['.date('Y-m-d H:i:s').'] --- add order get error: '.$exception->getMessage());
			DB::rollBack();
			return response()->json(['status' => false, 'message' => 'Không thể cập nhật đơn hàng vào lúc này, '.$exception->getMessage()]);
		}

		return response()->json([], 200);
	}

  public function getOrder(Request $request)
  {
    $params = $request->all();
    //default
    $params['offset']       = $request->input('offset', 0);
    $params['limit']        = $request->input('limit', 5);
    $params['order']        = $request->input('order', 'asc');
    $params['sort']         = $request->input('sortBy', 'id');

    $result = $this->orderModel->getOrder($params);

    return response()->json([
      'total' => $result['total'],
      'data'  => $result['data'],
      'pagination' => $result['pagination'],
    ]);
  }

  public function getOrderDetail($id, Request $request)
  {
    return response()->json(['status' => true, 'data' => $this->orderModel->getOrderById($id)]);
  }
}
