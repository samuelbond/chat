<div id="content">

<h1><a href="/register">Registration</a></h1>
<p><?php
if(isset($message))
	echo $message;
else
	include('registerform.php'); ?></p>

</div>