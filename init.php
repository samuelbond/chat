<?php
$classes_directory  =  dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes';

include('includes/config.inc.php');
include('includes/autoload.inc.php');

$c = new Chat;

$added = null;
if(isset($_POST['message']))
{
	$msg = $_POST['message'];
	$ret = $c->addMessage($msg);
	if(is_string($ret))
	{
		$added = true;
		$output = $ret;
		unset($ret);
	}
	else
	{
		$added = false;
	}
	
	
}
