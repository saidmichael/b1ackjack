<?php

#Function: bj_clean(Content)
#Description: Cleans the content....duh.
function bj_clean($content) {
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

#Function: bj_excerpt(Content,Length)
#Description: Makes an excerpt out of the content.
function bj_excerpt($content,$length) {
	$content = str_replace(
		array(
			"<p>",
			"</p>",
			"<br />",
			"\n",
			"\r"
		),
		array(
			"",
			"",
			"",
			" ",
			""
		),
		$content);
	return $content;
}

#Function: formatted_for_editing(Content)
#Description: Formats it for a textarea.
function formatted_for_editing($content) {
	$content = str_replace(
		array(
			"&"
		),
		array(
			"&amp;"
		),
		$content);
	echo $content;
}

?>