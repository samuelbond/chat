<?php
require_once('init.php');

header('Content-type: text/plain');

if($added === false)
	header('HTTP/1.1 500 Internal Server Error');

if(isset($output))
	echo $output;

if($added === null)
	echo json_encode($c->getMessages(10));