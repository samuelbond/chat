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

/**
 * name function.
 * Gives a message on how to get a different nickname.
 * @access public
 * @return string
 */
function name()
{
	return 'Go <a href="/register">register a nickname/password combo</a>, at least, if you want to.';
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

function sun($when)
{
	if(empty($when))
		$when = 'tomorrow';
	
	$when = strtotime($when);
	$lat = 52.22;
	$long = 4.53;
	
	$sunrise = date_sunrise($when, SUNFUNCS_RET_STRING, $lat, $long, 90, 1);
	$sunset = date_sunset($when, SUNFUNCS_RET_STRING, $lat, $long, 90, 1);
	return 'Amsterdam, '.strftime('%d %B %Y', $when).', sunrise at: '.$sunrise.', sunset at: '.$sunset.'.';
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
	
	$html = 'Image search for "'.$term.'": <a href="'.$img->originalContextUrl.'"><img src="'.$img->unescapedUrl.'" title="'.$img->titleNoFormatting.'" alt="'.$img->titleNoFormatting.'" /></a>';
	
	return $html;
}

function youtube($term)
{
	if(empty($term))
		return false;
		
	$res = 'https://gdata.youtube.com/feeds/api/videos?alt=json&orderBy=relevance&max-results=8&q='.urlencode($term);
	$data = json_decode(file_get_contents($res));
	$videos = $data->feed->entry;
	$video = $videos[ mt_rand(0,7) ];
	
	// This is ugly
	if(empty($video))
		return 'Youtube search for "'.$term.'" didn\'t return any videos.';
	
	foreach($video->link as $link)
		if($link->rel == 'alternate' and $link->type == 'text/html')
		{
			$link = parse_url($link->href);
			break;
		}
	
	$code = str_replace('&feature=youtube_gdata', '', $link['query']);
	$code = substr($code, 2);
	
	return 'Youtube search for "'.$term.'": '.
			'<iframe width="560" height="315" src="http://www.youtube.com/embed/'.$code.'" frameborder="0" allowfullscreen></iframe>';
}

/**
 * xkcd function.
 * Get an xkcd comic by its number.
 * @access public
 * @param mixed $num
 * @return string
 */
function xkcd($num)
{
	$c = json_decode(file_get_contents('http://xkcd.com/'.urlencode($num).'/info.0.json'));
	if(is_null($c))
		return 'xkcd: comic "'.$num.'" not found.';
	
	return '<a href="http://xkcd.com/'.$c->num.'"><img src="'.$c->img.'" alt="'.$c->alt.'" /></a>';
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

/**
 * uptime function.
 * Returns server uptime and load averages.
 * @access public
 * @return string
 */
function uptime()
{
	return `uptime`;
}

/**
 * servertime function.
 * Returns RFC 2822 formatted server time.
 * @access public
 * @return string
 */
function servertime()
{
	return date('r');
}

/**
 * pug function.
 * Gets a pug.
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
 * Throws a pug bomb.
 * @access public
 * @param int $count
 * @return pugs
 */
function pugbomb($count)
{
	if($count > 32)
		$count = 32;
	
	$pugs = json_decode(file_get_contents('http://pugme.herokuapp.com/bomb?count='.$count));
	$html = '';
	foreach($pugs->pugs as $pug)
	{
		$html .= '<a href="'.$pug.'"><img src="'.$pug.'" alt="Pug!" /></a>';
	}
	return $html;
}
