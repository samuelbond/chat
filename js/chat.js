$(document).ready(function(){
	
	// Header toggles
	$('a[href="/login"], a[href="/register"]').click(function(){
		$('header ul form').slideUp();
		$(this).next('form').slideToggle();
		return false;
	});
	
	$('a[href="/channels"]').click(function(){
		$(this).next('ul').slideToggle();
		return false;
	});
	
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
	
	function refresh()
	{
		$.get(
			'/ajax.php',
			function(data)
			{
				//forEach()
				$('#chat').append('<pre>' + data + '</pre>');
			}
		);
	}
	//refresh();
	
	// window.setInterval(refresh, 5000);
});