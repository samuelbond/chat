<?php
/**
 * User class.
 */
class User
{
	/**
	 * id
	 * The user's identifier
	 * (default value: 1)
	 * @var int
	 * @access public
	 */
	public $id = 1;
	
	public $username;
	
	/**
	 * registered
	 * The user's registration date
	 * @var mixed
	 * @access public
	 */
	public $registered;
	
	/**
	 * db
	 * Database handle
	 * @var mixed
	 * @access private
	 */
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
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($i)
	{
		if(is_int($i) or is_numeric($i))
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