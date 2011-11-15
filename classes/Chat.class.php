<?php
/**
 * Chat class.
 */
class Chat
{	
	private $chan;
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
		$this->chan = new Channel(DEFAULT_CHAN);
		
		if(isset($_SESSION['user']) and 
			$_SESSION['user'] instanceof User)
		{
			$this->user = $_SESSION['user'];
		}
	}
	
	/**
	 * getMessages function.
	 * Get an associative array with the lates messages.
	 * @access public
	 * @param int $amount (default: 40)
	 * @return array
	 */
	public function getMessages($amount = 40, $channel = null)
	{
		if(is_string($channel))
			$channel = self::sanitize($channel);
		else
			$channel = $this->chan->getName();
		
		$sql = 'SELECT message_id,posted,content,users.username,channels.name
				FROM messages 
				INNER JOIN channels ON messages.channel = channels.channel_id 
				LEFT JOIN users ON messages.owner = users.user_id 
				WHERE channels.name = :chan
				ORDER BY message_id DESC LIMIT :amount ';
		
		$st = $this->db->prepare($sql);
		$st->bindValue(':chan', $channel);
		$st->bindParam(':amount', $amount, PDO::PARAM_INT);
		
		$st->execute();
		$output = $st->fetchAll(PDO::FETCH_ASSOC);
		
		return array_reverse($output);
	}
	
	/**
	 * addMessage function.
	 * Add a message to the database.
	 * @access public
	 * @param string $msg
	 * @return bool
	 */
	public function addMessage($msg, $user = null)
	{
		// Refuse a message when it's empty
		if(is_null($msg) or empty($msg))
			return false;
		
		// Refuse a message when it's over 140 chars
		if(strlen($msg) > 240)
			return false;
		
		$msg = $this->sanitize($msg);
		
		if(is_null($user))
			$user_id = $this->user->getId();
		elseif(is_int($user))
			$user_id = $user;
		elseif(is_string($user))
		{
			$u = $this->fetchUser($user);
			$user_id = $u->getId();
		}
		
		if(!$this->chan->getId())
			$this->fetchChan($this->chan->getName());
		
		$sql = 'INSERT INTO messages ( content, channel, ip, owner )
				VALUES ( :msg, :chan, :ip, :owner )';
		
		$st = $this->db->prepare($sql);
		$st->bindParam(':msg', $msg);
		$st->bindValue(':chan', $this->chan->getId());
		$st->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
		$st->bindParam(':owner', $user_id);
		
		$pattern = '/^\/\w{2,16}(\s?)/';
		if(preg_match($pattern, $msg, $match))
		{
			$function = substr(trim($match[0]), 1);
			$argument = trim(preg_filter($pattern, '', $msg));
			
			if($output = $this->executeFunction($function, $argument))
				$msg = $output;
		}
		else
		{
			$msg = self::wrapUrls($msg);
		}
		
		if($st->execute())
		{
			// If the query succeeded, return the message data
			return array(
				//'message_id' => 0,
				'posted' => date('Y-m-d H:i:s'),
				'content' => $msg,
				'username' => $this->user->getUsername(),
				'name' => $this->getChan()
			);
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * executeFunction function.
	 * Executes a /command from the function list.
	 * @access protected
	 * @param string $fu
	 * @param mixed $ar
	 * @return mixed
	 */
	protected function executeFunction($fu, $ar)
	{
		include('includes/functions.inc.php');
		
		$allowed_funcs = array('image', 'uname', 'name', 'time' => 'servertime', 'pug', 'pugbomb', 'rev', 'rot13', 'md5', 'sha1', 'l33t');
		
		
		if(array_key_exists($fu, $allowed_funcs) and function_exists($fu))
			return call_user_func($fu, $ar);
		else
			return false;
	}
	
	/**
	 * wrapUrls function.
	 * Identifies URLs in arbitraty strings and wraps them with <a> tags.
	 * @access public
	 * @static
	 * @param string $text
	 * @return string
	 */
	public static function wrapUrls($text)
	{
		// Crazy-ass regex from Gruber {@link http://daringfireball.net/2010/07/improved_regex_for_matching_urls}
		$pattern = '~(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))~';
		
		$output = preg_replace_callback($pattern, function($matches){
			return '<a href="'.$matches[0].'">'.$matches[0].'</a>';
		}, $text);
		
		return $output;
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
				FROM messages WHERE message_id = :mid LIMIT 1';
		
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
	
	/**
	 * parseURL function.
	 * Parses a URL and returns its pieces.
	 * @access public
	 * @static
	 * @param mixed $u
	 * @return void
	 */
	public static function parseURL($u)
	{
		$seq = explode('/', $u);
		$seq = array_filter($seq);
		
		return $seq;
	}
	
	/**
	 * fetchUser function.
	 * Get a user by their username
	 * @access public
	 * @param mixed $username
	 * @return mixed
	 */
	public function fetchUser($username)
	{
		$sql = 'SELECT user_id,username,registered
				FROM users WHERE username = :un LIMIT 1';
		
		$st = $this->db->prepare($sql);
		$st->bindValue(':un', self::sanitize($username));
		$st->setFetchMode(PDO::FETCH_CLASS, 'User');
		
		if($st->execute() and $user = $st->fetch())
		{
			return $user;
		}
		else
		{
			return false;
		}
	}
	
	public function getUser()
	{
		return $this->user;
	}
	
	/**
	 * addUser function.
	 * Adds a user to the database.
	 * @access public
	 * @param mixed $username
	 * @param mixed $password
	 * @return mixed
	 */
	public function addUser($username, $password)
	{
		$sql = 'INSERT INTO users ( username, password )
				VALUES ( :un, :pw )';
		
		$st = $this->db->prepare($sql);
		$st->bindValue(':un', self::sanitize($username));
		$st->bindValue(':pw', sha1($password));
		
		
		if($st->execute() and $user = $this->fetchUser($username))
		{
			$this->user = $user;
			return $user;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * loginUser function.
	 * Logs a user in.
	 * @access public
	 * @param mixed $username
	 * @param mixed $password
	 * @return bool
	 */
	public function loginUser($username, $password)
	{
		$sql = 'SELECT user_id,username,registered FROM users WHERE username = :un AND password = :pw';
		$st = $this->db->prepare($sql);
		$st->bindValue(':un', self::sanitize($username));
		$st->bindValue(':pw', sha1($password));
		$st->setFetchMode(PDO::FETCH_INTO, $this->user);
		
		if($st->execute() and $st->fetch())
		{
			$this->user->setLoggedIn(true);
			$_SESSION['user'] = $this->getUser();
			$this->addMessage($this->user->getUsername().' logged in.', 0);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * logoutUser function.
	 * Logs the current user out.
	 * @access public
	 * @return void
	 */
	public function logoutUser()
	{
		$this->addMessage($this->user->getUsername().' logged out.', 0);
		$this->user = new User;
		session_destroy();
	}
	
	/**
	 * deleteUser function.
	 * Deletes a user from the database;
	 * @access public
	 * @param mixed $username
	 * @return bool
	 */
	public function deleteUser($username)
	{
		$sql = 'DELETE FROM user
				WHERE username = :un LIMIT 1';
		
		$st = $this->db->prepare($sql);
		$st->bindValue(':un', self::sanitize($username));
		
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
	
	protected function fetchChan($name)
	{
		$sql = 'SELECT channel_id, name, created
				FROM channels WHERE name = :cn';
		
		$st = $this->db->prepare($sql);
		$st->bindValue(':cn', self::sanitize($name));
		$st->setFetchMode(PDO::FETCH_INTO, $this->chan);
		
		if($st->execute() and $st->fetch())
		{
			$_SESSION['chan'] = $this->chan;
			return $this->chan;
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
		return $this->chan;
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
			$this->chan->setName($channame);
			return true;
		}
		else
		{
			return false;
		}
	}
}