<?php

namespace app\model;

use app\core\model\ActiveRecord;

class User extends ActiveRecord
{
	protected $table = 'user';
	protected $primaryKey = 'id';
	public $id;
	public $username;
	public $password;
	public $phone;
}
