
<div id="chat"><?php
foreach($messages as $msg)
{
	echo '<p id="'.$msg['message_id'].'">[<a href="/message/'.$msg['message_id'].'">'.$msg['posted'].'</a>] <em><a href="/user/'.$msg['username'].'">'.$msg['username'].'</a></em>: '.$msg['content'].'</p>';
}
?></div>

<form id="messagebox" method="post" action="/channel/<?php echo $channel->getName(); ?>">
	<input type="text" name="message" autofocus autocomplete="off" />
	<input type="hidden" name="channel" value="<?php echo $channel->getName(); ?>" />
	<input type="submit" value="Send" />
</form>
