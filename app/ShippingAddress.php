<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
	protected $fillable = [
							'name',
							'phone',
						];
	protected $primaryKey = 'id';
	protected $table = 'shipping_addresses';
	public $timestamps = false;
}
