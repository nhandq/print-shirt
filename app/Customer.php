<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
	protected $fillable = [
							'name',
							'email',
						];
	protected $primaryKey = 'id';
	protected $table = 'customers';
	public $timestamps = false;
}
