<?php
/*
 * index.php
 */

require_once('init.php');

header('Content-Type: text/html; charset='.CHARSET);

////////////////

$seq = Chat::parseURL($_SERVER['REQUEST_URI']);
$stuff = array('register', 'login', 'logout', 'logs', 'channels', 'faq');

// Check the URL parameters
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
	$title = $id;
	
	if(isset($id))
	{
		$c->setChan($id);
		$messages = $c->getMessages(40, $id);
	}
	else
	{
		$messages = $c->getMessages(40);
	}
}
elseif($page == 'message'
	and is_numeric($id))
{
	$file = 'message';
	$title = 'Message #'.$id;
	$message = $c->getMessage($id);
}
elseif($page == 'user'
	and isset($id))
{
	$file = 'profile';
	$title = $id;
	$profile = $c->getUser($id);
	
}
elseif($page == 'login' and
		!empty($_POST['username']) and !empty($_POST['password']))
{
	$file = 'login';
	$c->loginUser($_POST['username'], $_POST['password']);
}
elseif($page == 'logout')
{
	$file = 'logout';
	if($c->logoutUser())
		$message = 'You are logged out.';
	else
		$message = 'You weren\'t even logged in, silly!';
}
elseif($page == 'register' and
		!empty($_POST['username']) and !empty($_POST['password']))
{
	$file = 'register';
	
	if( $c->addUser($_POST['username'], $_POST['password']) )
		$content = 'Succes, you are registered.';
	else
		$content = 'Something went wrong, you aren\'t registered.';
}
elseif(in_array($page, $stuff) and $id == null)
{
	$file = $page;
	$title = ucfirst($page);
}

$channel = $c->getChan();
$user = $c->getUser();

////////////////

include('layout/header.php');
include('layout/' . $file . '.php');
include('layout/footer.php');