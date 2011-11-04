<?php
/**
 * Chat class.
 */
class Chat
{
	private $channel;
	private $channel_id = 1;
	
	private $user;
	
	/**
	 * db
	 * Database handle.
	 * @var mixed
	 * @access private
	 */
	private $db;
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		$this->db = new Database;
		$this->user = new User;
		
		$this->channel = DEFAULT_CHAN;
	}
	
	/**
	 * getMessages function.
	 * Get an associative array with the lates messages.
	 * @access public
	 * @param int $amount (default: 40)
	 * @return array
	 */
	public function getMessages($amount = 40)
	{
		$chan = $this->db->quote( $this->getChan() );
		$sql = 'SELECT message_id,posted,content,users.username,channels.name
				FROM messages 
				INNER JOIN channels ON messages.channel = channels.channel_id 
				LEFT JOIN users ON messages.owner = users.user_id 
				WHERE name='.$chan.'
				ORDER BY posted DESC LIMIT ' . $amount;
		
		$statement = $this->db->query($sql);
		$output = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		return array_reverse($output);
	}
	
	/**
	 * addMessage function.
	 * Add a message to the database.
	 * @access public
	 * @param string $msg
	 * @return bool
	 */
	public function addMessage($msg)
	{
		// Refuse a message when it's empty
		if(is_null($msg) or empty($msg))
			return false;
		
		// Refuse a message when it's over 140 chars
		if(strlen($msg) > 140)
			return false;
		
		$msg = $this->sanitize($msg);
		
		$sql = 'INSERT INTO messages (content, channel, ip)
				VALUES (:msg, :chan, :ip)';
		$st = $this->db->prepare($sql);
		$st->bindParam(':msg', $msg);
		$st->bindParam(':chan', $this->channel_id);
		$st->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
		
		if($st->execute())
		{
			// If the query succeeded, return the formatted message
			$message = '['.date('Y-m-d H:i:s').'] <em>'.
						$this->user->getUsername().
						'</em>: '.$msg;
			return $message;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * getMessage function.
	 * Get a message by its identifier 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function getMessage($id)
	{
		$sql = 'SELECT message_id,posted,content
				FROM messages WHERE message_id=:mid LIMIT 1';
		$st = $this->db->prepare($sql);
		$st->bindParam(':mid', $id);
		
		if($st->execute())
		{
			return $st->fetch(PDO::FETCH_ASSOC);
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * sanitize function.
	 * Sanitize user input
	 * @access public
	 * @static
	 * @param mixed $input
	 * @return void
	 */
	public static function sanitize($input)
	{
		$output = htmlentities($input);
		
		return $output;
	}
	
	public static function parseURL($u)
	{
		$seq = explode('/', $u);
		$seq = array_filter($seq);
		
		return $seq;
	}
	
	/**
	 * getUser function.
	 * Get a user by their username
	 * @access public
	 * @param mixed $username
	 * @return void
	 */
	public function getUser($username)
	{
		$sql = 'SELECT user_id,username,registered
				FROM users WHERE username=:un LIMIT 1';
		
		$st = $this->db->prepare($sql);
		$username = $this->sanitize($username);
		
		$st->bindParam(':un', $username);
		$st->setFetchMode(PDO::FETCH_INTO, $this->user);
		
		if($st->execute())
		{
			return true;
			echo 'haaaai';
			var_dump($this->user);
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * createChan function.
	 * Create a new channel
	 * @access public
	 * @param mixed $channame
	 * @return void
	 */
	public function createChan($channame)
	{
		$sql = 'INSERT INTO channels (name) VALUES (:name)';
		$st = $this->db->prepare($sql);
		
		$channame = $this->sanitize($channame);
		$st->bindParam(':name', $channame);
		
		
		if($st->execute())
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * getChan function.
	 * Get the current channel
	 * @access public
	 * @return void
	 */
	public function getChan()
	{
		return $this->channel;
	}
	
	/**
	 * setChan function.
	 * Set the current channel
	 * @access public
	 * @param mixed $channame
	 * @return void
	 */
	public function setChan($channame)
	{
		if(is_string($channame))
		{
			$this->channel = $channame;
		}
		else
		{
			return false;
		}
	}
}