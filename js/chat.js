// var $('#chat') = $('#chat');

function refresh()
{
	console.log('Refreshing...');
	
	$.get(
		'/ajax.php',
		function(data)
		{
			var messages = eval(data);
			
			for ( i in messages )
			{
				var msg = messages[i];
				var text = '<p id="message_' + msg.message_id + '" class="new" style="display:none">[<a href="/message/' + msg.message_id + '">' + msg.posted + '</a>] <em><a href="/user/' + msg.username + '">' + msg.username + '</a></em>: ' + msg.content + '</p>';
				
				
				if( ! document.getElementById( 'message_' + msg.message_id ) )
				{
					console.log(text);
					$('#chat').append(text);
					$('#chat .new').slideDown();
				}
			}
		}
	);
	// return null;
}

$(document).ready(function(){
	
	// Header toggles
	$('a[href="/login"], a[href="/register"]').click(function(){
		$('header ul form').slideUp();
		$(this).next('form').slideToggle();
		return false;
	});
	
	$('a[href="/channels"]').click(function(){
		$('header form').slideUp();
		$(this).next('ul').slideToggle();
		return false;
	});
	////////////////
	
	
	// Message submitting
	$('form#messagebox').submit(function(event)
	{
		event.preventDefault();
		
		var $form = $(this),
			input = 'input[name="message"]';
			//msg = $form.find(input).val();
			//url = $form.attr('action');
		
		$.post(
			'/ajax.php',
			$form.serialize(),
			function(data) // On succes
			{
				$('#chat').append('<p class="new" style="display:none">' + data + '</p>');
				$('#chat .new').slideDown();
				$(input).val('');
			}
		);
	});
	////////////////
	
	
	if(document.getElementById('chat'))
		window.setInterval(refresh, 5000);
});