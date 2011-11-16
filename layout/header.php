<!doctype html>

<html>
	<head>
		<meta charset="<?php echo CHARSET; ?>" />
		<title>Chat<?php if(isset($title)) echo ' | '.$title; ?></title>
		<base href="<?php echo SITE_URL; ?>" />
		
		<link rel="stylesheet" href="/css/reset.css" />
		<link rel="stylesheet" href="/css/screen.css" media="screen" />
		
		<script src="/js/jquery-1.6.4.min.js"></script>
		<script src="/js/jquery.scrollTo-1.4.2-min.js"></script>
		<script src="/js/chat.js"></script>
		
	</head>
	
	<body>
		<header>
			<h1><a href="/">Chat</a></h1>
			<h2>in <a href="/channel/<?php echo $channel->getName(); ?>"><?php echo $channel->getName(); ?></a> as ~<a href="/user/<?php echo $user->getUsername(); ?>"><?php echo $user->getUsername(); ?></a></h2>
			
			<nav>
				<ul>
					<?php if($user->loggedIn()): ?>
					<li><a href="/logout">Log out</a></li>
					<?php else: ?>
					<li><a href="/login">Log in</a>
						<?php include('loginform.php'); ?>
					</li>
					<li><a href="/register">Register</a>
						<?php include('registerform.php'); ?>
					</li>
					<?php endif; ?>
				
					<li><a href="/channels">Channels</a>
						<ul>
							<li><a href="/channel/mt">Mt</a></li>
							<li><a href="/channel/ma">Ma</a></li>
							<li><a href="/channel/iv">Iv</a></li>
							<li><a href="/channel/gv">Gv</a></li>
						</ul>
					</li>
				
					<li><a href="/logs">Logs</a></li>
					<li><a href="/faq">FAQ</a></li>
				</ul>
			</nav>
		</header>
