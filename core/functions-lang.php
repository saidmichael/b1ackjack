<?php

if(BJ_LANG != "") {
	if(file_exists(BJPATH."content/langs/".BJ_LANG.".php")) {
		require BJPATH."content/langs/".BJ_LANG.".php";
	}
}

#Function: lecho(Text)
#Description: Prints based upon a language.
function _e($text) {
	global $lang;
	if(isset($lang[$text])) {
		echo $lang[$text];
	}
	else {
		echo $text;
	}
}

#Function: leturn(Text)
#Description: Returns based upon a language.
function _r($text) {
	global $lang;
	if(isset($lang[$text])) {
		return $lang[$text];
	}
	else {
		return $text;
	}
}

?>