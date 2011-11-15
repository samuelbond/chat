<?php
/*
 * Functions.inc.php
 * 
 * Function for use with /commands in the chat.
 */

/**
 * rev function.
 * Reverse a string
 * @access public
 * @param string $str
 * @return string
 */
function rev($str)
{
	return strrev($str);
}

/**
 * rot13 function.
 * Rotate a strings characters 13 places.
 * @access public
 * @param string $str
 * @return string
 */
function rot13($str)
{
	return str_rot13($str);
}

function name()
{
	return 'You can <a href="/register">register a nickname/password combo</a> if you want to.';
}

/**
 * uname function.
 * Get uname -a of the server.
 * @access public
 * @return string
 */
function uname()
{
	return `uname -a`;
}

/**
 * image function.
 * Get a Google image result for a search term.
 * @access public
 * @param mixed $term
 * @return string
 */
function image($term)
{
	if(empty($term))
		return false;
		
	$res = 'https://ajax.googleapis.com/ajax/services/search/images?v=1.0&rsz=8&safe=moderate&q='.urlencode($term);
	$data = json_decode(file_get_contents($res));
	
	$img = $data->responseData->results[ mt_rand(0,7) ];
	var_dump($img);
	$html = 'Image search for "'.$term.'": <a href="'.$img->originalContextUrl.'"><img src="'.$img->unescapedUrl.'" title="'.$img->titleNoFormatting.'" alt="'.$img->titleNoFormatting.'" /></a>';
	
	return $html;
}

function youtube($term)
{
	if(empty($term))
		return false;
		
	$res = 'https://gdata.youtube.com/feeds/api/videos?alt=json&orderBy=relevance&q='.urlencode($term);
	$data = json_decode(file_get_contents($res));
	$url = $data->feed->entry[0]->link[0]->href;
	
	return $url;
}

function l33t($text)
{
	$letters = array(
		'a'	=>	'4',
		'b'	=>	'|3',
		'c'	=>	'(',
		'd'	=>	'[)',
		'e'	=>	'3',
		'f'	=>	'|=',
		'g'	=>	'9',
		'h'	=>	']-[',
		'i'	=>	'1',
		'j'	=>	'_)',
		'k'	=>	'|<',
		'l'	=>	'|_',
		'm'	=>	'|\/|',
		'n'	=>	'|\|',
		'o'	=>	'0',
		'p'	=>	'|*',
		'q'	=>	'0_',
		'r'	=>	'2',
		's'	=>	'5',
		't'	=>	'7',
		'u'	=>	'|_|',
		'v'	=>	'\/',
		'w'	=>	'\/\/',
		'x'	=>	'><',
		'y'	=>	'`/',
		'z'	=>	'~/_'
	);
	foreach($letters as $letter => $l33t)
	{
		$text = str_replace($letter, $l33t, $text);
	}
	return $text;
}

function servertime()
{
	return date('r');
}

/**
 * pug function.
 * Get a pug.
 * @access public
 * @return pug
 */
function pug()
{
	$pug = json_decode(file_get_contents('http://pugme.herokuapp.com/random'));
	$html = '<a href="'.$pug->pug.'"><img src="'.$pug->pug.'" alt="Pug!" /></a>';
	return $html;
}

/**
 * pugbomb function.
 * Throw a pug bomb.
 * @access public
 * @param int $count
 * @return pugs
 */
function pugbomb($count)
{
	if($count > 42)
		$count = 42;
	
	$pugs = json_decode(file_get_contents('http://pugme.herokuapp.com/bomb?count='.$count));
	$html = '';
	foreach($pugs->pugs as $pug)
	{
		$html .= '<a href="'.$pug.'"><img src="'.$pug.'" alt="Pug!" /></a>';
	}
	return $html;
}
