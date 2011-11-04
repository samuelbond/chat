<?php
class Database extends PDO
{
	public function __construct()
	{
		try
		{
			$dsn = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST;
			parent::__construct($dsn, DB_USER, DB_PASS);
		}
		catch(PDOException $e)
		{
			echo 'Connection failed. ' . $e->getMessage();
			exit;
		}
	}
}