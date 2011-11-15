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
	protected $user_id = 1;
	
	/**
	 * username
	 * 
	 * (default value: 'anonymous_coward')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $username = 'anonymous_coward';
	
	/**
	 * registered
	 * The user's registration date
	 * @var mixed
	 * @access public
	 */
	protected $registered;
	
	protected $loggedin = false;
	
	public function __construct()
	{
		
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
		return $this->user_id;
	}
	
	public function setId($i)
	{
		if(is_int($i) or is_numeric($i))
		{
			$this->user_id = $i;
		}
		else
		{
			return false;
		}
	}
	
	public function getJoinDate()
	{
		return $this->registered;
	}
	
	public function loggedIn()
	{
		return $this->loggedin;
	}
	
	public function setLoggedIn($l)
	{
		if($l == true)
		{
			$this->loggedin = true;
		}
		else
		{
			$this->loggedin = false;
		}
	}
	
	/**
	 * getArray function.
	 * Return user info as an associative array.
	 * @access public
	 * @return void
	 */
	public function getArray()
	{
		return array(
			'user_id' => $this->getId(),
			'username' => $this->getUsername(),
			'registered' => $this->registered,
			'loggedin' => $this->loggedin
		);
	}
	
	public function __get($p)
	{
		return $this->$p;
	}
	
	public function __set($p, $v)
	{
		$this->$p = $v;
	}
	
	public function __clone()
	{
	
	}
	
	public function __destruct()
	{
	
	}
}