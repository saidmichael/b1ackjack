<?php

#Function: bj_shortname(Title)
#Description: Converts the title into a friendlier name.
function bj_shortname($title) {
	
    $title = strtolower($title);
    $title = str_replace(
		array(
			"å",
			"ø",
			" ",
			'/',
		),
		array(
			"aa",
			"o",
			"-",
			''
		),
	$title);
	$title = preg_replace(
		array(
			'/&.+?;/',
			'/[^a-z0-9 _-]/',
			'/\s+/',
			'|-+|'
		),
		array(
			'',
			'',
			'',
			'-'),
	$title);
    $title = trim($title, '-');
    if(empty($title)) {
		$title = '-';
	}

    return $title;
}

#Function: Formatted_for_editing(Content)
#Description: Changes text for the editor. Useful for stuff like special characters.
function formatted_for_editing($content) {
	$content = str_replace(
		'&',
		'&amp;',
		$content);
	$content = str_replace(
		'\'',
		'&#39;',
		$content);
	$content = str_replace(
		'"',
		'&#34;',
		$content);
	return $content;
}

#Function: wptexturize(Text)
#Description: Created by Matthew Mullenweg- http://photomatt.net.
function wptexturize($text){
	$text = str_replace(
		array(
			'&#039;',
			'&#39;',
		),
		'\'',
		$text);
	$text = str_replace(
		array(
			'&#034;',
			'&#34;',
			'&quot;'
		),
		'"',
		$text);
	$output='';
	//Capture tags and everything inside them
	$textarr = preg_split("/(<.*>)/Us",$text,-1,PREG_SPLIT_DELIM_CAPTURE);
	$stop = count($textarr); $next=true;//loopstuff
	for($i = 0; $i < $stop; $i++){
		$curl = $textarr[$i];

		if(isset($curl{0}) && '<' != $curl{0} && $next) { //If it's not a tag
			$curl = str_replace('---','&#8212;',$curl);
			$curl = str_replace('--','&#8212;',$curl);
			$curl = str_replace('--','&#8211;',$curl);
			$curl = str_replace('xn&#8211;','xn--',$curl);
			$curl = str_replace('...','&#8230;',$curl);
			$curl = str_replace('``','&#8220;',$curl);
			
			//This is a hack, look at this more later. Itworksprettywellthough.
			$cockney=array("'tain't","'twere","'twas","'tis","'twill","'til","'bout","'nuff","'round","'cause");
			$cockneyreplace=array("&#8217;tain&#8217;t","&#8217;twere","&#8217;twas","&#8217;tis","&#8217;twill","&#8217;til","&#8217;bout","&#8217;nuff","&#8217;round","&#8217;cause");
			$curl = str_replace($cockney,$cockneyreplace,$curl);
			
			$curl = preg_replace("/'s/",'&#8217;s',$curl);
			$curl = preg_replace("/'(\d\d(?:&#8217;|')?s)/","&#8217;$1",$curl);
			$curl = preg_replace('/(\s|\A|")\'/','$1&#8216;',$curl);
			$curl = preg_replace('/(\d+)"/','$1&#8243;',$curl);
			$curl = preg_replace("/(\d+)'/",'$1&#8242;',$curl);
			$curl = preg_replace("/(\S)'([^'\s])/","$1&#8217;$2",$curl);
			$curl = preg_replace('/(\s|\A)"(?!\s)/','$1&#8220;$2',$curl);
			$curl = preg_replace('/"(\s|\S|\Z)/','&#8221;$1',$curl);
			$curl = preg_replace("/'([\s.]|\Z)/",'&#8217;$1',$curl);
			$curl = preg_replace("/\(tm\)/i",'&#8482;',$curl);
			$curl = str_replace("''",'&#8221;',$curl);
				
			$curl = preg_replace('/(\d+)x(\d+)/',"$1&#215;$2",$curl);	
		} elseif(strstr($curl,'<code') || strstr($curl,'<pre') || strstr($curl,'<kbd' || strstr($curl,'<style') || strstr($curl,'<script'))){
			//strstr is fast
			$next = false;
		} else {
			$next = true;
		}
		$curl = preg_replace('/&([^#])(?![a-zA-Z1-4]{1,8};)/','&#038;$1',$curl);
		$output .= $curl;
	}
	return $output;
}

#Wpaotop
#By Wordpress too.
function clean_pre($text) {
	$text = str_replace('<br />', '', $text);
	$text = str_replace('<p>', "\n", $text);
	$text = str_replace('</p>', '', $text);
	return $text;
}

function wpautop($pee, $br = 1) {
	$pee = $pee . "\n"; // just to make things a little easier, pad the end
	$pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
	// Space things out a little
	$allblocks = '(?:table|thead|tfoot|caption|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|form|blockquote|address|math|style|script|object|input|param|p|h[1-6])';
	$pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
	$pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
	$pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines
	$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
	$pee = preg_replace('/\n?(.+?)(?:\n\s*\n|\z)/s', "<p>$1</p>\n", $pee); // make paragraphs, including one at the end
	$pee = preg_replace('|<p>\s*?</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
	$pee = preg_replace( '|<p>(<div[^>]*>\s*)|', "$1<p>", $pee );
	$pee = preg_replace('!<p>([^<]+)\s*?(</(?:div|address|form)[^>]*>)!', "<p>$1</p>$2", $pee);
	$pee = preg_replace( '|<p>|', "$1<p>", $pee );
	$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
	$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
	$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
	$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
	$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
	$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
	if ($br) {
		$pee = preg_replace('/<(script|style).*?<\/\\1>/se', 'str_replace("\n", "<WPPreserveNewline />", "\\0")', $pee);
		$pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
		$pee = str_replace('<WPPreserveNewline />', "\n", $pee);
	}
	$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
	$pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
	if ( strstr( $pee, '<pre' ) )
		$pee = preg_replace('!(<pre.*?>)(.*?)</pre>!ise', " stripslashes('$1') .  stripslashes(clean_pre('$2'))  . '</pre>' ", $pee);
	$pee = preg_replace( "|\n</p>$|", '</p>', $pee );
/**/
	return $pee;
}

?>
