<?php
/*
 * index.php
 */

require_once('init.php');

if($added === false)
	header('HTTP/1.1 500 Internal Server Error');

if(isset($output))
{
	echo $output;
}

if($added === null)
{
	if(isset($_GET['channel']))
		$msgs = $c->getMessages(12, $_GET['channel']);
	else
		$msgs = $c->getMessages(12);
	
	echo json_encode($msgs);
}