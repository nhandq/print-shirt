<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderModel extends Model
{
    public $primaryKey = 'id';

    protected $guarded = array();

    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->table = $this->tableName();
    }

    protected function tableName()
    {
        return 'orders';
    }

    public function getOrder($params) {
			$data = [];
			$select = OrderModel::join('customers', 'orders.customer_id', 'customers.id')->select('orders.id', 'orders.user_id', 'orders.customer_id', 'orders.shipping_id', 'orders.status', 'orders.shipping_status', 'orders.index', 'orders.total_money', 'customers.name', 'customers.email', 'customers.phone', 'customers.address', 'customers.birthday', 'customers.gender', 'customers.facebook')
			->when(isset($params['name']), function ($query) use ($params) {
					return $query->where('customers.name', 'LIKE', '%'.$params['name'].'$');
			})
			->when(isset($params['status']), function ($query) use ($params) {
					return $query->where('orders.status', $params['status']);
			});
			$data['total'] = $select->count();
			$data['total'] = intval($data['total']/$params['limit'] + 0.9);
			$data['data']  = $select->skip(intval($params['offset']))
					->take($params['limit']);
            $data['data']  = ($params['sort'] != 'status') ? $data['data']->orderByRaw('customers.'.$params['sort'] . ' ' . $params['order'])->get()->toArray() : $data['data']->orderByRaw('orders.'.$params['sort'] . ' ' . $params['order'])->get()->toArray();

			$data['pagination'] = [
					'total' => $data['total'],
					'per_page' => $params['limit'],
					'current_page' => $params['offset'] + 1,
			];

			return $data;
    }

    public function getOrderById($id) {
        $select = OrderModel::find($id);
        if (is_null($select)) {
            return [];
        }
        $select = $select->toArray();
        $orderDetail = DB::table('order_detail')->select("*")
        ->where('order_id', $select['id'])
        ->get()->toArray();

        $orderDetail = json_decode(json_encode($orderDetail), true);
        $orderDetail = array_map(function ($eachRow) {
            $eachRow['images'] = json_decode($eachRow['image']);
            $eachRow['total'] = $eachRow['price']*$eachRow['number'];
            return $eachRow;
        }, $orderDetail);

        $customer = DB::table('customers')->where('id', $select['customer_id'])->first();
        $shipping = DB::table('shipping_addresses')->where('id', $select['shipping_id'])->first();
        // $customer = DB::table('users')->where('id', $select['user_id'])->get()->toArray();

        return ['order' => $select, 'orderDetail' => $orderDetail, 'customer' => $customer, 'shipping' => $shipping];
    }

    public function updateOrder($params) {
        OrderModel::where('id', $params['id'])
        ->update(['purpose' => $params['purpose'],
                'number_of_visa' => $params['number_of_visa'],
                'visa_type' => $params['visa_type'],
                'date_of_arrival' => date('Y-m-d', strtotime($params['date_of_arrival'])),
                'date_of_departure' => date('Y-m-d', strtotime($params['date_of_departure'])),
                'airport_fast_track' => $params['airport_fast_track'],
                'car_pick_up' => $params['car_pick_up'],
                'updated_at' => date('Y-m-d H:i:s')
        ]);

        return ['status' => true, 'message' => 'Cập nhập thành công'];
    }

    public function updateOrderDetail($params) {
        DB::table('order_detail')->where('id', $params['id'])
        ->update([
                'passport_full_name' => $params['passport_full_name'],
                'passport_gender' => $params['passport_gender'],
                'passport_date_of_birth' => date('Y-m-d', strtotime($params['passport_date_of_birth'])),
                'nationality' => $params['nationality'],
                'passport_number' => $params['passport_number'],
                'updated_at' => date('Y-m-d H:i:s')
        ]);

        return ['status' => true, 'message' => 'Cập nhập thành công'];
    }

    public function deleteOrder($id)
    {
        OrderModel::where('id', $id)->delete();
    }

    public function updateStatus($id) {
        OrderModel::where('id', $id)->update(['status_order' => 'success']);
    }
}
