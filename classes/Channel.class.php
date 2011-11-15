<?php
class Channel
{
	protected $channel_id;
	protected $name;
	protected $created;
	
	public function __construct($name = '')
	{
		$this->name = $name;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getId()
	{
		if(!is_null($this->channel_id))
		{
			return $this->channel_id;
		}
		else
		{
			return false;
		}
	}
	
	public function setId($id)
	{
		$this->channel_id = $id;
	}
	
	public function __get($p)
	{
		return $this->$p;
	}
	
	public function __set($p, $v)
	{
		$this->$p = $v;
	}
}