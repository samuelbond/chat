var $chat = $('#chat');

function formatMessage(msg)
{
	return '<p id="' + msg.message_id + '" class="new" style="display:none">[<a href="/message/' + msg.message_id + '">' + msg.posted + '</a>] <em><a href="/user/' + msg.username + '">' + msg.username + '</a></em>: ' + msg.content + '</p>'
}

function refresh(chan)
{
	$.get(
		'/ajax.php?channel=' + chan,
		function(data)
		{
			var messages = eval(data), scroll = false;
			
			for ( i in messages )
			{
				var msg = messages[i];
				
				if( ! document.getElementById( msg.message_id ) )
				{
					var text = formatMessage(msg);
					
					$('#chat').append(text);
					if( $('#chat .new').slideDown() )
						scroll = true;
				}
			}
			
			if(scroll == true)
				$.scrollTo('p:last-child', 1000);
		}
	);
	return null;
}

$(document).ready(function(){
	
	// Header toggles
	$('a[href="/login"], a[href="/register"]').click(function(){
		$('header ul form').slideUp();
		$(this).next('form').slideToggle();
		return false;
	});
	
	// Channel toggle
	$('a[href="/channels"]').click(function(){
		$('header form').slideUp();
		$(this).next('ul').slideToggle();
		return false;
	});
	
	
	// Message submitting
	$('form#messagebox').submit(function(event)
	{
		event.preventDefault();
		
		var $form = $(this),
			input = 'input[name="message"]';
		
		$.post(
			'/ajax.php',
			$form.serialize(),
			function(data) // On succes
			{
				var message = eval('['+data+']');
				
				message = message[0];
				message.message_id = parseInt( $('#chat p').last().attr('id') ) + 1;
			}
		);
		$(input).val('');
	});
	
	var channel = $('form#messagebox input[name=channel]').attr('value');
	
	if(document.getElementById('chat'))
		window.setInterval(function(){ refresh(channel); }, 1000);
});