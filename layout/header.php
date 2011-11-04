<!doctype html>

<html>
	<head>
		<meta charset="utf-8" />
		<title>Chat</title>
		<base href="<?php echo SITE_URL; ?>" />
		
		<link rel="stylesheet" href="/css/reset.css" />
		<link rel="stylesheet" href="/css/screen.css" media="screen" />
		
		<script src="/js/jquery-1.6.4.min.js"></script>
		<script src="/js/chat.js"></script>
		
	</head>
	
	<body>
		<header>
			<h1><a href="/">Chat</a></h1>
			<h2>~<a href="/channel/<?php echo $channel; ?>"><?php echo $channel; ?></a></h2>
			
			<nav>
				<ul>
					<li><a href="/login">Log in</a>
						<form method="post" action="/login">
							<input type="text" name="username" placeholder="Username" />
							<input type="password" name="password" placeholder="Password" />
							<input type="submit" value="Log in" />
						</form>
					</li>
					<li><a href="/register">Register</a>
						<form method="post" action="/register">
							<input type="text" name="username" placeholder="Username" />
							<input type="password" name="password" placeholder="Password" />
							<input type="submit" value="Register" />
						</form>
					</li>
				
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
