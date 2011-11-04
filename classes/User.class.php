<?php
class User
{
	public $id = 1;
	public $username;
	public $registered;
	
	private $db;
	
	public function __construct()
	{
		$this->db = new Database;
		$this->username = 'anonymous_coward';
	}
	
	private function verify($password)
	{
		if(!is_string($this->username))
			$pass = Chat::sanitize($password);
		
		
	}
	
	public function getUsername()
	{
		return $this->username;
	}
	
	public function setUsername($un)
	{
		if(is_string($un))
		{
			$this->username = $un;
		}
		else
		{
			return false;
		}
	}
	
	public function setId($i)
	{
		if(is_int($i))
		{
			$this->id = $i;
		}
		else
		{
			return false;
		}
	}
	
	public function __clone()
	{
	
	}
	
	public function __destruct()
	{
	
	}
}