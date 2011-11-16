<div id="content">
	<?php
	if(isset($message))
		echo '<h1>'.$message.'</h1>';
	else
	{
		echo '<h1><a href="/login">Login</a></h1>';
		include('loginform.php');
	}
	?>
</div>