<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class OrderDetailModel extends Model
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
        return 'order_detail';
    }

    public function addOrderDetail($params) {
        $data = [];
        foreach ($params['passport_full_name'] as $index => $eachData) {
            if (!in_array($params['passport_gender'][$index], ['male', 'female'])) {
                DB::table('order')->where('id', $params['order_id'])->delete();

                return ["error" => ["user_id" => ["User id is required!"]], "status_code" => 422];
            }
            if (DateTime::createFromFormat('Y-m-d', $params['passport_date_of_birth'][$index]) === false) {
                DB::table('order')->where('id', $params['order_id'])->delete();

                return ["error" => ["user_id" => ["Passport date of birth is not valid!"]], "status_code" => 422];
            }
            $data[$index]['order_id'] = $params['order_id'];
            $data[$index]['passport_full_name'] = $params['passport_full_name'][$index];
            $data[$index]['passport_gender'] = $params['passport_gender'][$index];
            $data[$index]['passport_date_of_birth'] = $params['passport_date_of_birth'][$index];
            $data[$index]['nationality'] = $params['nationality'][$index];
            $data[$index]['passport_number'] = $params['passport_number'][$index];
            $data[$index]['created_at'] = date('Y-m-d H:i:s');
            $data[$index]['updated_at'] = date('Y-m-d H:i:s');
        }
        OrderDetailModel::insert($data);
        return true;
    }

    public function deleteOrderDetail($orderId) {
        OrderDetailModel::where('order_id')->delete();
    }
}
