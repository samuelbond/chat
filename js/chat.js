var $chat = $('#chat');

function formatMessage(msg)
{
	console.log('Adding formatted message #' + msg.message_id + ' with content ' + msg.content);

	return '<p id="' + msg.message_id + '" class="new" style="display:none">[<a href="/message/' + msg.message_id + '">' + msg.posted + '</a>] <em><a href="/user/' + msg.username + '">' + msg.username + '</a></em>: ' + msg.content + '</p>'
}

function refresh(chan)
{
	console.log('Refreshing...');
	
	$.get(
		'/ajax.php?channel=' + chan,
		function(data)
		{
			var messages = eval(data);
			
			for ( i in messages )
			{
				var msg = messages[i];
				
				if( ! document.getElementById( msg.message_id ) )
				{
					var text = formatMessage(msg);
					
					console.log(msg);
					
					$('#chat').append(text);
					$('#chat .new').slideDown();
				}
			}
		}
	);
	return null;
}

function scrollDown(q)
{
	var oldscrollHeight = $("#chat")[0].scrollHeight;
	var newscrollHeight = oldscrollHeight - (14 * q);
	
	if(newscrollHeight > oldscrollHeight)
	{
		$("#chat").animate({ scrollTop: bottom }, 'normal');
	}
}

$(document).ready(function(){
	console.log('Initializing...');
	
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
		console.log('Sending message...');
		
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
				var message = eval('['+data+']');
				console.log(message[0]);
				message = message[0];
				message.message_id = parseInt( $('#chat p').last().attr('id') ) + 1;
				console.log(message.message_id);


				$('#chat').append(formatMessage(message));
				$('#chat .new').slideDown();
				$(input).val('');
			}
		);
	});
	////////////////
	
	var channel = $('form#messagebox input[name=channel]').attr('value');
	
	if(document.getElementById('chat'))
		window.setInterval(function(){ refresh(channel); }, 1000);
});