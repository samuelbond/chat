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
		$sql = 'SELECT message_id,posted,content,users.username,channels.name FROM messages 
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
		if(is_null($msg) or empty($msg))
			return false;
		
		if(strlen($msg) > 140)
			return false;
		
		$msg = $this->sanitize($msg);
		
		$sql = 'INSERT INTO messages (content, channel, ip) VALUES (:msg, :chan, :ip)';
		$st = $this->db->prepare($sql);
		$st->bindParam(':msg', $msg);
		$st->bindParam(':chan', $this->channel_id);
		$st->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
		
		if($st->execute())
		{
			$message = '['.date('Y-m-d H:i:s').'] <em>'.$this->user->getUsername().'</em>: '.$msg;
			return $message;
		}
		else
		{
			return false;
		}
	}
	
	public function getMessage($id)
	{
		return 'Bericht '.$id;
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
	
	public function getUser($username)
	{
		$sql = 'SELECT username,messages.content,messages.posted FROM users WHERE username=:un LEFT JOIN messages ON user.user_id = messages.owner ORDER BY posted DESC LIMIT 100';
		$st = $this->db->prepare($sql);
		$username = $this->sanitize($username);
		$st->bindParam(':un', $username);
		
		return 'Profiel '.$username;
		//return $st->fetchAll(PDO::FETCH_ASSOC);
	}
	
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
	
	public function getChan()
	{
		return $this->channel;
	}
	
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