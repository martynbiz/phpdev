<?php

namespace app\models;

use framework\Model\RowGateway;

class User extends RowGateway {
	
	public $id;
	public $name;
	public $email;
	public $password;
	public $active;
}
