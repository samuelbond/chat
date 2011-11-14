<?php
/*
 * init.php
 */

$classes_directory  =  dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes';

include('includes/config.inc.php');
include('includes/autoload.inc.php');

session_start();

$c = new Chat;

$added = null;
if(isset($_POST['message']))
{
	if(isset($_POST['channel']))
		$c->setChan($_POST['channel']);
	
	$msg = $_POST['message'];
	$ret = $c->addMessage($msg);
	if(is_array($ret))
	{
		$added = true;
		$output = json_encode($ret);
		unset($ret);
	}
	else
	{
		$added = false;
	}
}
