<?php
require_once('init.php');

session_start();

////////////////

$seq = Chat::parseURL($_SERVER['REQUEST_URI']);
$stuff = array('register', 'login', 'logout', 'logs', 'channels', 'faq');

if(isset($seq[1]) and isset($seq[2]))
{
	$page = $seq[1];
	$id = $seq[2];
}
elseif(isset($seq[1]))
{
	$page = $seq[1];
	$id = null;
}
else
{
	$page = 'home';
	$id = null;
}

if($page == 'home' or
	$page == 'channel' and isset($id))
{
	$file = 'channel';
	
	if(isset($id))
		$c->setChan($id);
	
	$messages = $c->getMessages(20);
}
elseif($page == 'message'
	and is_numeric($id))
{
	$file = 'message';
	$message = $c->getMessage($id);
}
elseif($page == 'user'
	and isset($id))
{
	$file = 'profile';
	$profile = $c->getUser($id);
	
}
elseif(in_array($page, $stuff) and $id == null)
{
	$file = $page;
}

$channel = $c->getChan();

////////////////
/*
 - Channel
 - Message
 */

include('layout/header.php');
include('layout/' . $file . '.php');
include('layout/footer.php');