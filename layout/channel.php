
<div id="chat"><?php
foreach($messages as $msg)
{
	echo '<p>[<a href="/message/'.$msg['message_id'].'">'.$msg['posted'].'</a>] <em><a href="/user/'.$msg['username'].'">'.$msg['username'].'</a></em>: '.$msg['content'].'</p>';
}

//echo '<pre>'.var_dump($_SESSION, session_id()).'</pre>';
?></div>

<form id="messagebox" method="post" action="/">
	<input type="text" name="message" autofocus autocomplete="off" />
	<input type="submit" value="Send" />
</form>
