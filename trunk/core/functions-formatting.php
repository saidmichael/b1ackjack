<?php

#Function: bj_clean(Content)
#Description: Cleans the content....duh.
function bj_clean($content){
	$content = stripslashes($content);
	$content = str_replace(
		array(
			"<",
			">",
			"\"",
			"'",
			"&"),
		array(
			"&#60;",
			"&#62;",
			"&#34;",
			"&#39;",
			"&#38;"),
		$content);
	return $content;
}

#Function:bj_excerpt(Content, Length)
#Description:Makes an excerpt out of the content.
function bj_excerpt($content,$length){
	$content = str_replace(
		array(
			"<p>",
			"</p>",
			"<br/>",
			"\n",
			"\r"
		),
		array(
			"",
			"",
			"",
			"",
			""
		),
		$content);
	return $content;
}

#Function: formatted_for_editing(Content)
#Description: Formats it for a textarea.
function formatted_for_editing($content){
	$content=str_replace(
		array(
			"&"
		),
		array(
			"&amp;"
		),
		$content);
	echo $content;
}

#Function:wptexturize(Text)
#Description: Created by Matthew Mullenweg- http://photomatt.net.
function wptexturize($text){
	$text = str_replace(
		array(
			'&#039;',
			'&#39;',
			'&amp;'
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

?>